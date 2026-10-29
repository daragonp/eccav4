<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

/**
 * Servicio de dominio para resolver texto, referencia y lista estructurada de
 * versículos contra storage/app/data/biblia.json.
 *
 * Es PHP puro sin HTTP: espeja la carga, limpieza y formateo de BibleController
 * pero lanza RuntimeException en lugar de abort(500), de modo que la lógica sea
 * testeable unitariamente sin servidor ni rutas. El backend es la única autoridad
 * que resuelve el contenido de los versículos, evitando confiar en datos del
 * navegador.
 */
class BibleTextService
{
    /**
     * Lee y cachea el JSON (estructura: libro -> cap -> vers -> texto).
     *
     * Comparte la clave de caché 'biblia_json' con BibleController, por lo que no
     * hay doble lectura de disco. La limpieza de textos es idéntica a la del
     * controlador para que el texto resuelto coincida carácter a carácter con el
     * que la UI muestra vía /biblia/api/*.
     */
    protected function bible(): array
    {
        return Cache::rememberForever('biblia_json', function () {
            $path = storage_path('app/data/biblia.json');
            if (!is_readable($path)) {
                // Mismo criterio que el controlador, pero sin acoplar a HTTP: la capa
                // de dominio lanza una excepción que el llamador decide cómo traducir.
                throw new \RuntimeException('No se encontró biblia.json en storage/app/data');
            }
            $raw = json_decode(file_get_contents($path), true) ?: [];

            // Normaliza textos (el JSON trae a veces "')," al final; lo limpiamos).
            $clean = [];
            foreach ($raw as $libro => $caps) {
                $clean[$libro] = [];
                foreach ($caps as $cap => $vers) {
                    $clean[$libro][$cap] = [];
                    foreach ($vers as $num => $txt) {
                        $t = trim($txt);
                        // Quitar el prefijo de salto de línea mal escapado del dato fuente
                        // (muchos versículos del JSON empiezan por "/n" o "\n" literal).
                        $t = preg_replace('#^\s*(?:/n|\\\\n)+\s*#', '', $t);
                        // Quitar residuos "')," "')" ",)" etc.
                        $t = rtrim($t, " ,')");
                        $clean[$libro][$cap][$num] = trim($t);
                    }
                }
            }
            return $clean;
        });
    }

    /**
     * Nombre bonito del libro: "1-corintios" -> "1 Corintios".
     * Espejo de BibleController::pretty().
     */
    public function pretty(string $slug): string
    {
        // Reemplaza guiones por espacios y capitaliza (sin tocar números).
        return collect(explode('-', $slug))
            ->map(fn ($p) => is_numeric($p) ? $p : Str::title($p))
            ->implode(' ');
    }

    /**
     * Referencia bonita de un versículo concreto: "Juan 3:16".
     * Único formateador del patrón capítulo:versículo del servicio.
     */
    public function prettyRef(string $book, int $chapter, int $verse): string
    {
        return $this->pretty($book) . ' ' . $chapter . ':' . $verse;
    }

    /**
     * Resuelve un único versículo contra el JSON.
     *
     * Devuelve null si el libro, capítulo o versículo no existen. Si existe,
     * devuelve ['book','chapter','verse','pretty','text'].
     */
    public function resolveVerse(string $book, int $chapter, int $verse): ?array
    {
        $data = $this->bible();

        // El JSON se indexa por claves string; casteamos para indexar de forma segura.
        if (!isset($data[$book][(string) $chapter][(string) $verse])) {
            return null;
        }

        return [
            'book'    => $book,
            'chapter' => $chapter,
            'verse'   => $verse,
            'pretty'  => $this->prettyRef($book, $chapter, $verse),
            'text'    => $data[$book][(string) $chapter][(string) $verse],
        ];
    }

    /**
     * Resuelve una lista de referencias {book,chapter,verse} y compone el texto,
     * la cita combinada y las listas de resueltas/inválidas.
     *
     * - text: líneas '<pretty> — <text>' unidas por "\n" (em dash U+2014 rodeado
     *   de espacios); cadena vacía si no hay versículos resueltos.
     * - reference: cita combinada 'Juan 3:16; Salmos 23:1'; null si no hay resueltos.
     * - resolvedRefs: array de {book,chapter,verse,pretty,text} en orden de entrada.
     * - invalidRefs: referencias que no se pudieron resolver.
     */
    public function buildTextAndReference(array $refs): array
    {
        $resolved = [];
        $invalid = [];

        foreach ($refs as $ref) {
            $book = $ref['book'] ?? null;
            $chapter = $ref['chapter'] ?? null;
            $verse = $ref['verse'] ?? null;

            $resolvedVerse = ($book !== null && $chapter !== null && $verse !== null)
                ? $this->resolveVerse((string) $book, (int) $chapter, (int) $verse)
                : null;

            if ($resolvedVerse === null) {
                $invalid[] = $ref;
                continue;
            }

            $resolved[] = $resolvedVerse;
        }

        $text = implode("\n", array_map(
            fn ($v) => $v['pretty'] . ' — ' . $v['text'],
            $resolved
        ));

        $reference = $resolved
            ? implode('; ', array_map(fn ($v) => $v['pretty'], $resolved))
            : null;

        return [
            'text'         => $text,
            'reference'    => $reference,
            'resolvedRefs' => $resolved,
            'invalidRefs'  => $invalid,
        ];
    }
}
