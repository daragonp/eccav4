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
        if (!Schema::hasColumn('worships', 'ai_summary')) {
            Schema::table('worships', function (Blueprint $table) {
                $table->text('ai_summary')->nullable()->comment('Resumen generado por IA del audio');
            });
        }

        if (!Schema::hasColumn('worships', 'ai_image')) {
            Schema::table('worships', function (Blueprint $table) {
                $table->string('ai_image')->nullable()->comment('Imagen generada por IA basada en el contenido del audio');
            });
        }

        if (!Schema::hasColumn('worships', 'ai_processed')) {
            Schema::table('worships', function (Blueprint $table) {
                $table->boolean('ai_processed')->default(false)->comment('Indica si el audio ha sido procesado por IA');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('worships', 'ai_summary')) {
            Schema::table('worships', function (Blueprint $table) {
                $table->dropColumn('ai_summary');
            });
        }

        if (Schema::hasColumn('worships', 'ai_image')) {
            Schema::table('worships', function (Blueprint $table) {
                $table->dropColumn('ai_image');
            });
        }

        if (Schema::hasColumn('worships', 'ai_processed')) {
            Schema::table('worships', function (Blueprint $table) {
                $table->dropColumn('ai_processed');
            });
        }
    }
};
