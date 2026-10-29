<?php

namespace App\Http\Controllers;

use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use Illuminate\Http\Request;

class ApiClientController extends Controller
{
    /**
     * Lista los clientes API (incluidos los archivados).
     */
    public function show(Request $request)
    {
        $query = ApiClient::query()->withTrashed();

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('token_prefix', 'like', "%{$search}%");
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.api.show-api-clients', compact('clients'));
    }

    /**
     * Crea un cliente API y muestra el token en claro UNA sola vez.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:120',
        ], [
            'name.required' => 'Debe indicar el nombre del servicio o emisora.',
            'name.max'      => 'El nombre no debe superar los 120 caracteres.',
        ]);

        ['plain' => $plain] = ApiClient::issue($request->input('name'));

        // El token en claro se muestra UNA sola vez vía flash; no es recuperable después.
        return back()
            ->with('success', 'Cliente API creado. Copia el token ahora; no se volverá a mostrar.')
            ->with('new_api_token', $plain);
    }

    /**
     * Activa o revoca (desactiva) un cliente API.
     */
    public function toggle($id)
    {
        $client = ApiClient::withTrashed()->findOrFail($id);
        $client->active = ! $client->active;
        $client->save();

        $estado = $client->active ? 'activado' : 'revocado';

        return back()->with('success', "El cliente API ha sido {$estado}.");
    }

    /**
     * Archiva (soft delete) un cliente API.
     */
    public function destroy($id)
    {
        $client = ApiClient::findOrFail($id);
        $client->delete();

        return back()->with('success', 'El cliente API ha sido archivado.');
    }

    /**
     * Estadísticas de uso de la API: resumen, conteo por cliente y log reciente.
     */
    public function stats(Request $request)
    {
        // Totales globales (portables en MySQL y SQLite: count() en lugar de sum() sobre boolean).
        $total          = ApiRequestLog::count();
        $autorizadas    = ApiRequestLog::where('authorized', true)->count();
        $noAutorizadas  = ApiRequestLog::where('authorized', false)->count();
        $clientesActivos = ApiClient::where('active', true)->count();

        // Conteo por cliente: total de peticiones y última petición.
        $totalesPorCliente = ApiRequestLog::selectRaw('api_client_id, count(*) as total, max(created_at) as ultimo')
            ->groupBy('api_client_id')
            ->get()
            ->keyBy('api_client_id');

        // Clientes (incluidos archivados) para cruzarlos con los conteos.
        $clientes = ApiClient::withTrashed()->orderBy('name')->get();

        // Log reciente paginado (evita N+1 con with('client')).
        $logs = ApiRequestLog::with('client')->latest('created_at')->paginate(20);

        return view('admin.api.api-stats', compact(
            'total',
            'autorizadas',
            'noAutorizadas',
            'clientesActivos',
            'totalesPorCliente',
            'clientes',
            'logs'
        ));
    }
}
