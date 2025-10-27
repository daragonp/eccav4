<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleOverride extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Nombre de la tabla
     */
    protected $table = 'schedule_overrides';

    /**
     * Campos asignables masivamente
     */
    protected $fillable = [
        'date',
        'override_day',
        'reason',
        'is_active',
    ];

    /**
     * Conversiones de tipo para atributos
     */
    protected $casts = [
        'date' => 'date',
        'override_day' => 'integer',
        'is_active' => 'boolean',
        'deleted_at' => 'datetime',
    ];

    /**
     * Relación con la tabla weeks (día que se usará como reemplazo)
     */
    public function week()
    {
        return $this->belongsTo(Week::class, 'override_day', 'id');
    }

    /**
     * Scope para obtener overrides activos
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('deleted_at');
    }

    /**
     * Scope para obtener overrides por fecha
     */
    public function scopeByDate($query, $date)
    {
        return $query->where('date', $date);
    }

    /**
     * Scope para obtener overrides futuros
     */
    public function scopeFuture($query)
    {
        return $query->where('date', '>=', now()->format('Y-m-d'));
    }

    /**
     * Obtener el nombre del día de la semana que se usará
     */
    public function getOverrideDayNameAttribute()
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
        
        return $dias[$this->override_day] ?? 'Desconocido';
    }

    /**
     * Obtener el nombre del día de la semana de la fecha
     */
    public function getDateDayNameAttribute()
    {
        $dias = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo',
        ];
        
        $dayOfWeek = date('w', strtotime($this->date));
        return $dias[$dayOfWeek] ?? 'Desconocido';
    }
}
