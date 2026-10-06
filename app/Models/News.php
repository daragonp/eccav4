<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'category',
        'slug',
        'abstract',
        'audio',
        'pdfdoc',
        'autor',
        'image'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function getNewstextContentAttribute()
    {
        return \Illuminate\Support\Str::words(html_entity_decode(strip_tags($this->abstract ?? '')), 400);
    }

    public function getNewsabstractContentAttribute()
    {
        return \Illuminate\Support\Str::words(html_entity_decode(strip_tags($this->abstract ?? '')), 200);
    }

    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image)) return null;
        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) return $this->image;
        if (file_exists(public_path('storage/images/news/' . $this->image))) {
            return asset('storage/images/news/' . $this->image);
        }
        return asset('images/news/' . $this->image);
    }

    public function getPdfUrlAttribute(): ?string
    {
        if (empty($this->pdfdoc)) return null;
        if (str_starts_with($this->pdfdoc, 'http://') || str_starts_with($this->pdfdoc, 'https://')) return $this->pdfdoc;
        if (file_exists(public_path('storage/documents/news/' . $this->pdfdoc))) {
            return asset('storage/documents/news/' . $this->pdfdoc);
        }
        return asset('documents/news/' . $this->pdfdoc);
    }

    public function getAudioUrlAttribute(): ?string
    {
        if (empty($this->audio)) return null;
        if (str_starts_with($this->audio, 'http://') || str_starts_with($this->audio, 'https://')) return $this->audio;
        if (file_exists(public_path('storage/audio/news/' . $this->audio))) {
            return asset('storage/audio/news/' . $this->audio);
        }
        return asset('audio/news/' . $this->audio);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function newscomments()
    {
        return $this->hasMany(Comment::class);
    }
}
