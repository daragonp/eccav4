<?php

namespace Database\Seeders;

use App\Models\LibraryCategory;
use App\Models\LibraryResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LibrarySeeder extends Seeder
{
    /**
     * Siembra 2 categorías y 3 recursos de ejemplo que ejercitan las tres ramas
     * de render (libro PDF archivo, video YouTube enlace, audio archivo).
     */
    public function run(): void
    {
        // Categorías de ejemplo
        $teologia = LibraryCategory::firstOrCreate(
            ['slug' => Str::slug('Teología')],
            ['name' => 'Teología', 'description' => 'Recursos de formación teológica.']
        );
        $alabanza = LibraryCategory::firstOrCreate(
            ['slug' => Str::slug('Alabanza')],
            ['name' => 'Alabanza', 'description' => 'Recursos de adoración y alabanza.']
        );

        // Asegurar que existan archivos de ejemplo en disco (idempotente)
        $this->ensureSampleFiles();

        // 1. Libro (archivo PDF) — prueba el visor PDF + descarga y el placeholder de portada
        LibraryResource::updateOrCreate(
            ['slug' => 'introduccion-a-la-teologia'],
            [
                'title' => 'Introducción a la Teología',
                'description' => 'Un libro introductorio de ejemplo en formato PDF.',
                'type' => 'libro',
                'category_id' => $teologia->id,
                'author' => 'P. Henry Belalcázar',
                'cover' => null,
                'file' => 'ejemplo_libro.pdf',
                'external_url' => null,
                'published' => true,
                'published_at' => Carbon::now(),
            ]
        );

        // 2. Video YouTube (enlace) — prueba video_platform/embed_url + "Ver en origen"
        LibraryResource::updateOrCreate(
            ['slug' => 'mensaje-de-esperanza'],
            [
                'title' => 'Mensaje de Esperanza',
                'description' => 'Un video de ejemplo enlazado desde YouTube.',
                'type' => 'video',
                'category_id' => $alabanza->id,
                'author' => 'Ministerio ECCA',
                'cover' => null,
                'file' => null,
                'external_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'published' => true,
                'published_at' => Carbon::now(),
            ]
        );

        // 3. Audio (archivo) — prueba <audio controls> + descarga
        LibraryResource::updateOrCreate(
            ['slug' => 'alabanza-matutina'],
            [
                'title' => 'Alabanza Matutina',
                'description' => 'Un audio de ejemplo en formato MP3.',
                'type' => 'audio',
                'category_id' => $alabanza->id,
                'author' => 'Coro ECCA',
                'cover' => null,
                'file' => 'ejemplo_audio.mp3',
                'external_url' => null,
                'published' => true,
                'published_at' => Carbon::now(),
            ]
        );
    }

    /**
     * Crea archivos de ejemplo mínimos en public/library/files si no existen.
     */
    protected function ensureSampleFiles(): void
    {
        $dir = public_path('library/files');
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        $pdf = $dir . '/ejemplo_libro.pdf';
        if (!file_exists($pdf)) {
            // PDF mínimo válido de una página en blanco.
            $contenido = "%PDF-1.1\n"
                . "1 0 obj<</Type/Catalog/Pages 2 0 R>>endobj\n"
                . "2 0 obj<</Type/Pages/Kids[3 0 R]/Count 1>>endobj\n"
                . "3 0 obj<</Type/Page/Parent 2 0 R/MediaBox[0 0 612 792]>>endobj\n"
                . "trailer<</Root 1 0 R>>\n"
                . "%%EOF";
            file_put_contents($pdf, $contenido);
        }

        $mp3 = $dir . '/ejemplo_audio.mp3';
        if (!file_exists($mp3)) {
            // Marco MP3 silencioso mínimo (placeholder); suficiente para servir/descargar.
            file_put_contents($mp3, hex2bin('fffb90640000000000000000000000000000'));
        }
    }
}
