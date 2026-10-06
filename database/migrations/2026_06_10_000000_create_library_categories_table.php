<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla de categorías de la Biblioteca.
     */
    public function up(): void
    {
        Schema::create('library_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');                 // "Teología", "Alabanza", etc.
            $table->string('slug')->unique();       // slug único generado del nombre
            $table->text('description')->nullable();
            $table->timestamps();
            $table->softDeletes();                  // deleted_at nullable
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_categories');
    }
};
