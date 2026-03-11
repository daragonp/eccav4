<?php
/**
 * SCRIPT DE DIAGNÓSTICO PARA bible.sqlite
 * 
 * Ejecuta este script para verificar que tu base de datos SQLite
 * tiene la estructura correcta para las búsquedas.
 * 
 * Uso: php diagnose_sqlite.php
 */

$dbPath = __DIR__ . '/storage/app/bible.sqlite';

echo "=== DIAGNÓSTICO DE BIBLE.SQLITE ===\n\n";

// 1. Verificar existencia
echo "1. Verificando archivo...\n";
if (!file_exists($dbPath)) {
    echo "   ❌ ERROR: No existe el archivo: {$dbPath}\n";
    echo "   Debes generar la base de datos primero.\n";
    exit(1);
}
echo "   ✅ Archivo existe\n";
echo "   Tamaño: " . round(filesize($dbPath) / 1024 / 1024, 2) . " MB\n\n";

// 2. Conectar
echo "2. Conectando a SQLite...\n";
try {
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "   ✅ Conexión exitosa\n\n";
} catch (Exception $e) {
    echo "   ❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Listar tablas
echo "3. Tablas en la base de datos:\n";
$tables = $pdo->query(
    "SELECT name, type FROM sqlite_master WHERE type IN ('table', 'virtual table') ORDER BY name"
)->fetchAll(PDO::FETCH_ASSOC);

foreach ($tables as $table) {
    $type = $table['type'] === 'virtual table' ? ' (FTS/Virtual)' : '';
    echo "   - {$table['name']}{$type}\n";
}
echo "\n";

// 4. Verificar estructura de cada tabla
echo "4. Estructura de tablas:\n";
foreach ($tables as $table) {
    $tableName = $table['name'];
    echo "\n   === {$tableName} ===\n";
    
    // Obtener SQL de creación
    $stmt = $pdo->query("SELECT sql FROM sqlite_master WHERE name = '{$tableName}'");
    $sql = $stmt->fetchColumn();
    echo "   SQL: " . substr($sql, 0, 100) . "...\n";
    
    // Contar registros
    try {
        $count = $pdo->query("SELECT COUNT(*) FROM {$tableName}")->fetchColumn();
        echo "   Registros: " . number_format($count) . "\n";
    } catch (Exception $e) {
        echo "   Error contando: " . $e->getMessage() . "\n";
    }
}

// 5. Verificar columnas de tabla principal
echo "\n5. Columnas de tabla 'verses' (o similar):\n";
$versesTable = null;
foreach ($tables as $t) {
    if (strpos($t['name'], 'verses') !== false && $t['type'] !== 'virtual table') {
        $versesTable = $t['name'];
        break;
    }
}

if ($versesTable) {
    $columns = $pdo->query("PRAGMA table_info({$versesTable})")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($columns as $col) {
        echo "   - {$col['name']} ({$col['type']})\n";
    }
    
    // Mostrar muestra de datos
    echo "\n   Muestra de datos:\n";
    $sample = $pdo->query("SELECT * FROM {$versesTable} LIMIT 2")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($sample as $row) {
        echo "   " . json_encode($row, JSON_UNESCAPED_UNICODE) . "\n";
    }
}

// 6. Probar búsqueda FTS
echo "\n6. Probando búsqueda FTS:\n";

// Detectar tabla FTS
$ftsTable = null;
foreach ($tables as $t) {
    if (strpos($t['name'], 'fts') !== false || $t['type'] === 'virtual table') {
        $ftsTable = $t['name'];
        break;
    }
}

if ($ftsTable) {
    echo "   Usando tabla FTS: {$ftsTable}\n";
    
    // Probar búsqueda simple
    $testQueries = ['dios', 'amor', 'jesus'];
    
    foreach ($testQueries as $q) {
        try {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$ftsTable} WHERE {$ftsTable} MATCH ?");
            $stmt->execute([$q . '*']);
            $count = $stmt->fetchColumn();
            echo "   '{$q}': {$count} resultados\n";
        } catch (Exception $e) {
            echo "   '{$q}': ERROR - " . $e->getMessage() . "\n";
        }
    }
    
    // Probar búsqueda con bm25
    echo "\n   Probando ranking BM25:\n";
    try {
        $sql = "
            SELECT book, chapter, verse, text, bm25({$ftsTable}) as score
            FROM {$ftsTable}
            WHERE {$ftsTable} MATCH 'dios*'
            ORDER BY bm25({$ftsTable})
            LIMIT 3
        ";
        $results = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($results as $r) {
            echo "   - {$r['book']} {$r['chapter']}:{$r['verse']} (score: {$r['score']})\n";
            echo "     " . substr($r['text'], 0, 60) . "...\n";
        }
        echo "   ✅ BM25 funciona correctamente\n";
    } catch (Exception $e) {
        echo "   ❌ Error con BM25: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "   ⚠️ No se encontró tabla FTS. La búsqueda será con LIKE.\n";
    
    if ($versesTable) {
        echo "   Probando búsqueda LIKE:\n";
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$versesTable} WHERE text LIKE ?");
        $stmt->execute(['%dios%']);
        echo "   'dios': " . $stmt->fetchColumn() . " resultados\n";
    }
}

// 7. Recomendaciones
echo "\n=== RECOMENDACIONES ===\n";

if (!$ftsTable) {
    echo "⚠️ No hay tabla FTS. Para mejorar las búsquedas, crea un índice FTS5:\n";
    echo "
    CREATE VIRTUAL TABLE verses_fts USING fts5(
        book,
        chapter UNINDEXED,
        verse UNINDEXED,
        text,
        tokenize='unicode61'
    );
    
    INSERT INTO verses_fts SELECT book, chapter, verse, text FROM verses;
    \n";
} else {
    echo "✅ La base de datos tiene FTS configurado correctamente.\n";
}

echo "\n=== FIN DEL DIAGNÓSTICO ===\n";
