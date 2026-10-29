<?php

namespace Tests\Feature;

use App\Models\ApiClient;
use App\Models\User;
use App\Models\Verse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Suite de feature del selector multi-versículo del "Versículo del día".
 *
 * Cubre la resolución server-side de text/reference/verses_refs en
 * VerseController@store/@edit y la extensión del payload de
 * Api\VerseApiController@today, incluyendo autenticación de la API privada.
 */
class VerseSelectorTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Aísla la caché compartida 'biblia_json' entre tests.
        Cache::flush();
    }

    /**
     * Crea y devuelve un usuario administrador autenticable para las rutas de
     * panel (middleware auth + admin).
     */
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

    /**
     * JSON serializado de un carrito de versículos, tal como lo envía el campo
     * oculto del navegador. Incluye a propósito pretty/text falsos para probar
     * que el backend los ignora y regenera desde biblia.json.
     */
    private function cartJson(array $refs): string
    {
        return json_encode($refs);
    }

    // ==================== STORE ====================

    public function test_store_con_carrito_regenera_text_y_reference_server_side(): void
    {
        $textoFalso = 'TEXTO FALSO ENVIADO POR EL NAVEGADOR';

        $res = $this->actingAs($this->admin())->post('/addverse', [
            'image'       => UploadedFile::fake()->image('verse.jpg'),
            'date'        => now()->toDateString(),
            'text'        => $textoFalso,
            'verses_refs' => $this->cartJson([
                ['book' => 'juan',   'chapter' => 3,  'verse' => 16, 'pretty' => 'FALSO', 'text' => 'FALSO'],
                ['book' => 'salmos', 'chapter' => 23, 'verse' => 1,  'pretty' => 'FALSO', 'text' => 'FALSO'],
            ]),
        ]);

        $res->assertSessionHasNoErrors();

        $verse = Verse::latest('id')->first();
        $this->assertNotNull($verse);

        // text regenerado server-side: distinto del falso y con las 2 líneas resueltas.
        $this->assertNotSame($textoFalso, $verse->text);
        $this->assertStringContainsString('Juan 3:16', $verse->text);
        $this->assertStringContainsString('Salmos 23:1', $verse->text);

        // reference combinada en orden del carrito.
        $this->assertSame('Juan 3:16; Salmos 23:1', $verse->reference);

        // verses_refs persistido como array de {book,chapter,verse,pretty,text} en orden.
        $this->assertIsArray($verse->verses_refs);
        $this->assertCount(2, $verse->verses_refs);
        $this->assertSame('juan', $verse->verses_refs[0]['book']);
        $this->assertSame(3, $verse->verses_refs[0]['chapter']);
        $this->assertSame(16, $verse->verses_refs[0]['verse']);
        $this->assertSame('Juan 3:16', $verse->verses_refs[0]['pretty']);
        $this->assertArrayHasKey('text', $verse->verses_refs[0]);
        $this->assertNotSame('FALSO', $verse->verses_refs[0]['text']);
        $this->assertSame('salmos', $verse->verses_refs[1]['book']);
    }

    public function test_store_sin_carrito_conserva_text_manual_y_deja_null(): void
    {
        $textoManual = 'Este es un texto escrito a mano sin selector.';

        $res = $this->actingAs($this->admin())->post('/addverse', [
            'image' => UploadedFile::fake()->image('verse.jpg'),
            'date'  => now()->toDateString(),
            'text'  => $textoManual,
            // verses_refs ausente
        ]);

        $res->assertSessionHasNoErrors();

        $verse = Verse::latest('id')->first();
        $this->assertSame($textoManual, $verse->text);
        $this->assertNull($verse->reference);
        $this->assertNull($verse->verses_refs);
    }

    public function test_store_sin_text_ni_carrito_falla_validacion(): void
    {
        $res = $this->actingAs($this->admin())->post('/addverse', [
            'image' => UploadedFile::fake()->image('verse.jpg'),
            'date'  => now()->toDateString(),
            // sin text ni verses_refs
        ]);

        $res->assertSessionHasErrors(['text']);
        $this->assertSame(0, Verse::count());
    }

    public function test_store_con_ref_inexistente_avisa_y_no_persiste(): void
    {
        $res = $this->actingAs($this->admin())->post('/addverse', [
            'image'       => UploadedFile::fake()->image('verse.jpg'),
            'date'        => now()->toDateString(),
            'text'        => 'texto cualquiera',
            'verses_refs' => $this->cartJson([
                ['book' => 'juan',   'chapter' => 3,   'verse' => 16],
                ['book' => 'salmos', 'chapter' => 999, 'verse' => 1],
            ]),
        ]);

        $res->assertSessionHasErrors(['verses_refs']);
        $this->assertSame(0, Verse::count());
    }

    public function test_store_con_carrito_malformado_falla_validacion(): void
    {
        $res = $this->actingAs($this->admin())->post('/addverse', [
            'image'       => UploadedFile::fake()->image('verse.jpg'),
            'date'        => now()->toDateString(),
            'text'        => 'texto cualquiera',
            'verses_refs' => 'esto no es json {{{',
        ]);

        $res->assertSessionHasErrors(['verses_refs']);
        $this->assertSame(0, Verse::count());
    }

    // ==================== EDIT ====================

    public function test_edit_regenera_server_side_y_persiste(): void
    {
        $verse = Verse::create([
            'image' => 'inicial.jpg',
            'date'  => now()->toDateString(),
            'text'  => 'texto inicial manual',
        ]);

        $res = $this->actingAs($this->admin())->post('/update-quote/' . $verse->id, [
            'date'        => now()->toDateString(),
            'text'        => 'TEXTO FALSO',
            'verses_refs' => $this->cartJson([
                ['book' => 'juan', 'chapter' => 3, 'verse' => 16],
            ]),
        ]);

        $res->assertSessionHasNoErrors();

        $verse->refresh();
        $this->assertStringContainsString('Juan 3:16', $verse->text);
        $this->assertNotSame('TEXTO FALSO', $verse->text);
        $this->assertSame('Juan 3:16', $verse->reference);
        $this->assertIsArray($verse->verses_refs);
        $this->assertCount(1, $verse->verses_refs);
        $this->assertSame('juan', $verse->verses_refs[0]['book']);
    }

    // ==================== API ====================

    private function bearer(): array
    {
        $issued = ApiClient::issue('Test');

        return ['Authorization' => 'Bearer ' . $issued['plain']];
    }

    public function test_api_today_incluye_reference_y_verses_desde_carrito(): void
    {
        Verse::create([
            'image'       => 'hoy.jpg',
            'date'        => now()->toDateString(),
            'text'        => 'Juan 3:16 — ...',
            'reference'   => 'Juan 3:16',
            'verses_refs' => [
                ['book' => 'juan', 'chapter' => 3, 'verse' => 16, 'pretty' => 'Juan 3:16', 'text' => 'Porque...'],
            ],
        ]);

        $res = $this->withHeaders($this->bearer())->getJson('/api/v1/verse/today');

        $res->assertStatus(200);
        $res->assertJsonStructure([
            'data' => [
                'id', 'date', 'text', 'reference', 'verses',
                'image_url', 'audio_url', 'pdf_url', 'created_at', 'updated_at',
            ],
        ]);
        $res->assertJsonPath('data.reference', 'Juan 3:16');
        $res->assertJsonPath('data.verses.0.book', 'juan');
        $res->assertJsonPath('data.verses.0.pretty', 'Juan 3:16');
    }

    public function test_api_today_versiculo_manual_devuelve_null_en_reference_y_verses(): void
    {
        Verse::create([
            'image' => 'hoy.jpg',
            'date'  => now()->toDateString(),
            'text'  => 'texto manual',
        ]);

        $res = $this->withHeaders($this->bearer())->getJson('/api/v1/verse/today');

        $res->assertStatus(200);
        $res->assertJsonPath('data.reference', null);
        $res->assertJsonPath('data.verses', null);
    }

    public function test_api_today_sin_versiculo_de_hoy_devuelve_404(): void
    {
        $res = $this->withHeaders($this->bearer())->getJson('/api/v1/verse/today');

        $res->assertStatus(404);
    }

    public function test_api_today_sin_auth_devuelve_401(): void
    {
        $res = $this->getJson('/api/v1/verse/today');

        $res->assertStatus(401);
    }
}
