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
            // Nullable a nivel de BD para no romper las filas existentes que no tienen texto.
            // La obligatoriedad se impone en la capa de aplicación (validación en VerseController).
            $table->text('text')->nullable()->after('date');
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::table('verses', function (Blueprint $table) {
            $table->dropColumn('text');
        });
    }
};
