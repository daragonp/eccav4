<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class BibleController extends Controller
{
    /** Lee y cachea el JSON (estructura: libro -> cap -> vers -> texto) */
    protected function bible(): array
    {
        return Cache::rememberForever('biblia_json', function () {
            // Ajusta la ruta si lo ubicas en otro sitio
            $path = storage_path('app/biblia.json');
            if (!is_readable($path)) {
                abort(500, 'No se encontró biblia.json en storage/app');
            }
            $raw = json_decode(file_get_contents($path), true) ?: [];

            // Normaliza textos (tu JSON trae a veces "')," al final; lo limpiamos)
            $clean = [];
            foreach ($raw as $libro => $caps) {
                $clean[$libro] = [];
                foreach ($caps as $cap => $vers) {
                    $clean[$libro][$cap] = [];
                    foreach ($vers as $num => $txt) {
                        $t = trim($txt);
                        // Quitar residuos "')," "')" ",)" etc.
                        $t = rtrim($t, " ,')");
                        $clean[$libro][$cap][$num] = $t;
                    }
                }
            }
            return $clean;
        });
    }

    /** Nombre bonito del libro: "1-corintios" -> "1 Corintios" */
    protected function pretty(string $slug): string
    {
        // Reemplaza guiones por espacios y capitaliza (sin tocar números)
        return collect(explode('-', $slug))
            ->map(fn ($p) => is_numeric($p) ? $p : Str::title($p))
            ->implode(' ');
    }

    public function index()
    {
        // La vista cargará datos vía endpoints JSON (para no inyectar ~4.5MB en HTML)
        return view('biblia.index');
    }

    /** GET /biblia/api/libros -> [{slug, name, chapters}] */
    public function apiBooks()
    {
        $data = $this->bible();

        $out = [];
        foreach ($data as $slug => $caps) {
            $out[] = [
                'slug'     => $slug,
                'name'     => $this->pretty($slug),
                'chapters' => count($caps),
            ];
        }

        // Ordena por AT/NT por defecto (opcional): aquí solo alfabético
        usort($out, fn($a, $b) => strnatcmp($a['name'], $b['name']));

        return response()->json($out);
    }

    /** GET /biblia/api/{libro} -> lista de capítulos disponibles: ["1","2",...] */
    public function apiChapters(string $libro)
    {
        $data = $this->bible();
        if (!isset($data[$libro])) abort(404, 'Libro no encontrado');
        return response()->json(array_keys($data[$libro]));
    }

    /** GET /biblia/api/{libro}/{cap} -> { book, chapter, verses:[{n, t}], pretty } */
    public function apiChapter(string $libro, string $cap)
    {
        $data = $this->bible();
        if (!isset($data[$libro])) abort(404, 'Libro no encontrado');
        if (!isset($data[$libro][$cap])) abort(404, 'Capítulo no encontrado');

        $versOk = [];
        foreach ($data[$libro][$cap] as $n => $t) {
            $versOk[] = ['n' => (int)$n, 't' => $t];
        }

        return response()->json([
            'book'   => $libro,
            'chapter'=> (int)$cap,
            'pretty' => $this->pretty($libro) . ' ' . $cap,
            'verses' => $versOk,
        ]);
    }

    /** GET /biblia/api/buscar?q=palabra -> matches con fragmento */
    public function apiSearch(Request $req)
    {
        $q = trim((string)$req->query('q', ''));
        if ($q === '' || mb_strlen($q) < 2) {
            return response()->json(['q' => $q, 'total' => 0, 'results' => []]);
        }

        $data = $this->bible();
        $needle = mb_strtolower($q);

        $results = [];
        foreach ($data as $libro => $caps) {
            foreach ($caps as $cap => $vers) {
                foreach ($vers as $n => $t) {
                    $hay = mb_strtolower($t);
                    $pos = mb_strpos($hay, $needle);
                    if ($pos !== false) {
                        // Genera snippet alrededor del match
                        $start = max(0, $pos - 40);
                        $len   = 80 + mb_strlen($q);
                        $frag  = mb_substr($t, $start, $len);
                        $results[] = [
                            'book'    => $libro,
                            'chapter' => (int)$cap,
                            'verse'   => (int)$n,
                            'ref'     => $this->pretty($libro)." $cap:$n",
                            'text'    => $t,
                            'snippet' => ($start > 0 ? '…' : '') . $frag . (mb_strlen($t) > $start + $len ? '…' : ''),
                        ];
                        // Limita por rendimiento (p.ej. 200)
                        if (count($results) >= 200) break 3;
                    }
                }
            }
        }

        return response()->json([
            'q'       => $q,
            'total'   => count($results),
            'results' => $results,
        ]);
    }
}
