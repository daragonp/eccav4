<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrivacyController extends Controller
{
    /**
     * Muestra la política de privacidad completa
     */
    public function privacy()
    {
        return view('legal.privacy-policy');
    }

    /**
     * Muestra la política de cookies
     */
    public function cookies()
    {
        return view('legal.cookies-policy');
    }

    /**
     * Muestra los términos y condiciones
     */
    public function terms()
    {
        return view('legal.terms-conditions');
    }

    /**
     * Registra la aceptación del aviso de privacidad
     */
    public function recordAcceptance(Request $request)
    {
        try {
            // Validar datos
            $validated = $request->validate([
                'accepted' => 'required|boolean',
                'timestamp' => 'required|date_format:Y-m-d\TH:i:s.000\Z',
                'userAgent' => 'nullable|string',
                'pageUrl' => 'nullable|url',
            ]);

            // Registrar en logs
            Log::channel('privacy')->info('Privacy notice accepted', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'page_url' => $request->input('pageUrl'),
                'timestamp' => $validated['timestamp'],
            ]);

            return response()->json([
                'message' => 'Aceptación registrada correctamente',
                'success' => true,
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Datos inválidos',
                'errors' => $e->errors(),
                'success' => false,
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error registrando aceptación de privacidad', [
                'error' => $e->getMessage(),
                'ip' => $request->ip(),
            ]);

            return response()->json([
                'message' => 'Error al registrar la aceptación',
                'success' => false,
            ], 500);
        }
    }
}
