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
        'window_days', 'window_start', 'window_end',
        'mode', 'archivar', 'url_grabacion', 'title',
    ];

    protected $casts = [
        'window_days' => 'array',
        'archivar'    => 'boolean',
    ];

    /**
     * Accesor para compatibilidad hacia atrás con window_day.
     */
    public function getWindowDayAttribute(): int
    {
        $days = $this->window_days;
        return is_array($days) && count($days) > 0 ? (int) $days[0] : 7;
    }

    /**
     * Mutador para compatibilidad hacia atrás con window_day.
     */
    public function setWindowDayAttribute($value): void
    {
        $this->attributes['window_days'] = json_encode([(int) $value]);
    }

    /**
     * Devuelve el singleton de configuración, creándolo con los valores por
     * defecto si la tabla está vacía (domingo 09:00–13:00, modo automático).
     */
    public static function current(): self
    {
        return static::firstOrCreate([], [
            'platform'     => 'youtube',
            'window_days'  => [7],      // domingo (ISO-8601)
            'window_start' => '09:00:00',
            'window_end'   => '13:00:00',
            'mode'         => 'auto',
            'archivar'     => false,
        ]);
    }
}
