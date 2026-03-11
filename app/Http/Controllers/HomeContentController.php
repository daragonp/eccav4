<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Carbon\Carbon;
use App\Models\News;
use App\Models\Verse;
use App\Models\Podcast;
use App\Models\Schedule;
use App\Models\Suscriber;
use App\Models\Worship;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;




class HomeContentController extends Controller
{
    //
    public function index()
    {
        $date = Carbon::now()->toDateString();

        $quote = Verse::whereDate('date', '=', $date)->first();

        $slider = Banner::where('active', 1)->orderBy('created_at', 'desc')->get();

        $podcast = Podcast::all();

        $news = News::orderBy('created_at', 'DESC')->take(1)->get();

        $opinion = News::orderBy('created_at', 'DESC')->take(1)->get();

        $worship = Worship::whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->take(1)
            ->first();

        /*$now = Carbon::now();
        $dayOfWeek = $now->dayOfWeek; // 0 = Domingo, 1 = Lunes, etc.
        $currentTime = $now->format('H:i'); // Ejemplo: "17:30"
        $programinfo = Schedule::where('day',  $dayOfWeek)->get;
        /* $programaActual =NULL;
        if (!$programaActual) {
            return response()->json([
                'nombre_programa' => 'Música Continua',
                'director' => 'Equipo de la Emisora',
                'foto_director' => asset('images/default-radio.jpg'), // Asegúrate de que esta imagen exista
                'horarios_emision' => ['24/7'],
                'descripcion_programa' => 'Disfruta de la mejor música sin interrupciones y con la mejor compañía.',
            ]);
        }
*/
        //return response()->json($programinfo);

        //dd($programinfo);

        return view('welcome', compact('quote', 'podcast', 'news', 'opinion', 'worship', 'slider'));
    }

    public function liveverse()
    {
        $date = Carbon::now()->toDateString();

        $quote = Verse::whereDate('date', '=', $date)->first();

        return view('live', compact('quote'));
    }

    public function seeds()
    {

        return view('seeds');
    }

    public function suscriberemail(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $newsuscribe = new Suscriber();

        $position = Location::get();
        $newsuscribe->email = $validated['email'];
        $newsuscribe->ip = $position->ip ?? $request->ip();
        $newsuscribe->country = $position->countryName ?? 'N/A';
        $newsuscribe->city = $position->cityName ?? 'N/A';
        $newsuscribe->latitud = $position->latitude ?? '0';
        $newsuscribe->longitude = $position->longitude ?? '0';
        $newsuscribe->save();

        return redirect()->back()->with('mensaje', 'Se ha suscrito a nuestro boletín, pronto tendrá más información.');
    }

    public function search(Request $request)
    {
        // Get the search value from the request
        $search = $request->input('search');

        // Search in the title and body columns from the buscar table
        $buscar = News::query()
            ->where('title', 'LIKE', "%{$search}%")
            ->orWhere('abstract', 'LIKE', "%{$search}%")
            ->orWhere('text', 'LIKE', "%{$search}%")
            ->get();

        // Return the search view with the resluts compacted
        return view('search', compact('buscar'));
    }

    public function getProgramaActual()
    {
        $now = Carbon::now();
        $currentDay = $now->dayOfWeekIso; // 1 = lunes, 7 = domingo
        $currentTime = $now->format('H:i:s');

        $programaActual = Schedule::where('day', $currentDay)
            ->where('start', '<=', $currentTime)
            ->where('end', '>', $currentTime)
            ->whereNull('deleted_at') // si usas soft deletes
            ->first();

        if (!$programaActual) {
            return response()->json([
                'nombre_programa' => 'Música Continua',
                'director' => 'Equipo de la Emisora',
                'foto_director' => asset('images/logo/logo.png'),
                'horarios_emision' => ['¡Siempre al aire!'],
                'descripcion_programa' => 'Disfruta de la mejor música sin interrupciones y con la mejor compañía.',
            ]);
        }

        $horarios = "De " . Carbon::parse($programaActual->start)->format('H:i') .
            " a " . Carbon::parse($programaActual->end)->format('H:i');

        return response()->json([
            'nombre_programa' => $programaActual->name,
            'director' => $programaActual->host,
            'foto_director' => asset('images/schedule/' . $programaActual->image),
            'horarios_emision' => [$horarios],
            'descripcion_programa' => $programaActual->about,
        ]);
    }
}
