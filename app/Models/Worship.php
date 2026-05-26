<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worship extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'abstract',
        'badge',
        'broadcast',
        'pdfdoc',
        'autor',
        'image',
        'audio',
        'video',
        'urlyt',
        'ai_summary',
        'ai_image',
        'ai_processed'
    ];

    protected $casts = [
        'broadcast' => 'date',
        'deleted_at' => 'datetime',
        'ai_processed' => 'boolean'
    ];
}
