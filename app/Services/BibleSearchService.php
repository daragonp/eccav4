<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

/**
 * Servicio mínimo SOLO para búsqueda con SQLite FTS5
 * No afecta la funcionalidad existente de capítulos/versículos
 */
class BibleSearchService
{
    protected string $dbPath;
    protected ?\PDO $pdo = null;
    
    public function __construct()
    {
        $this->dbPath = storage_path('app/bible.sqlite');
    }
    
    /**
     * Verifica si el índice de búsqueda existe
     */
    public function exists(): bool
    {
        return File::exists($this->dbPath);
    }
    
    /**
     * Obtiene conexión PDO
     */
    protected function getPdo(): \PDO
    {
        if ($this->pdo === null) {
            if (!$this->exists()) {
                throw new \Exception('Índice de búsqueda no encontrado. Ejecuta: php artisan bible:init-search');
            }
            $this->pdo = new \PDO('sqlite:' . $this->dbPath);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
        return $this->pdo;
    }
    
    /**
     * Búsqueda con SQLite FTS5
     */
    public function search(string $query, int $page = 1, int $perPage = 10): array
    {
        $query = trim($query);
        
        if (mb_strlen($query) < 2) {
            return $this->emptyResult($query, $perPage);
        }
        
        try {
            $pdo = $this->getPdo();
            
            // Preparar query para FTS5
            $ftsQuery = $this->prepareFtsQuery($query);
            
            if (empty($ftsQuery)) {
                return $this->emptyResult($query, $perPage);
            }
            
            // Contar total
            $countStmt = $pdo->prepare('SELECT COUNT(*) FROM verses WHERE verses MATCH ?');
            $countStmt->execute([$ftsQuery]);
            $total = (int)$countStmt->fetchColumn();
            
            if ($total === 0) {
                return $this->emptyResult($query, $perPage);
            }
            
            // Obtener resultados paginados
            $offset = ($page - 1) * $perPage;
            
            $sql = '
                SELECT book, chapter, verse, text,
                       snippet(verses, 3, "<mark>", "</mark>", "…", 40) as highlighted
                FROM verses
                WHERE verses MATCH ?
                ORDER BY bm25(verses)
                LIMIT ? OFFSET ?
            ';
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$ftsQuery, $perPage, $offset]);
            $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            
            // Formatear resultados
            $results = [];
            $booksFound = [];
            $oldTestament = 0;
            $newTestament = 0;
            
            $oldTestamentBooks = [
                'genesis', 'exodo', 'levitico', 'numeros', 'deuteronomio', 
                'josue', 'jueces', 'rut', '1-samuel', '2-samuel', 
                '1-reyes', '2-reyes', '1-cronicas', '2-cronicas', 'esdras',
                'nehemias', 'ester', 'job', 'salmos', 'proverbios', 
                'eclesiastes', 'cantares', 'isaias', 'jeremias', 'lamentaciones', 
                'ezequiel', 'daniel', 'oseas', 'joel', 'amos', 
                'abdias', 'jonas', 'miqueas', 'nahum', 'habacuc', 
                'sofonias', 'hageo', 'zacarias', 'malaquias'
            ];
            
            foreach ($rows as $row) {
                $book = $row['book'];
                $isOld = in_array($book, $oldTestamentBooks);
                
                $results[] = [
                    'book' => $book,
                    'chapter' => (int)$row['chapter'],
                    'verse' => (int)$row['verse'],
                    'ref' => $this->prettyName($book) . ' ' . $row['chapter'] . ':' . $row['verse'],
                    'text' => $row['text'],
                    'highlighted' => $row['highlighted'],
                    'testament' => $isOld ? 'old' : 'new'
                ];
                
                if (!in_array($book, $booksFound)) {
                    $booksFound[] = $book;
                    if ($isOld) $oldTestament++;
                    else $newTestament++;
                }
            }
            
            $totalPages = max(1, ceil($total / $perPage));
            
            return [
                'q' => $query,
                'total' => $total,
                'results' => $results,
                'stats' => [
                    'total_results' => $total,
                    'books_count' => count($booksFound),
                    'old_testament' => $oldTestament,
                    'new_testament' => $newTestament
                ],
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'total_results' => $total,
                    'per_page' => $perPage,
                    'has_prev' => $page > 1,
                    'has_next' => $page < $totalPages,
                    'prev_page' => $page > 1 ? $page - 1 : null,
                    'next_page' => $page < $totalPages ? $page + 1 : null,
                ]
            ];
            
        } catch (\Exception $e) {
            // Si falla FTS5, retornar vacío
            return $this->emptyResult($query, $perPage);
        }
    }
    
    /**
     * Prepara la query para FTS5
     */
    protected function prepareFtsQuery(string $query): string
    {
        $query = trim($query);
        
        // Detectar frase exacta entre comillas dobles
        if (preg_match('/^"(.+)"$/u', $query, $matches)) {
            // Búsqueda de frase exacta
            return '"' . $matches[1] . '"';
        }
        
        // Detectar si contiene comillas en cualquier parte
        if (preg_match('/"([^"]+)"/', $query, $matches)) {
            // Tiene una frase entre comillas dentro
            $phrase = $matches[1];
            $rest = trim(str_replace($matches[0], '', $query));
            
            if ($rest) {
                $restTerms = $this->extractTerms($rest);
                $restQuery = implode(' ', array_map(fn($t) => $t . '*', $restTerms));
                return '"' . $phrase . '" ' . $restQuery;
            }
            return '"' . $phrase . '"';
        }
        
        // Detectar operador OR
        if (preg_match('/\bOR\b/i', $query)) {
            $parts = preg_split('/\s+OR\s+/i', $query);
            $parts = array_filter(array_map('trim', $parts));
            return implode(' OR ', array_map(fn($p) => $p . '*', $parts));
        }
        
        // Búsqueda normal AND (múltiples palabras)
        $terms = $this->extractTerms($query);
        
        if (empty($terms)) {
            return '';
        }
        
        // Agregar wildcard a cada término
        return implode(' ', array_map(fn($t) => $t . '*', $terms));
    }
    
    /**
     * Extrae términos significativos
     */
    protected function extractTerms(string $query): array
    {
        $stopWords = [
            'y', 'o', 'de', 'la', 'el', 'en', 'que', 'los', 'las', 'un', 'una',
            'es', 'al', 'del', 'se', 'por', 'con', 'para', 'como', 'no', 'su',
            'sus', 'le', 'lo', 'me', 'te', 'si', 'mas', 'ya', 'fue', 'son', 'ser',
            'era', 'han', 'hay', 'has', 'hasta', 'desde', 'cuando', 'donde', 'quien'
        ];
        
        $words = preg_split('/\s+/u', mb_strtolower(trim($query)));
        
        $terms = [];
        foreach ($words as $word) {
            $word = trim($word, '.,;:!?¿¡"\'');
            if (mb_strlen($word) >= 2 && !in_array($word, $stopWords) && !preg_match('/^\d+$/', $word)) {
                $terms[] = $word;
            }
        }
        
        return array_unique($terms);
    }
    
    /**
     * Nombre formateado del libro
     */
    protected function prettyName(string $slug): string
    {
        $names = [
            'genesis' => 'Génesis', 'exodo' => 'Éxodo', 'levitico' => 'Levítico',
            'numeros' => 'Números', 'deuteronomio' => 'Deuteronomio', 'josue' => 'Josué',
            'jueces' => 'Jueces', 'rut' => 'Rut', '1-samuel' => '1 Samuel', '2-samuel' => '2 Samuel',
            '1-reyes' => '1 Reyes', '2-reyes' => '2 Reyes', '1-cronicas' => '1 Crónicas',
            '2-cronicas' => '2 Crónicas', 'esdras' => 'Esdras', 'nehemias' => 'Nehemías',
            'ester' => 'Ester', 'job' => 'Job', 'salmos' => 'Salmos', 'proverbios' => 'Proverbios',
            'eclesiastes' => 'Eclesiastés', 'cantares' => 'Cantares', 'isaias' => 'Isaías',
            'jeremias' => 'Jeremías', 'lamentaciones' => 'Lamentaciones', 'ezequiel' => 'Ezequiel',
            'daniel' => 'Daniel', 'oseas' => 'Oseas', 'joel' => 'Joel', 'amos' => 'Amós',
            'abdias' => 'Abdías', 'jonas' => 'Jonás', 'miqueas' => 'Miqueas', 'nahum' => 'Nahum',
            'habacuc' => 'Habacuc', 'sofonias' => 'Sofonías', 'hageo' => 'Hageo',
            'zacarias' => 'Zacarías', 'malaquias' => 'Malaquías',
            'mateo' => 'Mateo', 'marcos' => 'Marcos', 'lucas' => 'Lucas', 'juan' => 'Juan',
            'hechos' => 'Hechos', 'romanos' => 'Romanos', '1-corintios' => '1 Corintios',
            '2-corintios' => '2 Corintios', 'galatas' => 'Gálatas', 'efesios' => 'Efesios',
            'filipenses' => 'Filipenses', 'colosenses' => 'Colosenses',
            '1-tesalonicenses' => '1 Tesalonicenses', '2-tesalonicenses' => '2 Tesalonicenses',
            '1-timoteo' => '1 Timoteo', '2-timoteo' => '2 Timoteo', 'tito' => 'Tito',
            'filemon' => 'Filemón', 'hebreos' => 'Hebreos', 'santiago' => 'Santiago',
            '1-pedro' => '1 Pedro', '2-pedro' => '2 Pedro', '1-juan' => '1 Juan',
            '2-juan' => '2 Juan', '3-juan' => '3 Juan', 'judas' => 'Judas', 
            'apocalipsis' => 'Apocalipsis'
        ];
        
        return $names[$slug] ?? ucfirst(str_replace('-', ' ', $slug));
    }
    
    /**
     * Resultado vacío
     */
    protected function emptyResult(string $query, int $perPage): array
    {
        return [
            'q' => $query,
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
        ];
    }
}
