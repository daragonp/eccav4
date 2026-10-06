<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class LibraryResource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'type',
        'category_id',
        'author',
        'cover',
        'file',
        'external_url',
        'published',
        'published_at',
    ];

    protected $casts = [
        'published' => 'boolean',
        'published_at' => 'date',
    ];

    /**
     * Categoría a la que pertenece el recurso.
     */
    public function category()
    {
        return $this->belongsTo(LibraryCategory::class, 'category_id');
    }

    // ------------------------------------------------------------------
    // Portada
    // ------------------------------------------------------------------

    /**
     * URL de la portada: null si no hay; URL tal cual si es externa; asset() si es archivo.
     */
    public function getCoverUrlAttribute(): ?string
    {
        if (empty($this->cover)) {
            return null;
        }

        if (Str::startsWith($this->cover, ['http://', 'https://'])) {
            return $this->cover;
        }

        return asset('library/covers/' . $this->cover);
    }

    // ------------------------------------------------------------------
    // Modo del medio (archivo vs enlace)
    // ------------------------------------------------------------------

    /**
     * true si el medio es un enlace externo; false si es un archivo subido.
     */
    public function getIsExternalAttribute(): bool
    {
        return !empty($this->external_url);
    }

    /**
     * URL del archivo subido: null si no hay; asset() en caso contrario.
     */
    public function getFileUrlAttribute(): ?string
    {
        if (empty($this->file)) {
            return null;
        }

        return asset('library/files/' . $this->file);
    }

    /**
     * Fuente unificada del medio: external_url si es enlace, si no la URL del archivo.
     */
    public function getMediaSrcAttribute(): ?string
    {
        if ($this->is_external) {
            return $this->external_url;
        }

        return $this->file_url;
    }

    // ------------------------------------------------------------------
    // Detección de plataforma de video (solo enlaces)
    // ------------------------------------------------------------------

    /**
     * Devuelve 'youtube' | 'dailymotion' | 'vimeo' | null según el host del enlace.
     */
    protected function detectVideoPlatform(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        if (Str::contains($url, ['youtube.com', 'youtu.be'])) {
            return 'youtube';
        }

        if (Str::contains($url, ['dailymotion.com', 'dai.ly'])) {
            return 'dailymotion';
        }

        if (Str::contains($url, ['vimeo.com'])) {
            return 'vimeo';
        }

        return null;
    }

    /**
     * Plataforma de video solo si el recurso es un video externo.
     */
    public function getVideoPlatformAttribute(): ?string
    {
        if ($this->type === 'video' && $this->is_external) {
            return $this->detectVideoPlatform($this->external_url);
        }

        return null;
    }

    // ------------------------------------------------------------------
    // Extracción de ID por plataforma
    // ------------------------------------------------------------------

    /**
     * Extrae el ID de un video de YouTube (regex idéntica a Banner::detectYoutubeId).
     */
    protected function detectYoutubeId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $match = preg_match('/(?:youtube(?:-nocookie)?\.com\/(?:.*[?&]v=|embed\/|shorts\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/', $url, $matches);
        return $match ? $matches[1] : null;
    }

    /**
     * Extrae el ID de un video de Dailymotion.
     */
    protected function detectDailymotionId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $match = preg_match('/(?:dailymotion\.com\/(?:video|embed\/video)\/|dai\.ly\/)([A-Za-z0-9]+)/', $url, $matches);
        return $match ? $matches[1] : null;
    }

    /**
     * Extrae el ID de un video de Vimeo.
     */
    protected function detectVimeoId(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }

        $match = preg_match('/vimeo\.com\/(?:video\/|channels\/[\w]+\/|groups\/[\w]+\/videos\/)?(\d+)/', $url, $matches);
        return $match ? $matches[1] : null;
    }

    // ------------------------------------------------------------------
    // Embed URL unificada
    // ------------------------------------------------------------------

    /**
     * URL embebible según la plataforma de video; null si no se pudo resolver.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        $platform = $this->video_platform;

        if ($platform === 'youtube') {
            $id = $this->detectYoutubeId($this->external_url);
            return $id ? 'https://www.youtube.com/embed/' . $id : null;
        }

        if ($platform === 'dailymotion') {
            $id = $this->detectDailymotionId($this->external_url);
            return $id ? 'https://www.dailymotion.com/embed/video/' . $id : null;
        }

        if ($platform === 'vimeo') {
            $id = $this->detectVimeoId($this->external_url);
            return $id ? 'https://player.vimeo.com/video/' . $id : null;
        }

        return null;
    }

    /**
     * true si existe una embed_url válida (video externo embebible).
     */
    public function getIsEmbeddableAttribute(): bool
    {
        return $this->embed_url !== null;
    }

    // ------------------------------------------------------------------
    // Mime para <video>/<audio> de archivos subidos
    // ------------------------------------------------------------------

    /**
     * Deduce el mime del archivo subido por su extensión (null si no reconocible).
     */
    public function getMediaMimeAttribute(): ?string
    {
        if (empty($this->file)) {
            return null;
        }

        $ext = strtolower(pathinfo($this->file, PATHINFO_EXTENSION));

        switch ($ext) {
            case 'mp4':
                return 'video/mp4';
            case 'webm':
                return 'video/webm';
            case 'mov':
                return 'video/quicktime';
            case 'ogg':
                // ogg puede ser video o audio según el tipo del recurso
                return $this->type === 'audio' ? 'audio/ogg' : 'video/ogg';
            case 'mp3':
                return 'audio/mpeg';
            case 'wav':
                return 'audio/wav';
            case 'm4a':
                return 'audio/mp4';
            case 'aac':
                return 'audio/aac';
            default:
                return null;
        }
    }

    // ------------------------------------------------------------------
    // Estado para la tabla admin
    // ------------------------------------------------------------------

    /**
     * Estado de publicación legible para el panel.
     */
    public function getStatusFormattedAttribute(): string
    {
        return $this->published ? 'Publicado' : 'Despublicado';
    }
}
