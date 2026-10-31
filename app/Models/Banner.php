<?php

namespace App\Models;

use App\Support\YouTube;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Banner extends Model
{
    protected $table = 'carousel_images';
    protected $fillable = ['image_left', 'image_right', 'active'];

    protected $casts = [
        'active' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected function detectMediaType(?string $value): string
    {
        if (empty($value)) {
            return 'image';
        }

        if (Str::contains($value, ['youtube.com', 'youtu.be'])) {
            return 'youtube';
        }

        $path = parse_url($value, PHP_URL_PATH) ?: $value;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $videoExt = ['mp4', 'webm', 'ogg', 'mov'];

        if (in_array($ext, $videoExt, true)) {
            return 'video';
        }

        return 'image';
    }

    protected function detectMediaSrc(?string $value): ?string
    {
        if (empty($value)) {
            return null;
        }

        if (Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return asset('images/slider/' . $value);
    }

    protected function detectMediaMime(?string $value): ?string
    {
        $path = parse_url($value, PHP_URL_PATH) ?: $value;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        if ($ext === 'mp4') {
            return 'video/mp4';
        }
        if ($ext === 'webm') {
            return 'video/webm';
        }
        if ($ext === 'ogg') {
            return 'video/ogg';
        }
        if ($ext === 'mov') {
            return 'video/quicktime';
        }

        return null;
    }

    protected function detectYoutubeId(?string $value): ?string
    {
        // Fuente única del regex en App\Support\YouTube::extractId().
        return YouTube::extractId($value);
    }

    protected function detectYoutubeEmbedUrl(?string $value): ?string
    {
        $id = $this->detectYoutubeId($value);
        return $id ? 'https://www.youtube.com/embed/' . $id : null;
    }

    public function getLeftMediaTypeAttribute(): string
    {
        return $this->detectMediaType($this->image_left);
    }

    public function getRightMediaTypeAttribute(): string
    {
        return $this->detectMediaType($this->image_right);
    }

    public function getLeftMediaSrcAttribute(): ?string
    {
        return $this->detectMediaSrc($this->image_left);
    }

    public function getRightMediaSrcAttribute(): ?string
    {
        return $this->detectMediaSrc($this->image_right);
    }

    public function getLeftMediaMimeAttribute(): ?string
    {
        return $this->detectMediaMime($this->image_left);
    }

    public function getRightMediaMimeAttribute(): ?string
    {
        return $this->detectMediaMime($this->image_right);
    }

    public function getLeftMediaEmbedUrlAttribute(): ?string
    {
        return $this->detectYoutubeEmbedUrl($this->image_left);
    }

    public function getRightMediaEmbedUrlAttribute(): ?string
    {
        return $this->detectYoutubeEmbedUrl($this->image_right);
    }

    public function getImageLeftUrlAttribute()
    {
        return $this->getLeftMediaSrcAttribute();
    }

    public function getImageRightUrlAttribute()
    {
        return $this->getRightMediaSrcAttribute();
    }

    /**
     * Obtiene el estado formateado
     */
    public function getStatusFormattedAttribute()
    {
        return $this->active ? 'Activo' : 'Inactivo';
    }
}
