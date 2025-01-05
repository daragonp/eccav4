<?php

namespace App\Models;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Week extends Model
{
    use HasFactory;
    protected $fillable = [
        'dayname',
        'shortdayname'
    ];

    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'day');
    }

}

