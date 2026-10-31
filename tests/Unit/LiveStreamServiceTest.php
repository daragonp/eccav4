<?php

namespace Tests\Unit;

use App\Models\LiveStream;
use App\Services\LiveStreamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * Cubre las 8 filas de la tabla de estados de LiveStreamService (diseño §2):
 * precedencia de modos, ventana horaria semiabierta, detección YouTube y
 * fallback a ventana. El reloj se fija con Carbon::setTestNow() y la API se
 * aísla con Http::fake().
 */
class LiveStreamServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
        config()->set('app.timezone', 'America/Bogota');
        config()->set('services.youtube.api_key', null);
        config()->set('services.youtube.channel_id', null);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function service(): LiveStreamService
    {
        return app(LiveStreamService::class);
    }

    /** Fija "ahora" a un domingo 10:00 en Bogotá (dentro de 09:00–13:00). */
    private function fijarDomingoDentroDeVentana(): void
    {
        // 2024-06-16 es domingo.
        Carbon::setTestNow(Carbon::create(2024, 6, 16, 10, 0, 0, 'America/Bogota'));
    }

    /** Fija "ahora" a un lunes 10:00 (fuera de la ventana del domingo). */
    private function fijarFueraDeVentana(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 6, 17, 10, 0, 0, 'America/Bogota'));
    }

    // Fila 1: forzar_oculto → false, source=manual, sin API.
    public function test_forzar_oculto_devuelve_false(): void
    {
        $this->fijarDomingoDentroDeVentana();
        Http::fake();
        $live = LiveStream::current();
        $live->mode = 'forzar_oculto';
        $live->save();

        $state = $this->service()->getLiveState();

        $this->assertFalse($state['is_live']);
        $this->assertSame('manual', $state['source']);
        Http::assertNothingSent();
    }

    // Fila 2: forzar_vivo → true, source=manual, embed desde youtube_url, sin API.
    public function test_forzar_vivo_devuelve_true_con_embed(): void
    {
        $this->fijarFueraDeVentana();
        Http::fake();
        $live = LiveStream::current();
        $live->mode = 'forzar_vivo';
        $live->youtube_url = 'https://youtu.be/dQw4w9WgXcQ';
        $live->save();

        $state = $this->service()->getLiveState();

        $this->assertTrue($state['is_live']);
        $this->assertSame('manual', $state['source']);
        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ?autoplay=1', $state['embed_url']);
        Http::assertNothingSent();
    }

    // Fila 3: auto fuera de ventana → false, source=window, SIN llamar API.
    public function test_auto_fuera_de_ventana_devuelve_false_sin_api(): void
    {
        $this->fijarFueraDeVentana();
        Http::fake();
        config()->set('services.youtube.api_key', 'KEY');
        config()->set('services.youtube.channel_id', 'UC123');
        $live = LiveStream::current(); // mode auto por defecto

        $state = $this->service()->getLiveState();

        $this->assertFalse($state['is_live']);
        $this->assertSame('window', $state['source']);
        Http::assertNothingSent();
    }

    // Fila 4: auto dentro de ventana, sin channel_id → true, source=window, sin API.
    public function test_auto_dentro_sin_channel_devuelve_window(): void
    {
        $this->fijarDomingoDentroDeVentana();
        Http::fake();
        config()->set('services.youtube.api_key', 'KEY');
        // sin channel_id
        LiveStream::current();

        $state = $this->service()->getLiveState();

        $this->assertTrue($state['is_live']);
        $this->assertSame('window', $state['source']);
        Http::assertNothingSent();
    }

    // Fila 5: auto dentro de ventana, sin key → true, source=window, sin API.
    public function test_auto_dentro_sin_key_devuelve_window(): void
    {
        $this->fijarDomingoDentroDeVentana();
        Http::fake();
        config()->set('services.youtube.channel_id', 'UC123');
        // sin api_key
        LiveStream::current();

        $state = $this->service()->getLiveState();

        $this->assertTrue($state['is_live']);
        $this->assertSame('window', $state['source']);
        Http::assertNothingSent();
    }

    // Fila 6: auto dentro, YT cfg, API confirma directo → true, source=youtube_api.
    public function test_auto_dentro_api_confirma_directo(): void
    {
        $this->fijarDomingoDentroDeVentana();
        config()->set('services.youtube.api_key', 'KEY');
        config()->set('services.youtube.channel_id', 'UC123');
        Http::fake([
            'www.googleapis.com/*' => Http::response([
                'items' => [[
                    'id' => ['videoId' => 'ABCDEF12345'],
                    'snippet' => ['title' => 'Culto Dominical en vivo'],
                ]],
            ], 200),
        ]);
        LiveStream::current();

        $state = $this->service()->getLiveState();

        $this->assertTrue($state['is_live']);
        $this->assertSame('youtube_api', $state['source']);
        $this->assertSame('https://www.youtube.com/embed/ABCDEF12345?autoplay=1', $state['embed_url']);
        $this->assertSame('Culto Dominical en vivo', $state['title']);
        Http::assertSentCount(1);
    }

    // Fila 7: auto dentro, YT cfg, API dice "no en vivo" → true, source=window.
    public function test_auto_dentro_api_no_en_vivo_fallback_window(): void
    {
        $this->fijarDomingoDentroDeVentana();
        config()->set('services.youtube.api_key', 'KEY');
        config()->set('services.youtube.channel_id', 'UC123');
        Http::fake([
            'www.googleapis.com/*' => Http::response(['items' => []], 200),
        ]);
        LiveStream::current();

        $state = $this->service()->getLiveState();

        $this->assertTrue($state['is_live']);
        $this->assertSame('window', $state['source']);
    }

    // Fila 8: auto dentro, YT cfg, API falla (500) → true, source=window.
    public function test_auto_dentro_api_falla_fallback_window(): void
    {
        $this->fijarDomingoDentroDeVentana();
        config()->set('services.youtube.api_key', 'KEY');
        config()->set('services.youtube.channel_id', 'UC123');
        Http::fake([
            'www.googleapis.com/*' => Http::response('error', 500),
        ]);
        LiveStream::current();

        $state = $this->service()->getLiveState();

        $this->assertTrue($state['is_live']);
        $this->assertSame('window', $state['source']);
    }

    // Borde: a las 13:00 exactas (fin de ventana) ya NO está en vivo (semiabierto).
    public function test_borde_fin_de_ventana_es_exclusivo(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 6, 16, 13, 0, 0, 'America/Bogota'));
        Http::fake();
        LiveStream::current();

        $this->assertFalse($this->service()->isLive());
    }

    // Borde: a las 09:00 exactas (inicio) SÍ está en vivo (inclusivo).
    public function test_borde_inicio_de_ventana_es_inclusivo(): void
    {
        Carbon::setTestNow(Carbon::create(2024, 6, 16, 9, 0, 0, 'America/Bogota'));
        Http::fake();
        LiveStream::current();

        $this->assertTrue($this->service()->isLive());
    }
}
