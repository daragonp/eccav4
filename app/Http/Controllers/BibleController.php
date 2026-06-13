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
    /** GET /biblia/api/inicio -> { book, chapter, verses:[{n, t}], pretty } */
public function apiStart()
{
    $data = $this->bible();
    
    // Obtener un libro y capítulo aleatorio
    $books = array_keys($data);
    $randomBook = $books[array_rand($books)];
    $chapters = array_keys($data[$randomBook]);
    $randomChapter = $chapters[array_rand($chapters)];
    
    // Verificar que exista el libro y capítulo seleccionado
    if (!isset($data[$randomBook]) || !isset($data[$randomBook][$randomChapter])) {
        // Si hay un problema, usar Juan 3:16 como fallback
        $randomBook = 'juan';
        $randomChapter = '3';
    }
    
    $versOk = [];
    $count = 0;
    foreach ($data[$randomBook][$randomChapter] as $n => $t) {
        if ($count >= 10) break;
        $versOk[] = ['n' => (int)$n, 't' => $t];
        $count++;
    }

    return response()->json([
        'book'   => $randomBook,
        'chapter'=> (int)$randomChapter,
        'pretty' => $this->pretty($randomBook) . ' ' . $randomChapter,
        'verses' => $versOk,
        'is_start' => true,
    ]);
}

    /** GET /biblia/api/buscar?q=palabra -> matches con fragmento, estadísticas y paginación */
    public function apiSearch(Request $req)
    {
        $q = trim((string)$req->query('q', ''));
        $page = (int)$req->query('page', 1);
        $perPage = 10;
        
        $exactMatch = null;
        
        // ========== NUEVA LÓGICA: Búsqueda exacta de referencia bíblica ==========
        if ($q !== '') {
            // Regex para detectar referencias tipo: "Juan 3:16", "1 Juan 5:7", "Genesis 1", "1 Corintios 13"
            if (preg_match('/^(?:(\d+)\s+)?([\p{L}\s\-\.]+?)\s+(\d+)(?:\s*:\s*(\d+))?$/ui', $q, $refMatches)) {
                $numPrefix = !empty($refMatches[1]) ? trim($refMatches[1]) : '';
                $bookName = !empty($refMatches[2]) ? trim($refMatches[2]) : '';
                $chapter = !empty($refMatches[3]) ? trim($refMatches[3]) : '';
                $verse = !empty($refMatches[4]) ? trim($refMatches[4]) : null;
                
                $fullBookName = $numPrefix ? "$numPrefix $bookName" : $bookName;
                $book = $this->findBookByName($fullBookName);
                
                if ($book) {
                    $data = $this->bible();
                    $bookSlug = $book['slug'];
                    
                    if (isset($data[$bookSlug][$chapter])) {
                        if ($verse !== null) {
                            if (isset($data[$bookSlug][$chapter][$verse])) {
                                $exactMatch = [
                                    'book'    => $bookSlug,
                                    'chapter' => (int)$chapter,
                                    'verse'   => (int)$verse,
                                    'pretty'  => $book['name'] . " $chapter:$verse",
                                    'text'    => $data[$bookSlug][$chapter][$verse]
                                ];
                            }
                        } else {
                            // Si solo se especificó libro y capítulo
                            $exactMatch = [
                                'book'    => $bookSlug,
                                'chapter' => (int)$chapter,
                                'verse'   => null,
                                'pretty'  => $book['name'] . " $chapter",
                                'text'    => "Capítulo completo. Haz clic para leer."
                            ];
                        }
                    }
                }
            }
        }
        
        if ($q === '' || mb_strlen($q) < 2) {
            return response()->json([
                'q' => $q, 
                'total' => 0, 
                'results' => [],
                'stats' => null,
                'exact_match' => $exactMatch,
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
        
        // ========== NUEVA LÓGICA: Extraer términos de búsqueda con soporte de comillas ==========
        $searchTerms = $this->extractSearchTerms($q);
        
        if (empty($searchTerms)) {
            return response()->json([
                'q' => $q, 
                'total' => 0, 
                'results' => [],
                'stats' => null,
                'exact_match' => $exactMatch,
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

        // Lista de libros del Antiguo y Nuevo Testamento
        $booksInfo = $this->booksInfo();
        $oldTestamentBooks = array_column($booksInfo['old_testament']['books'], 'slug');

        // Preparar términos normalizados y patrones regex antes del bucle para máximo rendimiento
        $normalizedTerms = [];
        $patterns = [];
        foreach ($searchTerms as $term) {
            $normalizedTerms[] = $this->removeAccents($term);
            $patterns[] = $this->getAccentInsensitivePattern($term);
        }

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
                    $tLowerNormalized = $this->removeAccents(mb_strtolower($t));
                    
                    // Verificar TODAS las palabras usando la optimización str_contains + regex
                    $matchCount = 0;
                    foreach ($normalizedTerms as $idx => $normTerm) {
                        if (str_contains($tLowerNormalized, $normTerm)) {
                            if (preg_match($patterns[$idx], $t)) {
                                $matchCount++;
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                    
                    // Solo incluir si coinciden TODOS los términos
                    if ($matchCount === count($searchTerms)) {
                        // Generar fragmento (snippet) usando el primer término
                        $firstTerm = $searchTerms[0];
                        $pos = mb_strpos($tLowerNormalized, $this->removeAccents($firstTerm));
                        $pos = ($pos === false) ? 0 : $pos;
                        $start = max(0, $pos - 40);
                        $len = 80 + mb_strlen($firstTerm);
                        $frag = mb_substr($t, $start, $len);
                        
                        // Resaltar términos en el texto original preservando mayúsculas y acentos
                        $highlighted = $t;
                        foreach ($patterns as $pattern) {
                            $highlighted = preg_replace($pattern, '<mark>$0</mark>', $highlighted);
                        }
                        
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
                            'testament' => $testament,
                            'match_count' => $matchCount
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
        
        // Ordenar resultados por orden bíblico
        $bookOrder = [];
        foreach ($booksInfo['old_testament']['books'] as $b) $bookOrder[] = $b['slug'];
        foreach ($booksInfo['new_testament']['books'] as $b) $bookOrder[] = $b['slug'];
        
        usort($allResults, function($a, $b) use ($bookOrder) {
            $aIdx = array_search($a['book'], $bookOrder);
            $bIdx = array_search($b['book'], $bookOrder);
            if ($aIdx !== $bIdx) return $aIdx - $bIdx;
            if ($a['chapter'] !== $b['chapter']) return $a['chapter'] - $b['chapter'];
            return $a['verse'] - $b['verse'];
        });
        
        // Paginar resultados
        $totalResults = count($allResults);
        $totalPages = max(1, ceil($totalResults / $perPage));
        $page = max(1, min($page, $totalPages));
        $offset = ($page - 1) * $perPage;
        
        $results = array_slice($allResults, $offset, $perPage);
        
        // Limpiar campo interno
        $results = array_map(function($r) {
            unset($r['match_count']);
            return $r;
        }, $results);
        
        return response()->json([
            'q'       => $q,
            'total'   => $totalResults,
            'results' => $results,
            'stats'   => $stats,
            'exact_match' => $exactMatch,
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

    /**
     * Extrae términos de búsqueda soportando frases exactas entre comillas.
     */
    protected function extractSearchTerms(string $query): array
    {
        $query = trim($query);
        if ($query === '') {
            return [];
        }
        
        // Buscar frases entre comillas dobles o simples, o palabras sueltas
        preg_match_all('/"([^"]+)"|\'([^\']+)\'|(\S+)/', $query, $matches);
        
        $terms = [];
        for ($i = 0; $i < count($matches[0]); $i++) {
            $term = '';
            if ($matches[1][$i] !== '') {
                $term = trim($matches[1][$i]);
            } elseif ($matches[2][$i] !== '') {
                $term = trim($matches[2][$i]);
            } else {
                $term = trim($matches[3][$i]);
            }
            
            if ($term !== '') {
                $terms[] = mb_strtolower($term);
            }
        }
        
        // Stop words en español
        $stopWords = [
            'y', 'o', 'de', 'la', 'el', 'en', 'que', 'los', 'las', 'un', 'una',
            'es', 'al', 'del', 'se', 'por', 'con', 'para', 'como', 'no', 'su',
            'sus', 'le', 'lo', 'me', 'te', 'si', 'más', 'ya', 'fue', 'son', 'ser'
        ];
        
        $filteredTerms = [];
        foreach ($terms as $term) {
            if (str_contains($term, ' ')) {
                $filteredTerms[] = $term;
            } else {
                if (mb_strlen($term) >= 2 && !in_array($term, $stopWords) && !preg_match('/^\d+$/', $term)) {
                    $filteredTerms[] = $term;
                }
            }
        }
        
        return array_unique($filteredTerms);
    }

    /**
     * Exporta toda la Biblia en JSON para la base de datos sin conexión.
     */
    public function apiExport()
    {
        return response()->json($this->bible());
    }

    /**
     * Busca un libro por nombre o slug de manera flexible y normalizada.
     */
    protected function findBookByName(string $name): ?array
    {
        $nameClean = $this->normalizeString($name);
        $booksInfo = $this->booksInfo();
        $allBooks = array_merge(
            $booksInfo['old_testament']['books'],
            $booksInfo['new_testament']['books']
        );
        
        foreach ($allBooks as $book) {
            $bookNameClean = $this->normalizeString($book['name']);
            $bookSlugClean = $this->normalizeString($book['slug']);
            if ($bookNameClean === $nameClean || $bookSlugClean === $nameClean) {
                return $book;
            }
        }
        
        // Búsqueda aproximada de prefijo
        foreach ($allBooks as $book) {
            $bookNameClean = $this->normalizeString($book['name']);
            if (str_starts_with($bookNameClean, $nameClean)) {
                return $book;
            }
        }
        
        return null;
    }

    /**
     * Normaliza un texto para comparaciones flexibles.
     */
    protected function normalizeString(string $str): string
    {
        $str = mb_strtolower(trim($str));
        $unwanted = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u',
            'à'=>'a', 'è'=>'e', 'ì'=>'i', 'ò'=>'o', 'ù'=>'u',
            'ä'=>'a', 'ë'=>'e', 'ï'=>'i', 'ö'=>'o', 'ü'=>'u',
            'ñ'=>'n'
        ];
        $str = strtr($str, $unwanted);
        $str = preg_replace('/[^a-z0-9]/', '', $str);
        return $str;
    }

    /**
     * Remueve acentos y tildes para búsqueda acento-insensitiva.
     */
    protected function removeAccents(string $str): string
    {
        $unwanted = [
            'á'=>'a', 'é'=>'e', 'í'=>'i', 'ó'=>'o', 'ú'=>'u',
            'à'=>'a', 'è'=>'e', 'ì'=>'i', 'ò'=>'o', 'ù'=>'u',
            'ä'=>'a', 'ë'=>'e', 'ï'=>'i', 'ö'=>'o', 'ü'=>'u',
            'Á'=>'a', 'É'=>'e', 'Í'=>'i', 'Ó'=>'o', 'Ú'=>'u',
            'À'=>'a', 'È'=>'e', 'Í'=>'i', 'Ó'=>'o', 'Ú'=>'u',
            'Ä'=>'a', 'Ë'=>'e', 'Ï'=>'i', 'Ö'=>'o', 'Ü'=>'u',
            'ñ'=>'n', 'Ñ'=>'n'
        ];
        return strtr($str, $unwanted);
    }

    /**
     * Genera un patrón regex Unicode acento-insensitivo para un término.
     */
    protected function getAccentInsensitivePattern(string $term): string
    {
        $normalized = $this->removeAccents(mb_strtolower($term));
        $escaped = preg_quote($normalized, '/');
        
        $map = [
            'a' => '[aáàäâ]',
            'e' => '[eéèëê]',
            'i' => '[iíìïî]',
            'o' => '[oóòöô]',
            'u' => '[uúùüû]',
            'n' => '[nñ]',
        ];
        
        $pattern = '';
        $len = mb_strlen($escaped);
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($escaped, $i, 1);
            if (isset($map[$char])) {
                $pattern .= $map[$char];
            } else {
                $pattern .= $char;
            }
        }
        
        return '/(?<!\p{L})' . $pattern . '(?!\p{L})/ui';
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