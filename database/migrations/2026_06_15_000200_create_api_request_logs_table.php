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
        Schema::create('api_request_logs', function (Blueprint $table) {
            $table->id();
            // Nullable: una petición no autorizada no tiene cliente asociado.
            $table->foreignId('api_client_id')->nullable()->constrained('api_clients')->nullOnDelete();
            $table->string('endpoint', 190);             // Ruta solicitada (p. ej. api/v1/verse/today)
            $table->string('method', 10);                // Verbo HTTP
            $table->string('ip', 45);                    // IPv4/IPv6
            $table->string('user_agent', 255)->nullable();
            $table->unsignedSmallInteger('status_code'); // Código HTTP resultante
            $table->boolean('authorized')->default(false); // true si autenticó correctamente
            $table->timestamp('created_at')->nullable(); // Log inmutable: solo created_at

            $table->index('api_client_id');
            $table->index('created_at');
            $table->index(['authorized', 'created_at']);
        });
    }

    /**
     * Revierte la migración.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_request_logs');
    }
};
