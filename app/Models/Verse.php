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

    protected $casts = [
        'date' => 'date',
        'deleted_at' => 'datetime',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) {
            return null;
        }
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }
        return asset('images/bible/' . $this->image);
    }

    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio)) {
            return null;
        }
        if (str_starts_with($this->audio, 'http://') || str_starts_with($this->audio, 'https://')) {
            return $this->audio;
        }
        return asset('audio/quote/' . $this->audio);
    }

    public function getPdfUrlAttribute(): ?string
    {
        if (empty($this->video)) {
            return null;
        }
        if (str_starts_with($this->video, 'http://') || str_starts_with($this->video, 'https://')) {
            return $this->video;
        }
        return asset('documents/quote/' . $this->video);
    }
}
