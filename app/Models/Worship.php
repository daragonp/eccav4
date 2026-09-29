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

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        return asset('images/worship/' . $this->image);
    }

    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio)) {
            return null;
        }
        if (str_starts_with($this->audio, 'http://') || str_starts_with($this->audio, 'https://')) {
            return $this->audio;
        }
        return asset('audio/worship/' . $this->audio);
    }
}
