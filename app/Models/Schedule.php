<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nombre de la tabla
     */
    protected $table = 'schedules';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'name',
        'image',
        'slug',
        'about',
        'start',
        'end',
        'host',
        'day',
        'duration',
        'emission_key',
    ];

    /**
     * Conversiones de tipo para atributos
     */
    protected $casts = [
        'start' => 'datetime:H:i',
        'end' => 'datetime:H:i',
        'day' => 'integer',
        'duration' => 'integer',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación con la tabla weeks (días de la semana)
     * Permite acceder al nombre del día: $schedule->week->name
     */
    public function week()
    {
        return $this->belongsTo(Week::class, 'day', 'id');
    }

    /**
     * Scope para obtener programas activos (no eliminados)
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope para obtener programas por día
     */
    public function scopeByDay($query, $day)
    {
        return $query->where('day', $day);
    }

    /**
     * Scope para obtener programas por emission_key
     */
    public function scopeByEmissionKey($query, $emissionKey)
    {
        return $query->where('emission_key', $emissionKey);
    }

    /**
     * Obtener todos los programas del mismo bloque (misma emission_key)
     */
    public function getSiblingsAttribute()
    {
        return self::where('emission_key', $this->emission_key)
            ->where('id', '!=', $this->id)
            ->get();
    }

    /**
     * Obtener el nombre formateado del día
     */
    public function getDayNameAttribute()
    {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];

        return $dias[$this->day] ?? 'Desconocido';
    }

    /**
     * Obtener la duración formateada en horas y minutos
     */
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}min";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}min";
        }
    }

    /**
     * Obtener la URL de la imagen del programa
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && $this->image !== 'logoprogramacion.jpg' && $this->image !== 'genericprogramimage.png') {
            return asset('storage/images/schedule/' . $this->image);
        }
        return asset('images/schedule/logoprogramacion.jpg');
    }

    /**
     * Verificar si el programa está en emisión en este momento
     *
     * @return bool
     */
    public function isCurrentlyAiring()
    {
        $now = now();
        $currentDay = $now->dayOfWeekIso; // 1 = Lunes, 7 = Domingo
        $currentTime = $now->format('H:i');

        // Verificar si hay override para hoy
        $override = \App\Models\ScheduleOverride::where('date', $now->format('Y-m-d'))
            ->where('is_active', true)
            ->first();

        $dayToCheck = $override ? $override->override_day : $currentDay;
        $previousDay = $currentDay === 1 ? 7 : $currentDay - 1;

        if ($this->start <= $this->end) {
            return $this->day == $dayToCheck
                && $currentTime >= $this->start
                && $currentTime < $this->end;
        }

        return ($this->day == $dayToCheck && $currentTime >= $this->start)
            || ($this->day == $previousDay && $currentTime < $this->end);
    }

    /**
     * Obtener el programa que está al aire en este momento
     *
     * @return Schedule|null
     */
    public static function getCurrentProgram()
    {
        $now = now();
        $currentDay = $now->dayOfWeekIso; // 1 = Lunes, 7 = Domingo
        $currentTime = $now->format('H:i');

        // Verificar si hay override para hoy
        $override = \App\Models\ScheduleOverride::where('date', $now->format('Y-m-d'))
            ->where('is_active', true)
            ->first();

        $dayToCheck = $override ? $override->override_day : $currentDay;
        $previousDay = $currentDay === 1 ? 7 : $currentDay - 1;

        $candidates = self::whereNull('deleted_at')
            ->where(function ($query) use ($dayToCheck, $previousDay) {
                $query->where('day', $dayToCheck)
                    ->orWhere('day', $previousDay);
            })
            ->orderBy('day')
            ->orderBy('start', 'asc')
            ->get();

        foreach ($candidates as $program) {
            if ($program->isCurrentlyAiring()) {
                return $program;
            }
        }

        return null;
    }
}
