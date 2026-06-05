<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ── verses: orderByDesc('date') en dashboard ──────────────
        Schema::table('verses', function (Blueprint $table) {
            $table->index('date');
        });

        // ── news: orderByDesc('created_at') en dashboard ──────────
        Schema::table('news', function (Blueprint $table) {
            $table->index('created_at');
        });

        // ── schedules: consulta "programa actual" (day + rango horario + soft-delete) ──
        Schema::table('schedules', function (Blueprint $table) {
            $table->index(['day', 'deleted_at']);
            $table->index(['start', 'end']);
        });

        // ── worships: orderByDesc o filtros por fecha ─────────────
        if (Schema::hasTable('worships')) {
            Schema::table('worships', function (Blueprint $table) {
                if (Schema::hasColumn('worships', 'date')) {
                    $table->index('date');
                }
                if (Schema::hasColumn('worships', 'created_at')) {
                    $table->index('created_at');
                }
                if (Schema::hasColumn('worships', 'deleted_at')) {
                    $table->index('deleted_at');
                }
            });
        }

        // ── users: búsqueda por role_id en access-control ─────────
        if (Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('role_id');
            });
        }

        // ── banners: count en dashboard ────────────────────────────
        if (Schema::hasTable('banners')) {
            Schema::table('banners', function (Blueprint $table) {
                if (Schema::hasColumn('banners', 'created_at')) {
                    $table->index('created_at');
                }
            });
        }

        // ── schedule_overrides: filtro por fecha (idempotente) ────
        if (Schema::hasTable('schedule_overrides')) {
            if (Schema::hasColumn('schedule_overrides', 'date') && !$this->indexExists('schedule_overrides', 'schedule_overrides_date_index')) {
                Schema::table('schedule_overrides', fn (Blueprint $t) => $t->index('date'));
            }
            if (Schema::hasColumn('schedule_overrides', 'schedule_id') && !$this->indexExists('schedule_overrides', 'schedule_overrides_schedule_id_index')) {
                Schema::table('schedule_overrides', fn (Blueprint $t) => $t->index('schedule_id'));
            }
        }
    }

    /**
     * Verifica si un índice ya existe en una tabla.
     */
    private function indexExists(string $table, string $indexName): bool
    {
        try {
            $result = \DB::select(
                'SELECT COUNT(*) as cnt FROM information_schema.statistics ' .
                'WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ?',
                [$table, $indexName]
            );
            return ($result[0]->cnt ?? 0) > 0;
        } catch (\Throwable) {
            return false;
        }
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
