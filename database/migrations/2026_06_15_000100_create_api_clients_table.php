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
        Schema::create('api_clients', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);                 // Nombre del servicio o emisora
            $table->string('token', 64)->unique();       // Hash sha256 del token (64 hex). Nunca el token en claro.
            $table->string('token_prefix', 16);          // Primeros caracteres del token para identificarlo en la UI
            $table->boolean('active')->default(true);    // Habilita o revoca el cliente
            $table->timestamp('last_used_at')->nullable(); // Última autenticación exitosa
            $table->timestamps();
            $table->softDeletes();

            $table->index(['active', 'deleted_at']);      // Búsqueda de autenticación
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_clients');
    }
};
