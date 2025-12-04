<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('worships', function (Blueprint $table) {
            $table->text('ai_summary')->nullable()->comment('Resumen generado por IA del audio');
            $table->string('ai_image')->nullable()->comment('Imagen generada por IA basada en el contenido del audio');
            $table->boolean('ai_processed')->default(false)->comment('Indica si el audio ha sido procesado por IA');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('worships', function (Blueprint $table) {
            $table->dropColumn(['ai_summary', 'ai_image', 'ai_processed']);
        });
    }
};