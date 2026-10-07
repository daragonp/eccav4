<?php

namespace Tests\Unit;

use App\Models\LibraryResource;
use Tests\TestCase;

class LibraryResourceAccessorsTest extends TestCase
{
    private function make(array $attrs): LibraryResource
    {
        return new LibraryResource($attrs);
    }

    // -------------------- video_platform --------------------

    public function test_detecta_plataforma_youtube(): void
    {
        $r = $this->make(['type' => 'video', 'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
        $this->assertSame('youtube', $r->video_platform);

        $r2 = $this->make(['type' => 'video', 'external_url' => 'https://youtu.be/dQw4w9WgXcQ']);
        $this->assertSame('youtube', $r2->video_platform);
    }

    public function test_detecta_plataforma_dailymotion(): void
    {
        $r = $this->make(['type' => 'video', 'external_url' => 'https://www.dailymotion.com/video/x7tgad0']);
        $this->assertSame('dailymotion', $r->video_platform);

        $r2 = $this->make(['type' => 'video', 'external_url' => 'https://dai.ly/x7tgad0']);
        $this->assertSame('dailymotion', $r2->video_platform);
    }

    public function test_detecta_plataforma_vimeo(): void
    {
        $r = $this->make(['type' => 'video', 'external_url' => 'https://vimeo.com/123456789']);
        $this->assertSame('vimeo', $r->video_platform);
    }

    public function test_plataforma_null_para_host_desconocido(): void
    {
        $r = $this->make(['type' => 'video', 'external_url' => 'https://example.com/video.mp4']);
        $this->assertNull($r->video_platform);
    }

    public function test_plataforma_null_si_no_es_video_externo(): void
    {
        $r = $this->make(['type' => 'audio', 'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
        $this->assertNull($r->video_platform);

        $r2 = $this->make(['type' => 'video', 'file' => 'video.mp4']);
        $this->assertNull($r2->video_platform);
    }

    // -------------------- embed_url --------------------

    public function test_embed_url_youtube(): void
    {
        $r = $this->make(['type' => 'video', 'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ']);
        $this->assertSame('https://www.youtube.com/embed/dQw4w9WgXcQ', $r->embed_url);
    }

    public function test_embed_url_dailymotion(): void
    {
        $r = $this->make(['type' => 'video', 'external_url' => 'https://www.dailymotion.com/video/x7tgad0']);
        $this->assertSame('https://www.dailymotion.com/embed/video/x7tgad0', $r->embed_url);

        $r2 = $this->make(['type' => 'video', 'external_url' => 'https://dai.ly/x7tgad0']);
        $this->assertSame('https://www.dailymotion.com/embed/video/x7tgad0', $r2->embed_url);
    }

    public function test_embed_url_vimeo(): void
    {
        $r = $this->make(['type' => 'video', 'external_url' => 'https://vimeo.com/123456789']);
        $this->assertSame('https://player.vimeo.com/video/123456789', $r->embed_url);

        // Formato de canal
        $r2 = $this->make(['type' => 'video', 'external_url' => 'https://vimeo.com/channels/staffpicks/987654321']);
        $this->assertSame('https://player.vimeo.com/video/987654321', $r2->embed_url);
    }

    public function test_embed_url_null_para_url_invalida(): void
    {
        $r = $this->make(['type' => 'video', 'external_url' => 'https://example.com/foo']);
        $this->assertNull($r->embed_url);
    }

    public function test_is_embeddable(): void
    {
        $r = $this->make(['type' => 'video', 'external_url' => 'https://vimeo.com/123456789']);
        $this->assertTrue($r->is_embeddable);

        $r2 = $this->make(['type' => 'video', 'external_url' => 'https://example.com/foo']);
        $this->assertFalse($r2->is_embeddable);
    }

    // -------------------- is_external --------------------

    public function test_is_external(): void
    {
        $this->assertTrue($this->make(['external_url' => 'https://x.test/a'])->is_external);
        $this->assertFalse($this->make(['file' => 'a.pdf'])->is_external);
        $this->assertFalse($this->make([])->is_external);
    }

    // -------------------- cover_url --------------------

    public function test_cover_url_null_sin_portada(): void
    {
        $this->assertNull($this->make([])->cover_url);
    }

    public function test_cover_url_externa_tal_cual(): void
    {
        $r = $this->make(['cover' => 'https://cdn.test/portada.jpg']);
        $this->assertSame('https://cdn.test/portada.jpg', $r->cover_url);
    }

    public function test_cover_url_archivo_usa_asset(): void
    {
        $r = $this->make(['cover' => 'portada.jpg']);
        $this->assertStringContainsString('library/covers/portada.jpg', $r->cover_url);
    }

    // -------------------- media_mime --------------------

    public function test_media_mime_por_extension(): void
    {
        $this->assertSame('video/mp4', $this->make(['type' => 'video', 'file' => 'v.mp4'])->media_mime);
        $this->assertSame('video/webm', $this->make(['type' => 'video', 'file' => 'v.webm'])->media_mime);
        $this->assertSame('audio/mpeg', $this->make(['type' => 'audio', 'file' => 'a.mp3'])->media_mime);
        $this->assertSame('audio/wav', $this->make(['type' => 'audio', 'file' => 'a.wav'])->media_mime);
        $this->assertNull($this->make(['type' => 'libro', 'file' => 'b.pdf'])->media_mime);
    }

    public function test_media_mime_ogg_segun_tipo(): void
    {
        $this->assertSame('audio/ogg', $this->make(['type' => 'audio', 'file' => 'a.ogg'])->media_mime);
        $this->assertSame('video/ogg', $this->make(['type' => 'video', 'file' => 'v.ogg'])->media_mime);
    }

    public function test_media_mime_null_sin_archivo(): void
    {
        $this->assertNull($this->make(['type' => 'video', 'external_url' => 'https://x.test/v'])->media_mime);
    }

    // -------------------- status_formatted --------------------

    public function test_status_formatted(): void
    {
        $this->assertSame('Publicado', $this->make(['published' => true])->status_formatted);
        $this->assertSame('Despublicado', $this->make(['published' => false])->status_formatted);
    }
}
