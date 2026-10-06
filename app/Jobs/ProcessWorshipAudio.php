<?php

namespace App\Jobs;

use App\Models\Worship;
use App\Services\AudioProcessingService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProcessWorshipAudio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Tiempo máximo (en segundos) que puede correr el job.
     * El procesamiento con IA (Whisper + GPT-4 + DALL-E) puede ser lento.
     */
    public int $timeout = 600;

    /**
     * Crea una nueva instancia del job.
     *
     * @param  int     $worshipId            Id del culto a procesar.
     * @param  string  $audioPath            Ruta del audio a transcribir.
     * @param  bool    $applyAbstractIfEmpty Aplicar el resumen de IA solo si el culto no trae resumen.
     * @param  bool    $applyTitleIfEmpty    Aplicar el título de IA (y recalcular slug) solo si no se envió título.
     * @param  bool    $applyImageIfMissing  Aplicar la imagen de IA solo si no se subió una imagen.
     */
    public function __construct(
        public int $worshipId,
        public string $audioPath,
        public bool $applyAbstractIfEmpty = false,
        public bool $applyTitleIfEmpty = false,
        public bool $applyImageIfMissing = false,
    ) {
    }

    /**
     * Ejecuta el procesamiento de IA del audio en segundo plano.
     */
    public function handle(AudioProcessingService $service): void
    {
        $worship = Worship::find($this->worshipId);

        if (!$worship) {
            Log::warning('ProcessWorshipAudio: no se encontró el culto', ['worship_id' => $this->worshipId]);
            return;
        }

        try {
            $aiResult = $service->processAudio($this->audioPath, $worship->title);

            $worship->ai_summary = $aiResult['summary'];
            $worship->ai_image = $aiResult['image_url'];
            $worship->ai_processed = true;

            if ($this->applyAbstractIfEmpty && empty($worship->abstract) && !empty($aiResult['summary'])) {
                $worship->abstract = $aiResult['summary'];
            }

            if ($this->applyTitleIfEmpty && !empty($aiResult['title'])) {
                $worship->title = $aiResult['title'];
                $worship->slug = Str::slug($worship->title);
            }

            if ($this->applyImageIfMissing && !empty($aiResult['image_url'])) {
                $worship->image = $aiResult['image_url'];
            }

            $worship->save();
        } catch (\Throwable $e) {
            Log::error('Error procesando audio con IA en segundo plano: ' . $e->getMessage(), [
                'worship_id' => $this->worshipId,
            ]);
        }
    }
}
