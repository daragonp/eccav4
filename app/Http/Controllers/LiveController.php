<?php

namespace App\Http\Controllers;

use App\Services\LiveStreamService;
use Illuminate\Support\Facades\Cache;

/**
 * Lado público de "Culto en Vivo": la página /en-vivo y el endpoint proxy
 * /api/live-status que consumen el banner de la home y la propia página.
 *
 * El endpoint replica el mecanismo server-side de AdminController@nowPlaying
 * (Http + Cache + json 200 con la credencial solo en servidor), pero expuesto
 * como ruta pública porque lo consume el visitante anónimo.
 */
class LiveController extends Controller
{
    /**
     * Página pública /en-vivo con el estado renderizado server-side. El front
     * refresca además vía fetch para transiciones en directo.
     */
    public function index(LiveStreamService $service)
    {
        return view('live.index', [
            'state' => $service->getLiveState(),
        ]);
    }

    /**
     * Endpoint proxy JSON. Cachea el estado completo normalizado 3 minutos.
     * Nunca expone la API key de YouTube. Siempre responde HTTP 200.
     */
    public function status(LiveStreamService $service)
    {
        $data = Cache::remember('livestream:status', now()->addMinutes(3), function () use ($service) {
            return $service->getLiveState();
        });

        return response()->json($data, 200);
    }
}
