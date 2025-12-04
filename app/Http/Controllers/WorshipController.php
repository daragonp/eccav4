<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Worship;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\WorshipDataTable;
use App\Http\Controllers\Controller;
use App\Services\AudioProcessingService;
use Illuminate\Support\Facades\Log;

class WorshipController extends Controller
{
    protected $audioProcessingService;
    
    public function __construct(AudioProcessingService $audioProcessingService)
    {
        $this->audioProcessingService = $audioProcessingService;
    }
    
    /**
     * Display a listing of resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        $worship = Worship::where('slug', $slug)->first();
        return view('showworship', compact('worship'));
    }

    /**
     * Show form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'broadcast' => 'required',
            'audio' => 'required|mimes:mp3,wav,ogg|max:102400', // 100MB máximo
        ], [
            'broadcast.required' => 'El campo fecha de emisión es obligatorio.',
            'audio.required' => 'El campo audio es obligatorio.',
            'audio.mimes' => 'El audio debe ser un archivo mp3, wav u ogg.',
            'audio.max' => 'El audio no puede ser mayor a 100MB.',
        ]);

        $worship = new Worship();
        
        // Si no se proporciona título, generar uno automáticamente
        if ($request->has('title') && !empty($request->input('title'))) {
            $worship->title = $request->input('title');
        } else {
            $worship->title = 'Culto del ' . \Carbon\Carbon::parse($request->input('broadcast'))->format('d/m/Y');
        }
        
        // Generar slug
        $slug = Str::slug($worship->title);
        $str = preg_replace('/[^a-z0-9]/', '-', $slug);
        $worship->slug = $str;
        
        // Autor por defecto
        $worship->autor = 'P. Henry Belalcázar';
        
        // Etiqueta por defecto
        $worship->badge = 'Culto Dominical';
        
        // Usar el resumen proporcionado o dejar vacío para IA
        $worship->abstract = $request->input('abstract', '');
        
        $worship->broadcast = $request->input('broadcast');
        
        // Valor por defecto para pdfdoc para evitar el error
        $worship->pdfdoc = '';

        // Procesamiento de imagen manual
        if ($request->hasFile('image')) {
            $imgName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move('images/worship', $imgName);
            $worship->image = $imgName;
        }

        // Procesamiento de audio y generación de contenido con IA
        $audioPath = null;
        $needsAIProcessing = false;
        
        if ($request->hasFile('audio')) {
            $audioName = time() . '.' . $request->file('audio')->getClientOriginalExtension();
            
            // Verificar que el directorio exista
            $audioDir = public_path('audio/worship');
            if (!is_dir($audioDir)) {
                mkdir($audioDir, 0755, true);
            }
            
            $request->file('audio')->move($audioDir, $audioName);
            $worship->audio = $audioName;
            $audioPath = 'audio/worship/' . $audioName;
            
            // Determinar si se necesita procesamiento con IA
            $needsAIProcessing = empty($request->input('abstract')) || !$request->hasFile('image');
            
            Log::info('Audio guardado en: ' . $audioDir . '/' . $audioName);
            Log::info('Ruta que se pasará al servicio: ' . $audioPath);
            Log::info('¿Se necesita procesamiento con IA?: ' . ($needsAIProcessing ? 'Sí' : 'No'));
        }

        $worship->created_at = Carbon::now();
        $worship->updated_at = Carbon::now();
        $worship->save();

        // Procesar el audio con IA solo si es necesario
        if ($audioPath && $needsAIProcessing) {
            try {
                Log::info('Iniciando procesamiento con IA para: ' . $audioPath);
                $aiResult = $this->audioProcessingService->processAudio($audioPath, $worship->title);
                
                // Actualizar el registro con los resultados de la IA
                $worship->ai_summary = $aiResult['summary'];
                $worship->ai_image = $aiResult['image_url'];
                $worship->ai_processed = true;
                
                // Si no se proporcionó resumen, usar el generado por IA
                if (empty($request->input('abstract')) && !empty($aiResult['summary'])) {
                    $worship->abstract = $aiResult['summary'];
                }
                
                // Si no se proporcionó título y la IA generó uno, usarlo
                if (!$request->has('title') && !empty($aiResult['title'])) {
                    $worship->title = $aiResult['title'];
                    // Actualizar el slug
                    $slug = Str::slug($worship->title);
                    $str = preg_replace('/[^a-z0-9]/', '-', $slug);
                    $worship->slug = $str;
                }
                
                // Si no se proporcionó imagen manual y la IA generó una, usarla
                if (!$request->hasFile('image') && $aiResult['image_url']) {
                    $worship->image = $aiResult['image_url'];
                }
                
                $worship->save();
                
                Log::info('Procesamiento con IA completado exitosamente');
            } catch (\Exception $e) {
                Log::error('Error procesando audio con IA: ' . $e->getMessage());
                // Continuar sin procesamiento de IA si hay un error
            }
        }

        return redirect('show-worship')->with('success', 'Se ha agregado el culto dominical');
    }
    
    public function view($id)
    {
        $worship = Worship::findOrFail($id);
        return view('admin.worship.view-worship', compact('worship'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Worship  $worship
     * @return \Illuminate\Http\Response
     */
    public function show(WorshipDataTable $worship)
    {
        return $worship->render('admin.worship.show-worship');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Worship  $worship
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'abstract' => 'required',
            'broadcast' => 'required',
            'autor' => 'required',
        ]);

        $worship = Worship::findOrFail($id);

        $worship->title = $request->input('title');
        $slug = Str::slug($request->title);
        $str = preg_replace('/[^a-z0-9]/', '-', $slug);
        $worship->slug = $str;
        $worship->abstract = $request->input('abstract');
        $worship->broadcast = $request->input('broadcast');
        $worship->badge = $request->input('badge');

        // Procesamiento de PDF
        if ($request->hasFile('pdfdoc')) {
            $docuName = time() . '.' . $request->file('pdfdoc')->getClientOriginalExtension();
            $request->file('pdfdoc')->move('documents/worship', $docuName);
            $worship->pdfdoc = $docuName;
        }

        $worship->autor = $request->input('autor');

        // Procesamiento de imagen
        if ($request->hasFile('image')) {
            $imgName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move('images/worship', $imgName);
            $worship->image = $imgName;
        }

        // Procesamiento de audio y generación de contenido con IA
        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audioName = time() . '.' . $request->file('audio')->getClientOriginalExtension();
            $request->file('audio')->move(public_path('audio/worship'), $audioName);
            $worship->audio = $audioName;
            $audioPath = 'audio/worship/' . $audioName;
            
            // Marcar como no procesado por IA para que se procese de nuevo
            $worship->ai_processed = false;
        }

        // Procesamiento de video
        if ($request->hasFile('video')) {
            $videoName = time() . '.' . $request->file('video')->getClientOriginalExtension();
            $request->file('video')->move('video/worship', $videoName);
            $worship->video = $videoName;
        }

        $worship->updated_at = Carbon::now();
        $worship->save();

        // Procesar el audio con IA si se proporcionó un nuevo audio
        if ($audioPath) {
            try {
                $aiResult = $this->audioProcessingService->processAudio($audioPath, $worship->title);
                
                // Actualizar el registro con los resultados de la IA
                $worship->ai_summary = $aiResult['summary'];
                $worship->ai_image = $aiResult['image_url'];
                $worship->ai_processed = true;
                $worship->save();
                
                // Si se generó una imagen con IA y no hay imagen manual, usar la de IA
                if (!$request->hasFile('image') && $aiResult['image_url']) {
                    $worship->image = $aiResult['image_url'];
                    $worship->save();
                }
            } catch (\Exception $e) {
                Log::error('Error procesando audio con IA: ' . $e->getMessage());
                // Continuar sin procesamiento de IA si hay un error
            }
        }

        return redirect()->back()->with('success', 'Se ha actualizado el culto dominical');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Worship  $worship
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $worship = Worship::find($id);
        return view('admin.worship.update-worship', compact('worship'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Worship  $worship
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $worship = Worship::findOrFail($id);
        $worship->deleted_at = Carbon::now();
        $worship->save();

        return redirect()->back()->with('success', 'La publicación no está disponible al público');
    }

    public function activate($id)
    {
        $worship = Worship::findOrFail($id);
        $worship->deleted_at = NULL;
        $worship->save();

        return redirect()->back()->with('success', 'La publicación ha sido activada al público');
    }

    /**
     * Reprocesa un audio con IA (para audios que no se procesaron correctamente)
     */
    public function reprocessWithAI($id)
    {
        $worship = Worship::findOrFail($id);
        
        if (!$worship->audio) {
            return redirect()->back()->with('error', 'No hay audio para procesar');
        }
        
        try {
            $audioPath = 'audio/worship/' . $worship->audio;
            $aiResult = $this->audioProcessingService->processAudio($audioPath, $worship->title);
            
            // Actualizar el registro con los resultados de la IA
            $worship->ai_summary = $aiResult['summary'];
            $worship->ai_image = $aiResult['image_url'];
            $worship->ai_processed = true;
            $worship->save();
            
            return redirect()->back()->with('success', 'El audio ha sido procesado con IA correctamente');
        } catch (\Exception $e) {
            Log::error('Error procesando audio con IA: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error al procesar el audio con IA: ' . $e->getMessage());
        }
    }
    
    /**
     * Elimina permanentemente un registro
     */
    public function delete($id)
    {
        $worship = Worship::findOrFail($id);
        $worship->delete();

        return redirect()->back()->with('success', 'La publicación ha sido eliminada definitivamente.');
    }

    /**
 * Display a listing of worship records for public view.
 *
 * @return \Illuminate\Http\Response
 */
public function publicIndex()
{
    // Obtener los cultos ordenados por fecha de emisión, más recientes primero
    $worships = Worship::orderBy('broadcast', 'desc')
        ->whereNull('deleted_at') // Solo mostrar registros no eliminados
        ->paginate(12);
        
    return view('public.worships.index', compact('worships'));
}

/**
 * Display the specified worship record for public view.
 *
 * @param  string  $slug
 * @return \Illuminate\Http\Response
 */
/**
 * Display the specified worship record for public view.
 *
 * @param  string  $slug
 * @return \Illuminate\Http\Response
 */
public function publicShow($slug)
{
    $worship = Worship::where('slug', $slug)
        ->whereNull('deleted_at') // Solo mostrar registros no eliminados
        ->firstOrFail();
        
    // Obtener cultos relacionados (anteriores y posteriores)
    $previous = Worship::where('broadcast', '<', $worship->broadcast)
        ->whereNull('deleted_at')
        ->orderBy('broadcast', 'desc')
        ->first();
        
    $next = Worship::where('broadcast', '>', $worship->broadcast)
        ->whereNull('deleted_at')
        ->orderBy('broadcast', 'asc')
        ->first();
    
    // Obtener cultos relacionados para mostrar en la sección inferior
    $relatedWorships = Worship::where('id', '!=', $worship->id)
        ->whereNull('deleted_at')
        ->orderBy('broadcast', 'desc')
        ->take(3)
        ->get();
    
    return view('public.worships.show', compact('worship', 'previous', 'next', 'relatedWorships'));
}

/**
 * Get latest worship records for welcome page
 *
 * @return \Illuminate\Http\Response
 */
public function getLatestForWelcome()
{
    // Obtener los 3 cultos más recientes
    $latestWorships = Worship::orderBy('broadcast', 'desc')
        ->whereNull('deleted_at')
        ->take(3)
        ->get();
        
    return $latestWorships;
}
    
}