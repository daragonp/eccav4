<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración.
     */
    public function up(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            // Cita combinada compacta: "Juan 3:16; Salmos 23:1". Nullable: versículos
            // manuales o filas históricas quedan en NULL (sin backfill).
            $table->string('reference', 500)->nullable()->after('text');
            // Lista estructurada [{book,chapter,verse,pretty,text}] en orden de presentación.
            // MySQL soporta columna json nativa. Nullable por la misma razón.
            $table->json('verses_refs')->nullable()->after('reference');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            $table->dropColumn(['verses_refs', 'reference']);
        });
    }
};
