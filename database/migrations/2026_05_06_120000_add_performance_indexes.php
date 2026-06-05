<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->addIndexIfMissing('verses', ['date']);
        $this->addIndexIfMissing('news', ['created_at']);
        $this->addIndexIfMissing('schedules', ['day', 'deleted_at']);
        $this->addIndexIfMissing('schedules', ['start', 'end']);

        if (Schema::hasTable('worships')) {
            foreach (['date', 'created_at', 'deleted_at'] as $col) {
                if (Schema::hasColumn('worships', $col)) {
                    $this->addIndexIfMissing('worships', [$col]);
                }
            }
        }

        if (Schema::hasColumn('users', 'role_id')) {
            $this->addIndexIfMissing('users', ['role_id']);
        }

        if (Schema::hasTable('banners') && Schema::hasColumn('banners', 'created_at')) {
            $this->addIndexIfMissing('banners', ['created_at']);
        }

        if (Schema::hasTable('schedule_overrides')) {
            if (Schema::hasColumn('schedule_overrides', 'date')) {
                $this->addIndexIfMissing('schedule_overrides', ['date']);
            }
            if (Schema::hasColumn('schedule_overrides', 'schedule_id')) {
                $this->addIndexIfMissing('schedule_overrides', ['schedule_id']);
            }
        }
    }

    /**
     * Agrega un índice solo si no existe ya (idempotente).
     */
    private function addIndexIfMissing(string $table, array $columns): void
    {
        $indexName = $this->indexName($table, $columns);
        $exists = false;
        try {
            $result = DB::select(
                "SELECT COUNT(*) AS cnt FROM information_schema.statistics
                 WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?",
                [$table, $indexName]
            );
            $exists = ((int) ($result[0]->cnt ?? 0)) > 0;
        } catch (\Throwable) {}

        if (!$exists) {
            Schema::table($table, fn (Blueprint $t) => $t->index($columns, $indexName));
        }
    }

    /**
     * Calcula el nombre que MySQL asigna a un índice simple/compuesto.
     */
    private function indexName(string $table, array $columns): string
    {
        return $table . '_' . implode('_', $columns) . '_index';
    }

    public function down(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            $table->dropIndex(['date']);
        });

        Schema::table('news', function (Blueprint $table) {
            $table->dropIndex(['created_at']);
        });

        Schema::table('schedules', function (Blueprint $table) {
            $table->dropIndex(['day', 'deleted_at']);
            $table->dropIndex(['start', 'end']);
        });

        if (Schema::hasTable('worships')) {
            Schema::table('worships', function (Blueprint $table) {
                if (Schema::hasColumn('worships', 'date')) {
                    $table->dropIndex(['date']);
                }
                if (Schema::hasColumn('worships', 'created_at')) {
                    $table->dropIndex(['created_at']);
                }
                if (Schema::hasColumn('worships', 'deleted_at')) {
                    $table->dropIndex(['deleted_at']);
                }
            });
        }

        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['role_id']);
            });
        }

        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table) {
                if (Schema::hasColumn('banners', 'created_at')) {
                    $table->dropIndex(['created_at']);
                }
            });
        }

        if (Schema::hasTable('schedule_overrides')) {
            Schema::table('schedule_overrides', function (Blueprint $table) {
                if (Schema::hasColumn('schedule_overrides', 'date')) {
                    $table->dropIndex(['date']);
                }
                if (Schema::hasColumn('schedule_overrides', 'schedule_id')) {
                    $table->dropIndex(['schedule_id']);
                }
            });
        }
    }
};
