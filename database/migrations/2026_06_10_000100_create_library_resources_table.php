<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración: crea la tabla de recursos de la Biblioteca.
     */
    public function up(): void
    {
        Schema::create('library_resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();                  // único, generado del título
            $table->text('description')->nullable();
            $table->string('type', 10);                        // 'libro' | 'video' | 'audio'
            $table->foreignId('category_id')
                  ->nullable()
                  ->constrained('library_categories')
                  ->nullOnDelete();                            // si se borra la categoría, queda null
            $table->string('author')->nullable();
            $table->string('cover')->nullable();               // nombre de archivo de la portada
            $table->string('file')->nullable();                // nombre de archivo del medio subido
            $table->string('external_url')->nullable();        // URL externa (enlace) del medio
            $table->boolean('published')->default(true);       // publicado/despublicado (NO 'active')
            $table->date('published_at')->nullable();          // fecha de publicación visible
            $table->timestamps();
            $table->softDeletes();                             // deleted_at

            $table->index('type');
            $table->index('category_id');
            $table->index(['published', 'deleted_at']);        // consultas públicas (cubre 'published' como prefijo)
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('library_resources');
    }
};
