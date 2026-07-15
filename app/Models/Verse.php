<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Verse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
                            'image',
                            'audio',
                            'video',
                            'date',
                        ];
}
