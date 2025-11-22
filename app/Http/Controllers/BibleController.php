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
    
    /** Información de los libros organizados por testamento */
    protected function booksInfo(): array
    {
        return Cache::rememberForever('biblia_books_info', function () {
            // Lista de libros del Antiguo Testamento en orden bíblico
            $oldTestament = [
                'genesis', 'exodo', 'levitico', 'numeros', 'deuteronomio', 'josue', 'jueces', 'rut',
                '1-samuel', '2-samuel', '1-reyes', '2-reyes', '1-cronicas', '2-cronicas', 'esdras',
                'nehemias', 'ester', 'job', 'salmos', 'proverbios', 'eclesiastes', 'cantares',
                'isaias', 'jeremias', 'lamentaciones', 'ezequiel', 'daniel', 'oseas', 'joel',
                'amos', 'abdias', 'jonas', 'miqueas', 'nahum', 'habacuc', 'sofonias', 'hageo',
                'zacarias', 'malaquias'
            ];
            
            // Lista de libros del Nuevo Testamento en orden bíblico
            $newTestament = [
                'mateo', 'marcos', 'lucas', 'juan', 'hechos', 'romanos', '1-corintios', '2-corintios',
                'galatas', 'efesios', 'filipenses', 'colosenses', '1-tesalonicenses', '2-tesalonicenses',
                '1-timoteo', '2-timoteo', 'tito', 'filemon', 'hebreos', 'santiago', '1-pedro',
                '2-pedro', '1-juan', '2-juan', '3-juan', 'judas', 'apocalipsis'
            ];
            
            $data = $this->bible();
            $booksInfo = [];
            
            // Procesar Antiguo Testamento
            $booksInfo['old_testament'] = [
                'name' => 'Antiguo Testamento',
                'books' => []
            ];
            
            foreach ($oldTestament as $slug) {
                if (isset($data[$slug])) {
                    $booksInfo['old_testament']['books'][] = [
                        'slug' => $slug,
                        'name' => $this->pretty($slug),
                        'chapters' => count($data[$slug]),
                        'testament' => 'old',
                        'order' => array_search($slug, $oldTestament) + 1
                    ];
                }
            }
            
            // Procesar Nuevo Testamento
            $booksInfo['new_testament'] = [
                'name' => 'Nuevo Testamento',
                'books' => []
            ];
            
            foreach ($newTestament as $slug) {
                if (isset($data[$slug])) {
                    $booksInfo['new_testament']['books'][] = [
                        'slug' => $slug,
                        'name' => $this->pretty($slug),
                        'chapters' => count($data[$slug]),
                        'testament' => 'new',
                        'order' => array_search($slug, $newTestament) + 1
                    ];
                }
            }
            
            return $booksInfo;
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

    /** GET /biblia/api/libros -> [{slug, name, chapters, testament, order}] */
    public function apiBooks()
    {
        $booksInfo = $this->booksInfo();
        
        // Combinar todos los libros en una lista plana
        $allBooks = array_merge(
            $booksInfo['old_testament']['books'],
            $booksInfo['new_testament']['books']
        );
        
        return response()->json($allBooks);
    }
    
    /** GET /biblia/api/libros/organizados -> {old_testament: {name, books}, new_testament: {name, books}} */
    public function apiBooksOrganized()
    {
        $booksInfo = $this->booksInfo();
        return response()->json($booksInfo);
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
        
        // Juan 3:16 por defecto
        $libro = 'juan';
        $cap = '3';
        
        if (!isset($data[$libro]) || !isset($data[$libro][$cap])) {
            // Si Juan no está disponible, tomar el primer libro y capítulo disponibles
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

    /** GET /biblia/api/buscar?q=palabra -> matches con fragmento, estadísticas y paginación */
    public function apiSearch(Request $req)
    {
        $q = trim((string)$req->query('q', ''));
        $page = (int)$req->query('page', 1);
        $perPage = 10; // Resultados por página
        
        if ($q === '' || mb_strlen($q) < 2) {
            return response()->json([
                'q' => $q, 
                'total' => 0, 
                'results' => [],
                'stats' => null,
                'pagination' => [
                    'current_page' => 1,
                    'total_pages' => 0,
                    'total_results' => 0,
                    'per_page' => $perPage,
                    'has_prev' => false,
                    'has_next' => false,
                    'prev_page' => null,
                    'next_page' => null,
                ]
            ]);
        }

        $data = $this->bible();
        $needle = mb_strtolower($q);
        
        // Lista de libros del Antiguo y Nuevo Testamento
        $booksInfo = $this->booksInfo();
        $oldTestamentBooks = array_column($booksInfo['old_testament']['books'], 'slug');
        $newTestamentBooks = array_column($booksInfo['new_testament']['books'], 'slug');

        $allResults = [];
        $stats = [
            'total_results' => 0,
            'books_count' => 0,
            'old_testament' => 0,
            'new_testament' => 0
        ];
        
        $foundBooks = [];
        
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
                        
                        // Determinar si es AT o NT
                        $testament = in_array($libro, $oldTestamentBooks) ? 'old' : 'new';
                        
                        $allResults[] = [
                            'book'    => $libro,
                            'chapter' => (int)$cap,
                            'verse'   => (int)$n,
                            'ref'     => $this->pretty($libro)." $cap:$n",
                            'text'    => $t,
                            'highlighted' => $highlighted,
                            'snippet' => ($start > 0 ? '…' : '') . $frag . (mb_strlen($t) > $start + $len ? '…' : ''),
                            'testament' => $testament
                        ];
                        
                        // Actualizar estadísticas
                        $stats['total_results']++;
                        
                        if (!in_array($libro, $foundBooks)) {
                            $foundBooks[] = $libro;
                            $stats['books_count']++;
                            
                            if ($testament === 'old') {
                                $stats['old_testament']++;
                            } else {
                                $stats['new_testament']++;
                            }
                        }
                    }
                }
            }
        }
        
        // Ordenar resultados por relevancia (primero los que coinciden exactamente)
        usort($allResults, function($a, $b) use ($needle) {
            $aText = mb_strtolower($a['text']);
            $bText = mb_strtolower($b['text']);
            
            // Coincidencia exacta
            $aExact = mb_strpos($aText, $needle) !== false;
            $bExact = mb_strpos($bText, $needle) !== false;
            
            if ($aExact && !$bExact) return -1;
            if (!$aExact && $bExact) return 1;
            
            // Orden bíblico
            $booksInfo = $this->booksInfo();
            $allBooks = array_merge(
                $booksInfo['old_testament']['books'],
                $booksInfo['new_testament']['books']
            );
            
            $aBookIndex = array_search($a['book'], array_column($allBooks, 'slug'));
            $bBookIndex = array_search($b['book'], array_column($allBooks, 'slug'));
            
            if ($aBookIndex != $bBookIndex) {
                return $aBookIndex - $bBookIndex;
            }
            
            // Si es el mismo libro, ordenar por capítulo y versículo
            if ($a['chapter'] != $b['chapter']) {
                return $a['chapter'] - $b['chapter'];
            }
            
            return $a['verse'] - $b['verse'];
        });
        
        // Paginar resultados
        $totalResults = count($allResults);
        $totalPages = ceil($totalResults / $perPage);
        
        // Asegurar que la página solicitada sea válida
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;
        
        $results = array_slice($allResults, $offset, $perPage);
        
        return response()->json([
            'q'       => $q,
            'total'   => $totalResults,
            'results' => $results,
            'stats'   => $stats,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $totalPages,
                'total_results' => $totalResults,
                'per_page' => $perPage,
                'has_prev' => $page > 1,
                'has_next' => $page < $totalPages,
                'prev_page' => $page > 1 ? $page - 1 : null,
                'next_page' => $page < $totalPages ? $page + 1 : null,
            ]
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
        $booksInfo = $this->booksInfo();
        $allBooks = array_merge(
            $booksInfo['old_testament']['books'],
            $booksInfo['new_testament']['books']
        );
        $books = array_column($allBooks, 'slug');
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