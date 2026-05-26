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
            'broadcast' => 'required|date',
            'title' => 'nullable|string|max:255',
            'abstract' => 'nullable|string|max:5000',
            'badge' => 'nullable|string|max:255',
            'autor' => 'nullable|string|max:30',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'pdfdoc' => 'nullable|file|mimes:pdf|max:10240',
            'audio' => 'required|file|mimes:mp3,wav,ogg|max:102400',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:512000',
        ], [
            'broadcast.required' => 'El campo fecha de emisión es obligatorio.',
            'broadcast.date' => 'La fecha de emisión debe ser válida.',
            'audio.required' => 'El audio es obligatorio.',
            'audio.mimes' => 'El audio debe ser un archivo mp3, wav u ogg.',
            'audio.max' => 'El audio no puede ser mayor a 100MB.',
        ]);

        $worship = new Worship();

        if ($request->filled('title')) {
            $worship->title = $request->input('title');
        } else {
            $worship->title = 'Culto del ' . Carbon::parse($request->input('broadcast'))->format('d/m/Y');
        }

        $slugBase = Str::slug($worship->title);
        $slug = $slugBase;
        $count = 1;
        while (Worship::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $count++;
        }
        $worship->slug = $slug;

        $worship->autor = $request->input('autor', 'P. Henry Belalcázar');
        $worship->badge = $request->input('badge', 'Culto Dominical');
        $worship->abstract = $request->input('abstract', '');
        $worship->broadcast = $request->input('broadcast');
        $worship->pdfdoc = '';

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imgName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/worship'), $imgName);
            $worship->image = $imgName;
        }

        $audioPath = null;
        $needsAIProcessing = false;
        if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
            $audioName = time() . '.' . $request->file('audio')->getClientOriginalExtension();
            $audioDir = public_path('audio/worship');
            if (!is_dir($audioDir)) {
                mkdir($audioDir, 0755, true);
            }
            $request->file('audio')->move($audioDir, $audioName);
            $worship->audio = $audioName;
            $audioPath = 'audio/worship/' . $audioName;
            $needsAIProcessing = empty($request->input('abstract')) || !$request->hasFile('image');
        }

        if ($request->hasFile('pdfdoc') && $request->file('pdfdoc')->isValid()) {
            $docuName = time() . '.' . $request->file('pdfdoc')->getClientOriginalExtension();
            $request->file('pdfdoc')->move(public_path('documents/worship'), $docuName);
            $worship->pdfdoc = $docuName;
        }

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $videoName = time() . '.' . $request->file('video')->getClientOriginalExtension();
            $request->file('video')->move(public_path('video/worship'), $videoName);
            $worship->video = $videoName;
        }

        $worship->save();

        if ($audioPath && $needsAIProcessing) {
            try {
                $aiResult = $this->audioProcessingService->processAudio($audioPath, $worship->title);
                $worship->ai_summary = $aiResult['summary'];
                $worship->ai_image = $aiResult['image_url'];
                $worship->ai_processed = true;

                if (empty($request->input('abstract')) && !empty($aiResult['summary'])) {
                    $worship->abstract = $aiResult['summary'];
                }

                if (!$request->filled('title') && !empty($aiResult['title'])) {
                    $worship->title = $aiResult['title'];
                    $worship->slug = Str::slug($worship->title);
                }

                if (!$request->hasFile('image') && !empty($aiResult['image_url'])) {
                    $worship->image = $aiResult['image_url'];
                }

                $worship->save();
            } catch (\Exception $e) {
                Log::error('Error procesando audio con IA: ' . $e->getMessage());
            }
        }

        return redirect('show-worship')->with('success', 'Se ha agregado el culto dominical');
    }

    public function edit($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'abstract' => 'required|string|max:5000',
            'broadcast' => 'required|date',
            'autor' => 'required|string|max:30',
            'badge' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'pdfdoc' => 'nullable|file|mimes:pdf|max:10240',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg|max:102400',
            'video' => 'nullable|file|mimes:mp4,mov,avi|max:512000',
        ], [
            'title.required' => 'El título es obligatorio.',
            'abstract.required' => 'El resumen es obligatorio.',
            'broadcast.required' => 'La fecha de emisión es obligatoria.',
            'broadcast.date' => 'La fecha debe ser válida.',
            'autor.required' => 'El autor es obligatorio.',
        ]);

        $worship = Worship::findOrFail($id);
        $worship->title = $request->input('title');

        $slugBase = Str::slug($request->title);
        $slug = $slugBase;
        $count = 1;
        while (Worship::where('slug', $slug)->where('id', '!=', $worship->id)->exists()) {
            $slug = $slugBase . '-' . $count++;
        }
        $worship->slug = $slug;
        $worship->abstract = $request->input('abstract');
        $worship->broadcast = $request->input('broadcast');
        $worship->badge = $request->input('badge');
        $worship->autor = $request->input('autor');

        if ($request->hasFile('pdfdoc') && $request->file('pdfdoc')->isValid()) {
            $docuName = time() . '.' . $request->file('pdfdoc')->getClientOriginalExtension();
            $request->file('pdfdoc')->move(public_path('documents/worship'), $docuName);
            $worship->pdfdoc = $docuName;
        }

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $imgName = time() . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/worship'), $imgName);
            $worship->image = $imgName;
        }

        $audioPath = null;
        if ($request->hasFile('audio') && $request->file('audio')->isValid()) {
            $audioName = time() . '.' . $request->file('audio')->getClientOriginalExtension();
            $request->file('audio')->move(public_path('audio/worship'), $audioName);
            $worship->audio = $audioName;
            $audioPath = 'audio/worship/' . $audioName;
            $worship->ai_processed = false;
        }

        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $videoName = time() . '.' . $request->file('video')->getClientOriginalExtension();
            $request->file('video')->move(public_path('video/worship'), $videoName);
            $worship->video = $videoName;
        }

        $worship->updated_at = Carbon::now();
        $worship->save();

        if ($audioPath) {
            try {
                $aiResult = $this->audioProcessingService->processAudio($audioPath, $worship->title);
                $worship->ai_summary = $aiResult['summary'];
                $worship->ai_image = $aiResult['image_url'];
                $worship->ai_processed = true;
                $worship->save();

                if (!$request->hasFile('image') && !empty($aiResult['image_url'])) {
                    $worship->image = $aiResult['image_url'];
                    $worship->save();
                }
            } catch (\Exception $e) {
                Log::error('Error procesando audio con IA: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Se ha actualizado el culto dominical');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
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
        $worship->delete();

        return redirect()->back()->with('success', 'La publicación no está disponible al público');
    }

    public function activate($id)
    {
        $worship = Worship::withTrashed()->findOrFail($id);
        $worship->restore();

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
        $worship = Worship::withTrashed()->findOrFail($id);
        $worship->forceDelete();

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
