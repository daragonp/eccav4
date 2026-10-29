<?php

namespace App\Http\Middleware;

use App\Models\ApiClient;
use App\Models\ApiRequestLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiClient
{
    /**
     * Autentica a un cliente de la API privada mediante token (API key).
     *
     * El token se lee de la cabecera "Authorization: Bearer <token>" o, como
     * alternativa, de "X-API-Key". Se compara su hash sha256 contra los clientes
     * activos (no archivados). En todos los casos se registra la petición en
     * api_request_logs. El valor del token nunca se escribe en los registros.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $this->extractToken($request);

        if (! $token) {
            return $this->deny($request, 'Falta el token de autenticación.');
        }

        $client = ApiClient::where('token', hash('sha256', $token))
            ->where('active', true)
            ->first();

        if (! $client) {
            return $this->deny($request, 'Token inválido o cliente inactivo.');
        }

        // Cliente autenticado: lo dejamos disponible para el controlador.
        $request->attributes->set('api_client', $client);

        // saveQuietly: evitamos disparar eventos del modelo en cada autenticación.
        $client->forceFill(['last_used_at' => now()])->saveQuietly();

        $response = $next($request);

        $this->log($request, $client->id, $response->getStatusCode(), true);

        return $response;
    }

    /**
     * Rechaza la petición con 401 JSON y registra el intento no autorizado.
     */
    private function deny(Request $request, string $message): Response
    {
        $this->log($request, null, 401, false);

        return response()->json([
            'message' => 'No autorizado. ' . $message,
        ], 401);
    }

    /**
     * Extrae el token de la cabecera Authorization (Bearer) o de X-API-Key.
     */
    private function extractToken(Request $request): ?string
    {
        return $request->bearerToken() ?: ($request->header('X-API-Key') ?: null);
    }

    /**
     * Registra la petición en api_request_logs. Nunca se guarda el token.
     * El registro de estadísticas jamás debe tumbar la API.
     */
    private function log(Request $request, ?int $clientId, int $status, bool $authorized): void
    {
        try {
            ApiRequestLog::create([
                'api_client_id' => $clientId,
                'endpoint'      => $request->path(),
                'method'        => $request->method(),
                'ip'            => $request->ip(),
                'user_agent'    => substr((string) $request->userAgent(), 0, 255),
                'status_code'   => $status,
                'authorized'    => $authorized,
                'created_at'    => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('No se pudo registrar api_request_log: ' . $e->getMessage());
        }
    }
}
