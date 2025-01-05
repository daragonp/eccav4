<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PodcastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user = auth()->user();
        if($user){
            return view('admin.dashboard');
        }else{
            return view('auth\login');
        }
    }

    public function podcast(){

        $category = Category::all();

        return view('admin.new-podcast', compact('category'));
    }
    /**
     * Show the form for creating a new resource.
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
        //
        $podcast = new Podcast();

        $audio = $request->audio;

        $audioName = time().'.'.$audio->getClientOriginalExtension();

        $request->audio->move('audio/podcast', $audioName);

        $podcast->audio_file = $audioName;

        $podcast->title = $request->name;

        $podcast->description = $request->description;

        $podcast->category_id = $request->category;
        //dd($podcast);

        $podcast->save();

        return redirect()->back()->with('mensaje', 'El podcast ha sido creado.');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Podcast  $podcast
     * @return \Illuminate\Http\Response
     */
    public function show(Podcast $podcasts)
    {
        //
        $podcasts = Podcast::all();

        return view('admin.show-podcasts', compact('podcasts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Podcast  $podcast
     * @return \Illuminate\Http\Response
     */
    public function update($id){

        $podcast = Podcast::find($id);
        $category = Category::all();

        return view('admin.update-podcast', compact('podcast', 'category'));

    }

    public function edit($id, Request $request){

  
        $podcast = podcast::find($id);

        $audio = $request->audio;

        if($audio){

            $audioName = time().'.'.$audio->getClientOriginalExtension();

            $request->audio->move('audio/podcast', $audioName);

            $podcast->audio_file = $audioName;
        }

        $podcast->title = $request->name;

        $podcast->description = $request->description;

        $podcast->category_id = $request->category;

        $podcast->save();

        return redirect()->back()->with('mensaje', 'El podcast ha sido actualizado');

    }
    public function delete($id)
    {
        //
        $podcast = Podcast::find($id);
        File::delete(public_path('audio/podcast/'.$podcast->audio_file));

        $podcast->delete();

        return redirect()->back()->with('mensaje', 'El podcast ha sido eliminado');
    }
}
