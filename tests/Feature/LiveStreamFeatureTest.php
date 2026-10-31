<?php

namespace Tests\Feature;

use App\Models\LiveStream;
use App\Models\User;
use App\Models\Worship;
use App\Services\LiveStreamService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Mockery;
use Tests\TestCase;

/**
 * Feature de "Culto en Vivo": endpoint proxy, página pública, banner en home,
 * panel protegido, persistencia, override y archivado como Worship.
 */
class LiveStreamFeatureTest extends TestCase
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

    private function admin(): User
    {
        $user = new User();
        $user->name = 'Admin de prueba';
        $user->email = 'admin_' . uniqid() . '@test.local';
        $user->password = bcrypt('secret');
        $user->image = '';
        $user->save();

        $role = \Spatie\Permission\Models\Role::firstOrCreate([
            'name'       => 'administrador',
            'guard_name' => 'web',
        ]);
        $user->assignRole($role);

        return $user->fresh();
    }

    /** Reemplaza el servicio por uno que devuelve un estado fijo. */
    private function forzarEstado(array $state): void
    {
        $mock = Mockery::mock(LiveStreamService::class);
        $mock->shouldReceive('getLiveState')->andReturn($state);
        $mock->shouldReceive('isLive')->andReturn($state['is_live']);
        $this->app->instance(LiveStreamService::class, $mock);
    }

    private function estadoEnVivo(): array
    {
        return [
            'is_live'    => true,
            'platform'   => 'youtube',
            'embed_url'  => 'https://www.youtube.com/embed/ABCDEF12345?autoplay=1',
            'watch_urls' => ['youtube' => 'https://youtu.be/ABCDEF12345', 'facebook' => null, 'tiktok' => null, 'x' => null, 'instagram' => null],
            'title'      => 'Culto Dominical',
            'source'     => 'youtube_api',
        ];
    }

    private function estadoSinDirecto(): array
    {
        return [
            'is_live'    => false,
            'platform'   => 'youtube',
            'embed_url'  => null,
            'watch_urls' => ['youtube' => null, 'facebook' => null, 'tiktok' => null, 'x' => null, 'instagram' => null],
            'title'      => null,
            'source'     => 'window',
        ];
    }

    // ==================== Endpoint + página pública ====================

    public function test_endpoint_live_status_devuelve_json_con_contrato(): void
    {
        $this->forzarEstado($this->estadoEnVivo());

        $res = $this->getJson('/api/live-status');

        $res->assertOk()
            ->assertJsonStructure(['is_live', 'platform', 'embed_url', 'watch_urls', 'title', 'source']);
    }

    public function test_pagina_en_vivo_con_directo_muestra_iframe(): void
    {
        $this->forzarEstado($this->estadoEnVivo());

        $res = $this->get('/en-vivo');

        $res->assertOk();
        $res->assertSee('iframe', false);
        $res->assertSee('youtube.com/embed/ABCDEF12345', false);
    }

    public function test_pagina_en_vivo_sin_directo_muestra_mensaje(): void
    {
        $this->forzarEstado($this->estadoSinDirecto());

        $res = $this->get('/en-vivo');

        $res->assertOk();
        $res->assertSee('No hay transmisión en vivo en este momento');
    }

    public function test_home_incluye_el_banner(): void
    {
        $res = $this->get('/');

        $res->assertOk();
        $res->assertSee('/api/live-status', false);
    }

    // ==================== Panel protegido ====================

    public function test_show_live_sin_auth_redirige(): void
    {
        $this->get('/show-live')->assertStatus(302);
    }

    public function test_show_live_autenticado_responde_ok(): void
    {
        $this->actingAs($this->admin())->get('/show-live')->assertOk();
    }

    public function test_dashboard_recibe_live_setting_desde_controlador(): void
    {
        $res = $this->actingAs($this->admin())->get('/dashboard');
        $res->assertOk();
        $res->assertViewHas('liveSetting');
    }

    public function test_update_live_valido_persiste(): void
    {
        $res = $this->actingAs($this->admin())->post('/update-live', [
            'platform'     => 'youtube',
            'window_day'   => 7,
            'window_start' => '09:00',
            'window_end'   => '13:00',
            'mode'         => 'forzar_oculto',
            'youtube_url'  => 'https://youtu.be/ABCDEF12345',
        ]);

        $res->assertRedirect();
        $live = LiveStream::current();
        $this->assertSame('forzar_oculto', $live->mode);
        $this->assertSame('https://youtu.be/ABCDEF12345', $live->youtube_url);
        $this->assertSame([7], $live->window_days);
    }

    public function test_update_live_con_multiples_dias_persiste(): void
    {
        $res = $this->actingAs($this->admin())->post('/update-live', [
            'platform'     => 'youtube',
            'window_days'  => [3, 7],
            'window_start' => '09:00',
            'window_end'   => '13:00',
            'mode'         => 'auto',
        ]);

        $res->assertRedirect();
        $live = LiveStream::current();
        $this->assertSame([3, 7], $live->window_days);
    }

    public function test_update_live_invalido_devuelve_errores(): void
    {
        $res = $this->actingAs($this->admin())->post('/update-live', [
            'platform'     => 'tiktok', // inválido
            'window_day'   => 7,
            'window_start' => '13:00',
            'window_end'   => '09:00', // fin antes del inicio
            'mode'         => 'auto',
        ]);

        $res->assertSessionHasErrors(['platform', 'window_end']);
    }

    public function test_toggle_live_alterna_modo(): void
    {
        $admin = $this->admin();
        LiveStream::current(); // mode auto

        $this->actingAs($admin)->post('/toggle-live')->assertRedirect();
        $this->assertSame('forzar_vivo', LiveStream::current()->fresh()->mode);

        $this->actingAs($admin)->post('/toggle-live')->assertRedirect();
        $this->assertSame('auto', LiveStream::current()->fresh()->mode);
    }

    // ==================== Archivado ====================

    public function test_archivar_sin_url_devuelve_error(): void
    {
        $this->actingAs($this->admin())->post('/archive-live')
            ->assertSessionHasErrors(['url_grabacion']);
    }

    public function test_archivar_crea_worship_enlazado(): void
    {
        $live = LiveStream::current();
        $live->url_grabacion = 'https://youtu.be/ABCDEF12345';
        $live->title = 'Culto de prueba';
        $live->save();

        $this->actingAs($this->admin())->post('/archive-live')->assertRedirect();

        $worship = Worship::where('title', 'Culto de prueba')->first();
        $this->assertNotNull($worship);
        $this->assertSame('https://youtu.be/ABCDEF12345', $worship->video);
        $this->assertSame('https://youtu.be/ABCDEF12345', $worship->urlyt);
        $this->assertSame('Culto Dominical', $worship->badge);
        $this->assertNotNull($worship->abstract);
        $this->assertNotNull($worship->pdfdoc);
        $this->assertSame(7, Carbon::parse($worship->broadcast)->dayOfWeekIso);
    }

    public function test_archivar_dos_veces_mismo_domingo_no_duplica(): void
    {
        $live = LiveStream::current();
        $live->url_grabacion = 'https://youtu.be/ABCDEF12345';
        $live->title = 'Culto repetido';
        $live->save();

        $admin = $this->admin();
        $this->actingAs($admin)->post('/archive-live');
        $this->actingAs($admin)->post('/archive-live');

        $this->assertSame(1, Worship::where('broadcast', $this->ultimoDomingo())->count());
    }

    public function test_archivar_desambigua_title_colisionante(): void
    {
        // Culto preexistente con el mismo título base pero otra fecha.
        Worship::create([
            'title'    => 'Culto colisión',
            'slug'     => 'culto-colision-viejo',
            'abstract' => '',
            'badge'    => 'Culto Dominical',
            'pdfdoc'   => '',
            'autor'    => 'Otro',
            'broadcast' => '2000-01-02', // domingo antiguo
        ]);

        $live = LiveStream::current();
        $live->url_grabacion = 'https://youtu.be/ABCDEF12345';
        $live->title = 'Culto colisión';
        $live->save();

        $this->actingAs($this->admin())->post('/archive-live')->assertRedirect();

        $nuevo = Worship::where('broadcast', $this->ultimoDomingo())->first();
        $this->assertNotNull($nuevo);
        $this->assertSame('Culto colisión (2)', $nuevo->title);
    }

    private function ultimoDomingo(): string
    {
        $fecha = Carbon::now(config('app.timezone'))->startOfDay();
        if ($fecha->dayOfWeekIso !== 7) {
            $fecha = $fecha->previous(Carbon::SUNDAY);
        }

        return $fecha->toDateString();
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
