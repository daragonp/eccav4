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

    /** GET /biblia/api/{libro}/{cap}/page/{page} -> { book, chapter, verses:[{n, t}], pretty, pagination } */
    public function apiChapterPaginated(string $libro, string $cap, int $page = 1)
    {
        $data = $this->bible();
        if (!isset($data[$libro])) abort(404, 'Libro no encontrado');
        if (!isset($data[$libro][$cap])) abort(404, 'Capítulo no encontrado');

        $perPage = 20; // Versículos por página
        $verses = $data[$libro][$cap];
        $totalVerses = count($verses);
        $totalPages = ceil($totalVerses / $perPage);
        
        // Asegurar que la página solicitada sea válida
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;
        
        $versOk = [];
        $versesSlice = array_slice($verses, $offset, $perPage, true);
        
        foreach ($versesSlice as $n => $t) {
            $versOk[] = ['n' => (int)$n, 't' => $t];
        }

        return response()->json([
            'book'   => $libro,
            'chapter'=> (int)$cap,
            'pretty' => $this->pretty($libro) . ' ' . $cap,
            'verses' => $versOk,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_verses' => $totalVerses,
                'per_page' => $perPage,
                'has_prev' => $page > 1,
                'has_next' => $page < $totalPages,
                'prev_page' => $page > 1 ? $page - 1 : null,
                'next_page' => $page < $totalPages ? $page + 1 : null,
            ]
        ]);
    }

    /** GET /biblia/api/inicio -> { book, chapter, verses:[{n, t}], pretty } */
    public function apiStart()
    {
        $data = $this->bible();
        
        // Génesis 1:1-10 por defecto
        $libro = 'genesis';
        $cap = '1';
        
        if (!isset($data[$libro]) || !isset($data[$libro][$cap])) {
            // Si Génesis no está disponible, tomar el primer libro y capítulo disponibles
            reset($data);
            $libro = key($data);
            $cap = key($data[$libro]);
        }
        
        $versOk = [];
        $count = 0;
        foreach ($data[$libro][$cap] as $n => $t) {
            if ($count >= 10) break;
            $versOk[] = ['n' => (int)$n, 't' => $t];
            $count++;
        }

        return response()->json([
            'book'   => $libro,
            'chapter'=> (int)$cap,
            'pretty' => $this->pretty($libro) . ' ' . $cap,
            'verses' => $versOk,
            'is_start' => true,
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
                        
                        // Resaltar el texto encontrado
                        $highlighted = str_ireplace($q, "<mark>{$q}</mark>", $t);
                        
                        $results[] = [
                            'book'    => $libro,
                            'chapter' => (int)$cap,
                            'verse'   => (int)$n,
                            'ref'     => $this->pretty($libro)." $cap:$n",
                            'text'    => $t,
                            'highlighted' => $highlighted,
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

    /** GET /biblia/api/versiculo/{libro}/{cap}/{vers} -> { book, chapter, verse, text, pretty, navigation } */
    public function apiVerse(string $libro, string $cap, string $vers)
    {
        $data = $this->bible();
        if (!isset($data[$libro])) abort(404, 'Libro no encontrado');
        if (!isset($data[$libro][$cap])) abort(404, 'Capítulo no encontrado');
        if (!isset($data[$libro][$cap][$vers])) abort(404, 'Versículo no encontrado');

        $verseText = $data[$libro][$cap][$vers];
        $verseNum = (int)$vers;
        $capNum = (int)$cap;
        
        // Navegación
        $hasPrev = isset($data[$libro][$cap][$verseNum - 1]);
        $prevVerse = $hasPrev ? ['verse' => $verseNum - 1, 'text' => $data[$libro][$cap][$verseNum - 1]] : null;
        
        $hasNext = isset($data[$libro][$cap][$verseNum + 1]);
        $nextVerse = $hasNext ? ['verse' => $verseNum + 1, 'text' => $data[$libro][$cap][$verseNum + 1]] : null;
        
        // Navegación entre capítulos
        $caps = array_keys($data[$libro]);
        $currentCapIndex = array_search($cap, $caps);
        
        $hasPrevChapter = $currentCapIndex > 0;
        $prevChapter = $hasPrevChapter ? [
            'chapter' => (int)$caps[$currentCapIndex - 1],
            'last_verse' => (int)array_key_last($data[$libro][$caps[$currentCapIndex - 1]])
        ] : null;
        
        $hasNextChapter = $currentCapIndex < count($caps) - 1;
        $nextChapter = $hasNextChapter ? [
            'chapter' => (int)$caps[$currentCapIndex + 1],
            'first_verse' => 1
        ] : null;
        
        // Navegación entre libros
        $books = array_keys($data);
        $currentBookIndex = array_search($libro, $books);
        
        $hasPrevBook = $currentBookIndex > 0;
        $prevBook = $hasPrevBook ? [
            'book' => $books[$currentBookIndex - 1],
            'last_chapter' => (int)array_key_last($data[$books[$currentBookIndex - 1]]),
            'last_verse' => (int)array_key_last($data[$books[$currentBookIndex - 1]][array_key_last($data[$books[$currentBookIndex - 1]])])
        ] : null;
        
        $hasNextBook = $currentBookIndex < count($books) - 1;
        $nextBook = $hasNextBook ? [
            'book' => $books[$currentBookIndex + 1],
            'first_chapter' => 1,
            'first_verse' => 1
        ] : null;

        return response()->json([
            'book'   => $libro,
            'chapter'=> $capNum,
            'verse'  => $verseNum,
            'text'   => $verseText,
            'pretty' => $this->pretty($libro) . ' ' . $capNum . ':' . $verseNum,
            'navigation' => [
                'prev_verse' => $prevVerse,
                'next_verse' => $nextVerse,
                'prev_chapter' => $prevChapter,
                'next_chapter' => $nextChapter,
                'prev_book' => $prevBook,
                'next_book' => $nextBook,
            ]
        ]);
    }
}