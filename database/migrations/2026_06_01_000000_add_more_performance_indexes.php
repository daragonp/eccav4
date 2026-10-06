<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Índice en news(slug): se usa para resolver URLs públicas por slug.
        if (Schema::hasTable('news') && Schema::hasColumn('news', 'slug')) {
            $this->addIndexIfMissing('news', ['slug']);
        }

        // Índices en worships para búsquedas/orden por slug y broadcast.
        if (Schema::hasTable('worships')) {
            if (Schema::hasColumn('worships', 'slug')) {
                $this->addIndexIfMissing('worships', ['slug']);
            }
            if (Schema::hasColumn('worships', 'broadcast')) {
                $this->addIndexIfMissing('worships', ['broadcast']);
            }
        }

        // Índice FULLTEXT en news(title, abstract) para acelerar búsquedas de texto.
        if (
            Schema::hasTable('news')
            && Schema::hasColumn('news', 'title')
            && Schema::hasColumn('news', 'abstract')
            && !$this->indexExists('news', 'news_title_abstract_fulltext')
        ) {
            DB::statement('CREATE FULLTEXT INDEX news_title_abstract_fulltext ON news(title, abstract)');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('news') && Schema::hasColumn('news', 'slug')) {
            Schema::table('news', function (Blueprint $table) {
                $table->dropIndex('news_slug_index');
            });
        }

        if (Schema::hasTable('worships')) {
            Schema::table('worships', function (Blueprint $table) {
                if (Schema::hasColumn('worships', 'slug')) {
                    $table->dropIndex('worships_slug_index');
                }
                if (Schema::hasColumn('worships', 'broadcast')) {
                    $table->dropIndex('worships_broadcast_index');
                }
            });
        }

        if (Schema::hasTable('news') && $this->indexExists('news', 'news_title_abstract_fulltext')) {
            DB::statement('DROP INDEX news_title_abstract_fulltext ON news');
        }
    }

    /**
     * Agrega un índice solo si no existe ya (idempotente).
     */
    private function addIndexIfMissing(string $table, array $columns): void
    {
        $indexName = $this->indexName($table, $columns);

        if (!$this->indexExists($table, $indexName)) {
            Schema::table($table, fn (Blueprint $t) => $t->index($columns, $indexName));
        }
    }

    /**
     * Comprueba si un índice existe consultando information_schema.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $result = DB::select(
                "SELECT COUNT(*) AS cnt FROM information_schema.statistics
                 WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?",
                [$table, $indexName]
            );

            return ((int) ($result[0]->cnt ?? 0)) > 0;
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * Calcula el nombre que MySQL asigna a un índice simple/compuesto.
     */
    private function indexName(string $table, array $columns): string
    {
        return $table . '_' . implode('_', $columns) . '_index';
    }
};
