<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Verse;
use Illuminate\Http\JsonResponse;

class VerseApiController extends Controller
{
    /**
     * Devuelve el versículo del día (el de la fecha de hoy).
     *
     * Mismo criterio que el front (HomeContentController): versículo cuya fecha es
     * HOY. El global scope de SoftDeletes excluye los inactivos. Si no existe,
     * responde 404 explícito (sin respaldo del más reciente).
     */
    public function today(): JsonResponse
    {
        $verse = Verse::whereDate('date', today())->first();

        if (! $verse) {
            return response()->json([
                'message' => 'No hay versículo disponible para hoy.',
            ], 404);
        }

        return response()->json([
            'data' => [
                'id'         => $verse->id,
                'date'       => optional($verse->date)->toDateString(),
                'text'       => $verse->text, // puede ser null si aún no se ha completado
                'reference'  => $verse->reference, // cita combinada "Juan 3:16; Salmos 23:1"; null si es manual
                'verses'     => $verse->verses_refs, // lista [{book,chapter,verse,pretty,text}]; null si es manual
                'image_url'  => $verse->image_url, // accessor → URL absoluta a images/bible
                'audio_url'  => $verse->audio_url, // null si no hay
                'pdf_url'    => $verse->pdf_url,    // columna `video` → documents/quote; null si no hay
                'created_at' => optional($verse->created_at)->toIso8601String(),
                'updated_at' => optional($verse->updated_at)->toIso8601String(),
            ],
        ], 200);
    }
}
