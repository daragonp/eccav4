<?php

namespace App\Models;

use App\Models\Week;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'about',
        'start',
        'end',
        'host',
        'duration',
        'day'
    ];

    public function week()
    {
        return $this->belongsTo(Week::class, 'day');
    }

}
