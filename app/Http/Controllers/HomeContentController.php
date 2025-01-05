<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\News;
use App\Models\Verse;
use App\Models\Podcast;
use App\Models\Present;
use App\Models\Suscriber;
use App\Models\Worship;
use Illuminate\Http\Request;
use Illuminate\Support\Fluent;
use Stevebauman\Location\Position;
use Stevebauman\Location\Facades\Location;




class HomeContentController extends Controller
{
    //
    public function index()
    {
        $date = Carbon::now()->toDateString();

        $quote = Verse::whereDate('date', '=', $date)->first();

        $podcast = Podcast::all();

        $news = News::orderBy('created_at', 'DESC')->take(1)->get();

        $opinion = News::orderBy('created_at', 'DESC')->take(1)->get();

        $worship = Worship::whereNull('deleted_at')
            ->orderBy('created_at', 'DESC')
            ->take(1)
            ->first();

        //dd($quote);

        return view('welcome', compact('quote', 'podcast', 'news', 'opinion', 'worship'));
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

    public function suscriberemail(Request $request, Position $position, Fluent $location)
    {
        $newsuscribe = new Suscriber();

        $position = Location::get();
        $newsuscribe->email = $request->email;
        $newsuscribe->ip = $position->ip;
        $newsuscribe->country = $position->countryName;
        $newsuscribe->city = $position->cityName;
        $newsuscribe->latitud = $position->latitude;
        $newsuscribe->longitude = $position->longitude;
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
}
