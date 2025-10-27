<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            // Cambiar deleted_at de date a timestamp (soft deletes)
            if (Schema::hasColumn('schedules', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
            $table->softDeletes();
            
            // Agregar emission_key si no existe (para agrupar programas repetidos)
            if (!Schema::hasColumn('schedules', 'emission_key')) {
                $table->string('emission_key', 50)->nullable()->after('image');
                $table->index('emission_key');
            }
            
            // Agregar índices para mejorar el rendimiento
            $table->index(['day', 'start', 'end']);
            $table->index('start');
            $table->index('end');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('emission_key');
            $table->dropIndex(['day', 'start', 'end']);
            $table->dropIndex(['start']);
            $table->dropIndex(['end']);
        });
    }
};
