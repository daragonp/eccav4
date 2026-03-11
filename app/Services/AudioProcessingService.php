<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AudioProcessingService
{
    /**
     * Resuelve la ruta física del audio (public o storage/app).
     */
    private function resolveAudioPath(string $audioPath): ?string
    {
        $pathsToTry = [
            public_path($audioPath),
            storage_path('app/' . $audioPath),
            storage_path('app/public/' . $audioPath),
            base_path($audioPath),
        ];

        foreach ($pathsToTry as $path) {
            if (is_readable($path)) {
                return $path;
            }
        }

        Log::error('El archivo de audio no existe en rutas conocidas', [
            'audio_path' => $audioPath,
            'paths' => $pathsToTry,
        ]);

        return null;
    }

    /**
     * Procesa un archivo de audio para generar un resumen y una imagen
     *
     * @param string $audioPath Ruta al archivo de audio
     * @param string $title Título del culto (para contexto)
     * @return array Con 'summary' y 'image_url'
     */
    public function processAudio(string $audioPath, string $title = ''): array
    {
        $fullPath = $this->resolveAudioPath($audioPath);
        if (!$fullPath) {
            return [
                'title' => $title,
                'summary' => 'Error: El archivo de audio no existe.',
                'image_url' => null,
                'transcription' => 'Error: El archivo de audio no existe en la ruta esperada.'
            ];
        }
        
        // 1. Transcribir el audio
        $transcription = $this->transcribeAudio($audioPath);
        
        // 2. Generar un resumen a partir de la transcripción
        $result = $this->generateSummary($transcription, $title);
        
        // 3. Generar una imagen basada en el resumen
        $imageUrl = $this->generateImage($result['summary'], $result['title']);
        
        return [
            'title' => $result['title'],
            'summary' => $result['summary'],
            'image_url' => $imageUrl,
            'transcription' => $transcription
        ];
    }
    
    /**
     * Transcribe el audio a texto
     */
    private function transcribeAudio(string $audioPath): string
    {
        $fullPath = $this->resolveAudioPath($audioPath);
        if (!$fullPath) {
            return "Error: El archivo de audio no existe.";
        }

        $apiKey = (string) config('services.openai.api_key');
        if ($apiKey === '') {
            Log::error('OPENAI_API_KEY no está configurada');
            return 'No se pudo transcribir el audio. Falta configurar la API key.';
        }

        try {
            $response = Http::withToken($apiKey)
                ->attach('file', file_get_contents($fullPath), basename($fullPath))
                ->asMultipart()
                ->post('https://api.openai.com/v1/audio/transcriptions', [
                    'model' => 'whisper-1',
                    'response_format' => 'text',
                ]);

            if ($response->successful()) {
                return trim($response->body());
            }

            Log::error('Error en transcripción de audio: ' . $response->body());
            return "No se pudo transcribir el audio. Error: " . $response->json('error.message', 'Error desconocido');
        } catch (\Exception $e) {
            Log::error('Excepción en transcripción de audio: ' . $e->getMessage());
            return "No se pudo transcribir el audio. Error: " . $e->getMessage();
        }
    }
    
    /**
     * Genera un resumen a partir del texto transcrito
     */
    private function generateSummary(string $transcription, string $title = ''): array
    {
        $apiKey = (string) config('services.openai.api_key');
        if ($apiKey === '') {
            return [
                'title' => $title,
                'summary' => 'No se pudo generar un resumen del audio.',
            ];
        }

        // Si ya hay un título, solo generar resumen
        if (!empty($title)) {
            $prompt = "Por favor, genera un resumen conciso pero completo del siguiente texto de un sermón o predicación cristiana. El resumen debe capturar los puntos principales, mensajes clave y enseñanzas. El título del sermón es: '{$title}'.\n\nTexto transcrito:\n\n{$transcription}\n\nResumen:";
        } else {
            // Si no hay título, generar título y resumen
            $prompt = "Analiza el siguiente texto de un sermón o predicación cristiana y proporciona:\n1. Un título conciso y atractivo para el sermón\n2. Un resumen completo que capture los puntos principales y enseñanzas clave\n\nResponde en formato JSON:\n{\n  \"title\": \"Título sugerido\",\n  \"summary\": \"Resumen del contenido\"\n}\n\nTexto transcrito:\n\n{$transcription}";
        }
        
        try {
            $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-4',
                'messages' => [
                    ['role' => 'system', 'content' => 'Eres un asistente experto en analizar contenido religioso cristiano. Responde siempre en formato JSON válido.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 800,
                'temperature' => 0.7,
            ]);
            
            if ($response->successful()) {
                $content = $response->json('choices.0.message.content', '{}');
                $data = json_decode($content, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'title' => $data['title'] ?? $title,
                        'summary' => $data['summary'] ?? 'No se pudo generar un resumen del audio.'
                    ];
                } else {
                    Log::error('Error decodificando JSON de OpenAI: ' . json_last_error_msg());
                }
            }
            
            Log::error('Error en generación de resumen: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Excepción en generación de resumen: ' . $e->getMessage());
        }
        
        // En caso de error, devolvemos valores por defecto
        return [
            'title' => $title,
            'summary' => 'No se pudo generar un resumen del audio.'
        ];
    }
    
    /**
     * Genera una imagen basada en el resumen
     */
    private function generateImage(string $summary, string $title = ''): ?string
    {
        $apiKey = (string) config('services.openai.api_key');
        if ($apiKey === '') {
            return null;
        }

        try {
            // Creamos un prompt para DALL-E basado en el resumen
            $prompt = "Crea una imagen inspiradora y espiritual que represente visualmente este sermón cristiano: '{$title}'. La imagen debe ser apropiada para un contexto religioso, con tonos cálidos y pacíficos. Estilo artístico, no fotográfico realista.";
            
            $response = Http::withToken($apiKey)->post('https://api.openai.com/v1/images/generations', [
                'model' => 'dall-e-3',
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1024x1024',
                'style' => 'vivid',
            ]);
            
            if ($response->successful()) {
                $imageUrl = $response->json('data.0.url');
                
                if ($imageUrl) {
                    $download = Http::timeout(30)->get($imageUrl);
                    if (!$download->successful()) {
                        Log::error('No se pudo descargar imagen generada por IA');
                        return null;
                    }

                    $imageName = 'ai_worship_' . Str::random(10) . '.png';
                    Storage::disk('public')->put('images/worship/' . $imageName, $download->body());
                    
                    return $imageName;
                }
            }
            
            Log::error('Error en generación de imagen: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Excepción en generación de imagen: ' . $e->getMessage());
        }
        
        // En caso de error, devolvemos null
        return null;
    }
}
