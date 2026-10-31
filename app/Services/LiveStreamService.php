<?php

namespace App\Services;

use App\Models\LiveStream;
use App\Support\YouTube;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Resuelve "¿estamos en vivo ahora?" combinando tres señales en orden de
 * precedencia: override manual (mode) → ventana horaria → detección por la
 * YouTube Data API v3.
 *
 * Regla de precedencia (ver diseño §2):
 *   - forzar_oculto → is_live=false (fin, no mira ventana ni API).
 *   - forzar_vivo   → is_live=true, source='manual' (fin, no llama API).
 *   - auto          → ventana en config('app.timezone'); fuera → false sin API;
 *                     dentro → true. YouTube solo refina el embed cuando
 *                     confirma un directo; nunca apaga la señal de ventana.
 *
 * La clave de YouTube nunca sale del servidor: se lee vía
 * config('services.youtube.*). Sin clave/canal, degrada a ventana sin fallar.
 */
class LiveStreamService
{
    /** Atajo: true si estamos en vivo ahora. */
    public function isLive(): bool
    {
        return $this->getLiveState()['is_live'];
    }

    /**
     * Estado completo normalizado del directo. Única fuente de verdad de la
     * decisión. Forma de retorno:
     *   is_live, platform, embed_url, watch_urls[], title, source.
     */
    public function getLiveState(): array
    {
        $live = LiveStream::current();

        // 1) Override manual: forzar oculto.
        if ($live->mode === 'forzar_oculto') {
            return $this->state(false, $live, 'manual');
        }

        // 2) Override manual: forzar en vivo (red de seguridad para redes sin API).
        if ($live->mode === 'forzar_vivo') {
            return $this->state(true, $live, 'manual');
        }

        // 3) Modo automático: evaluar ventana horaria.
        if (! $this->dentroDeVentana($live)) {
            // Fuera de ventana: NO se llama a la API (ahorro de cuota).
            return $this->state(false, $live, 'window');
        }

        // Dentro de ventana. "YouTube configurado" = canal efectivo + api_key.
        $channelId = $live->youtube_channel_id ?: config('services.youtube.channel_id');
        $apiKey    = config('services.youtube.api_key');

        if (filled($channelId) && filled($apiKey)) {
            $detectado = $this->detectYouTubeLive($channelId, $apiKey);

            if ($detectado && ! empty($detectado['live'])) {
                // La API confirma directo: refina el embed con el videoId real.
                return $this->state(true, $live, 'youtube_api', $detectado);
            }
        }

        // Fallback: dentro de ventana sin confirmación de YouTube (no cfg,
        // sin key, caída o "no en vivo") ⇒ is_live=true, source='window'.
        return $this->state(true, $live, 'window');
    }

    /**
     * ¿La hora actual cae en la ventana del culto? Normaliza ambos extremos a
     * HH:MM (la columna `time` llega como HH:MM:SS) e intervalo semiabierto
     * [inicio, fin) en config('app.timezone').
     */
    protected function dentroDeVentana(LiveStream $live): bool
    {
        $tz    = config('app.timezone');
        $now   = now($tz);
        $nowHm = $now->format('H:i');

        $startHm = substr((string) $live->window_start, 0, 5);
        $endHm   = substr((string) $live->window_end, 0, 5);

        $days = is_array($live->window_days)
            ? array_map('intval', $live->window_days)
            : [(int) $live->window_day];

        return in_array($now->dayOfWeekIso, $days, true)
            && $nowHm >= $startHm
            && $nowHm < $endHm;
    }

    /**
     * Consulta la YouTube Data API v3 (search.list, eventType=live) para
     * localizar el directo activo del canal. Devuelve el resultado normalizado
     * ['live'=>bool, 'video_id'=>?string, 'title'=>?string] o null.
     *
     * Aislado en un método protegido para poder mockearlo/cubrirlo con
     * Http::fake() en los tests. Cachea 3 min el resultado positivo/negativo y
     * 30 s el fallo, para no martillar la API dentro de la ventana.
     */
    protected function detectYouTubeLive(string $channelId, string $apiKey): ?array
    {
        return Cache::remember('livestream:youtube_detect', now()->addMinutes(3), function () use ($channelId, $apiKey) {
            try {
                $response = Http::timeout(5)
                    ->acceptJson()
                    ->get('https://www.googleapis.com/youtube/v3/search', [
                        'part'       => 'snippet',
                        'channelId'  => $channelId,
                        'eventType'  => 'live',
                        'type'       => 'video',
                        'key'        => $apiKey,
                        'maxResults' => 1,
                    ]);

                if (! $response->successful()) {
                    // No cachear el fallo mucho tiempo: TTL corto.
                    Cache::put('livestream:youtube_detect', ['live' => false, 'video_id' => null, 'title' => null], now()->addSeconds(30));
                    Log::warning('YouTube live detect: respuesta no exitosa', ['status' => $response->status()]);

                    return ['live' => false, 'video_id' => null, 'title' => null];
                }

                $items = $response->json('items', []);
                $videoId = $items[0]['id']['videoId'] ?? null;

                if (empty($videoId)) {
                    return ['live' => false, 'video_id' => null, 'title' => null];
                }

                return [
                    'live'     => true,
                    'video_id' => $videoId,
                    'title'    => $items[0]['snippet']['title'] ?? null,
                ];
            } catch (\Throwable $e) {
                Cache::put('livestream:youtube_detect', ['live' => false, 'video_id' => null, 'title' => null], now()->addSeconds(30));
                Log::warning('YouTube live detect falló', ['e' => $e->getMessage()]);

                return ['live' => false, 'video_id' => null, 'title' => null];
            }
        });
    }

    /**
     * Construye el array de estado normalizado a partir de la decisión tomada.
     */
    protected function state(bool $isLive, LiveStream $live, string $source, ?array $detectado = null): array
    {
        $platform = $live->platform ?: 'youtube';

        return [
            'is_live'    => $isLive,
            'platform'   => $platform,
            'embed_url'  => $isLive ? $this->embedUrl($live, $platform, $source, $detectado) : null,
            'watch_urls' => [
                'youtube'   => $live->youtube_url ?: null,
                'facebook'  => $live->facebook_url ?: null,
                'tiktok'    => $live->tiktok_url ?: null,
                'x'         => $live->x_url ?: null,
                'instagram' => $live->instagram_url ?: null,
            ],
            'title'      => $detectado['title'] ?? ($live->title ?: null),
            'source'     => $source,
        ];
    }

    /**
     * Deriva la URL de embed según la plataforma principal. Puede ser null si
     * no hay datos suficientes (el front muestra entonces solo los enlaces).
     */
    protected function embedUrl(LiveStream $live, string $platform, string $source, ?array $detectado): ?string
    {
        if ($platform === 'facebook') {
            if (empty($live->facebook_url)) {
                return null;
            }

            return 'https://www.facebook.com/plugins/video.php?href='
                . urlencode($live->facebook_url) . '&autoplay=1';
        }

        // YouTube (por defecto).
        if ($source === 'youtube_api' && ! empty($detectado['video_id'])) {
            return 'https://www.youtube.com/embed/' . $detectado['video_id'] . '?autoplay=1';
        }

        $videoId = YouTube::extractId($live->youtube_url);

        return $videoId ? 'https://www.youtube.com/embed/' . $videoId . '?autoplay=1' : null;
    }
}
