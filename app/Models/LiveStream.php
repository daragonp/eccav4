<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Configuración singleton de la transmisión "Culto en Vivo".
 *
 * La tabla `live_streams` guarda una única fila con las plataformas, la
 * ventana horaria del culto, el modo de visibilidad y el archivado. El acceso
 * de conveniencia es LiveStream::current(), que garantiza la fila por defecto
 * con firstOrCreate (idempotente aunque el seeder no haya corrido).
 */
class LiveStream extends Model
{
    protected $table = 'live_streams';

    protected $fillable = [
        'platform', 'youtube_channel_id', 'youtube_url',
        'facebook_url', 'tiktok_url', 'x_url', 'instagram_url',
        'window_day', 'window_start', 'window_end',
        'mode', 'archivar', 'url_grabacion', 'title', 'active',
    ];

    protected $casts = [
        'window_day' => 'integer',
        'archivar'   => 'boolean',
        'active'     => 'boolean',
    ];

    /**
     * Devuelve el singleton de configuración, creándolo con los valores por
     * defecto si la tabla está vacía (domingo 09:00–13:00, modo automático).
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'platform'     => 'youtube',
            'window_day'   => 7,        // domingo (ISO-8601)
            'window_start' => '09:00:00',
            'window_end'   => '13:00:00',
            'mode'         => 'auto',
            'archivar'     => false,
            'active'       => true,
        ]);
    }
}
