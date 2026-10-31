<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveStream;
use App\Models\Worship;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Panel de administración de "Culto en Vivo".
 *
 * Gestiona el singleton LiveStream::current(): ajustes de transmisión
 * (show/update), el botón de override de un clic (toggleLive) y el archivado
 * asistido de la grabación como registro Worship (archive).
 */
class LiveSettingController extends Controller
{
    /** Vista de ajustes del singleton. */
    public function show()
    {
        return view('admin.live.index', [
            'live' => LiveStream::current(),
        ]);
    }

    /** Valida y guarda la configuración sobre el singleton. */
    public function update(Request $request)
    {
        $request->validate([
            'platform'           => ['required', 'in:youtube,facebook'],
            'youtube_channel_id' => ['nullable', 'string', 'max:64'],
            'youtube_url'        => ['nullable', 'url', 'max:500'],
            'facebook_url'       => ['nullable', 'url', 'max:500'],
            'tiktok_url'         => ['nullable', 'url', 'max:500'],
            'x_url'              => ['nullable', 'url', 'max:500'],
            'instagram_url'      => ['nullable', 'url', 'max:500'],
            'window_days'        => ['required_without:window_day', 'array', 'min:1'],
            'window_days.*'      => ['integer', 'between:1,7'],
            'window_day'         => ['required_without:window_days', 'integer', 'between:1,7'],
            'window_start'       => ['required', 'date_format:H:i'],
            'window_end'         => ['required', 'date_format:H:i', 'after:window_start'],
            'mode'               => ['required', 'in:auto,forzar_vivo,forzar_oculto'],
            'archivar'           => ['nullable', 'boolean'],
            'url_grabacion'      => ['nullable', 'url', 'max:500'],
            'title'              => ['nullable', 'string', 'max:255'],
        ], [
            'platform.required'            => 'La plataforma principal es obligatoria.',
            'platform.in'                  => 'La plataforma debe ser YouTube o Facebook.',
            'youtube_url.url'              => 'La URL de YouTube no es válida.',
            'facebook_url.url'             => 'La URL de Facebook no es válida.',
            'tiktok_url.url'               => 'La URL de TikTok no es válida.',
            'x_url.url'                    => 'La URL de X no es válida.',
            'instagram_url.url'            => 'La URL de Instagram no es válida.',
            'window_days.required_without' => 'Debes seleccionar al menos un día de transmisión.',
            'window_days.min'              => 'Debes seleccionar al menos un día de transmisión.',
            'window_days.*.between'        => 'Cada día debe estar entre lunes (1) y domingo (7).',
            'window_day.required_without'  => 'El día de la ventana es obligatorio.',
            'window_day.between'           => 'El día debe estar entre lunes (1) y domingo (7).',
            'window_start.required'        => 'La hora de inicio es obligatoria.',
            'window_start.date_format'     => 'La hora de inicio debe tener formato HH:MM.',
            'window_end.required'          => 'La hora de fin es obligatoria.',
            'window_end.date_format'       => 'La hora de fin debe tener formato HH:MM.',
            'window_end.after'             => 'La hora de fin debe ser posterior a la de inicio.',
            'mode.required'                => 'El modo de visibilidad es obligatorio.',
            'mode.in'                      => 'El modo debe ser Automático, Forzar en vivo o Forzar oculto.',
            'url_grabacion.url'            => 'La URL de la grabación no es válida.',
        ]);

        $live = LiveStream::current();
        $live->platform           = $request->input('platform');
        $live->youtube_channel_id = $request->input('youtube_channel_id');
        $live->youtube_url        = $request->input('youtube_url');
        $live->facebook_url       = $request->input('facebook_url');
        $live->tiktok_url         = $request->input('tiktok_url');
        $live->x_url              = $request->input('x_url');
        $live->instagram_url      = $request->input('instagram_url');
        if ($request->has('window_days')) {
            $live->window_days = array_values(array_unique(array_map('intval', (array) $request->input('window_days'))));
        } elseif ($request->has('window_day')) {
            $live->window_days = [(int) $request->input('window_day')];
        }
        $live->window_start       = $request->input('window_start') . ':00';
        $live->window_end         = $request->input('window_end') . ':00';
        $live->mode               = $request->input('mode');
        $live->archivar           = $request->boolean('archivar');
        $live->url_grabacion      = $request->input('url_grabacion');
        $live->title              = $request->input('title');
        $live->save();

        $this->olvidarCache();

        return back()->with('success', 'Configuración de la transmisión guardada correctamente.');
    }

    /**
     * Override de un clic: alterna forzar_vivo ↔ auto. No usa forzar_oculto
     * (ese queda disponible explícitamente en el formulario) para que al
     * finalizar la lógica normal retome el control.
     */
    public function toggleLive()
    {
        $live = LiveStream::current();
        $live->mode = $live->mode !== 'forzar_vivo' ? 'forzar_vivo' : 'auto';
        $live->save();

        $this->olvidarCache();

        $mensaje = $live->mode === 'forzar_vivo'
            ? 'Transmisión forzada EN VIVO.'
            : 'Transmisión finalizada: vuelve al modo automático.';

        return back()->with('success', $mensaje);
    }

    /**
     * "Archivar como culto": crea/actualiza un Worship enlazado a la grabación
     * del último domingo. Idempotente por `broadcast` (un culto por domingo a
     * nivel de aplicación). Considera filas soft-deleted (withTrashed) porque
     * el índice UNIQUE de `title` sigue activo en BD aunque la fila esté borrada.
     */
    public function archive()
    {
        $live = LiveStream::current();

        if (empty($live->url_grabacion)) {
            return back()->withErrors(['url_grabacion' => 'Debes indicar la URL de la grabación antes de archivar.']);
        }

        // Último domingo <= hoy en la zona horaria del proyecto.
        $fecha = Carbon::now(config('app.timezone'))->startOfDay();
        if ($fecha->dayOfWeekIso !== 7) {
            $fecha = $fecha->previous(Carbon::SUNDAY);
        }
        $fecha = $fecha->toDateString();

        // Si ya existe un culto (incluso borrado) para ese domingo, se restaura.
        $existing = Worship::withTrashed()->where('broadcast', $fecha)->first();
        if ($existing && $existing->trashed()) {
            $existing->restore();
        }

        // Desambiguación del title (columna UNIQUE), contando soft-deleted.
        $baseTitle = $live->title ?: ('Culto Dominical — ' . Carbon::parse($fecha)->format('d/m/Y'));
        $title = $baseTitle;
        $n = 1;
        while (Worship::withTrashed()
                ->where('title', $title)
                ->where('broadcast', '!=', $fecha)
                ->exists()) {
            $title = $baseTitle . ' (' . (++$n) . ')';
        }

        Worship::withTrashed()->updateOrCreate(
            ['broadcast' => $fecha],
            [
                'title'    => $title,
                'slug'     => Str::slug($title),
                'abstract' => '',
                'badge'    => 'Culto Dominical',
                'pdfdoc'   => '',
                'autor'    => 'Culto en Vivo',
                'video'    => $live->url_grabacion,
                'urlyt'    => $live->url_grabacion,
            ]
        );

        return back()->with('success', 'Culto archivado correctamente.');
    }

    /** Invalida los cachés del estado para que el cambio se refleje ya. */
    protected function olvidarCache(): void
    {
        Cache::forget('livestream:status');
        Cache::forget('livestream:youtube_detect');
    }
}
