<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Elimina la columna `active` de la tabla `live_streams` ya que la lógica
     * de visibilidad se controla exclusivamente mediante `mode` (auto, forzar_vivo, forzar_oculto).
     */
    public function up(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            if (Schema::hasColumn('live_streams', 'active')) {
                $table->dropColumn('active');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('live_streams', function (Blueprint $table) {
            if (! Schema::hasColumn('live_streams', 'active')) {
                $table->boolean('active')->default(true)->after('title');
            }
        });
    }
};

