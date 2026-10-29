<?php

namespace Tests\Unit;

use App\Services\BibleTextService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BibleTextServiceTest extends TestCase
{
    private BibleTextService $service;

    protected function setUp(): void
    {
        parent::setUp();
        // Aislamiento: la clave 'biblia_json' es rememberForever y compartida.
        Cache::flush();
        $this->service = new BibleTextService();
    }

    // -------------------- pretty / prettyRef --------------------

    public function test_pretty_convierte_slug_a_nombre_bonito(): void
    {
        $this->assertSame('Juan', $this->service->pretty('juan'));
        $this->assertSame('1 Corintios', $this->service->pretty('1-corintios'));
    }

    public function test_pretty_ref_formatea_capitulo_y_versiculo(): void
    {
        $this->assertSame('Juan 3:16', $this->service->prettyRef('juan', 3, 16));
        $this->assertSame('1 Corintios 13:4', $this->service->prettyRef('1-corintios', 13, 4));
    }

    // -------------------- resolveVerse --------------------

    public function test_resuelve_versiculo_existente(): void
    {
        $v = $this->service->resolveVerse('juan', 3, 16);

        $this->assertIsArray($v);
        $this->assertSame('juan', $v['book']);
        $this->assertSame(3, $v['chapter']);
        $this->assertSame(16, $v['verse']);
        $this->assertSame('Juan 3:16', $v['pretty']);
        $this->assertNotEmpty($v['text']);
    }

    public function test_resuelve_null_si_capitulo_no_existe(): void
    {
        $this->assertNull($this->service->resolveVerse('juan', 999, 1));
    }

    public function test_resuelve_null_si_libro_no_existe(): void
    {
        $this->assertNull($this->service->resolveVerse('noexiste', 1, 1));
    }

    // -------------------- buildTextAndReference --------------------

    public function test_compone_texto_y_referencia_de_varios_versiculos(): void
    {
        $result = $this->service->buildTextAndReference([
            ['book' => 'juan', 'chapter' => 3, 'verse' => 16],
            ['book' => 'salmos', 'chapter' => 23, 'verse' => 1],
            ['book' => 'romanos', 'chapter' => 8, 'verse' => 28],
        ]);

        // reference exacta y combinada.
        $this->assertSame('Juan 3:16; Salmos 23:1; Romanos 8:28', $result['reference']);

        // text: 3 líneas con el patrón '<pretty> — <texto>'.
        $lineas = explode("\n", $result['text']);
        $this->assertCount(3, $lineas);
        $this->assertStringStartsWith('Juan 3:16 — ', $lineas[0]);
        $this->assertStringStartsWith('Salmos 23:1 — ', $lineas[1]);
        $this->assertStringStartsWith('Romanos 8:28 — ', $lineas[2]);

        // resolvedRefs con 3 ítems de 5 claves cada uno.
        $this->assertCount(3, $result['resolvedRefs']);
        foreach ($result['resolvedRefs'] as $ref) {
            $this->assertSame(['book', 'chapter', 'verse', 'pretty', 'text'], array_keys($ref));
        }

        $this->assertSame([], $result['invalidRefs']);
    }

    public function test_separa_referencias_invalidas(): void
    {
        $result = $this->service->buildTextAndReference([
            ['book' => 'juan', 'chapter' => 3, 'verse' => 16],
            ['book' => 'noexiste', 'chapter' => 1, 'verse' => 1],
        ]);

        $this->assertCount(1, $result['resolvedRefs']);
        $this->assertSame('juan', $result['resolvedRefs'][0]['book']);

        $this->assertCount(1, $result['invalidRefs']);
        $this->assertSame('noexiste', $result['invalidRefs'][0]['book']);

        $this->assertSame('Juan 3:16', $result['reference']);
    }

    public function test_lista_vacia_devuelve_texto_vacio_y_referencia_null(): void
    {
        $result = $this->service->buildTextAndReference([]);

        $this->assertSame('', $result['text']);
        $this->assertNull($result['reference']);
        $this->assertSame([], $result['resolvedRefs']);
        $this->assertSame([], $result['invalidRefs']);
    }
}
