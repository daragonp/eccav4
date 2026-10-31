<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabla singleton de configuración de la transmisión "Culto en Vivo".
     * Una sola fila gestiona plataformas, ventana horaria, modo de visibilidad
     * y archivado de la grabación. El acceso de conveniencia vive en
     * App\Models\LiveStream::current().
     *
     * @return void
     */
    public function up()
    {
        Schema::create('live_streams', function (Blueprint $table) {
            $table->id();
            $table->string('platform', 20)->default('youtube');     // youtube | facebook
            $table->string('youtube_channel_id', 64)->nullable();
            $table->string('youtube_url', 500)->nullable();
            $table->string('facebook_url', 500)->nullable();
            $table->string('tiktok_url', 500)->nullable();
            $table->string('x_url', 500)->nullable();
            $table->string('instagram_url', 500)->nullable();
            $table->unsignedTinyInteger('window_day')->default(7);   // ISO-8601: 1=lunes ... 7=domingo
            $table->time('window_start')->default('09:00:00');
            $table->time('window_end')->default('13:00:00');
            $table->string('mode', 20)->default('auto');             // auto | forzar_vivo | forzar_oculto
            $table->boolean('archivar')->default(false);
            $table->string('url_grabacion', 500)->nullable();
            $table->string('title', 255)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('live_streams');
    }
};
