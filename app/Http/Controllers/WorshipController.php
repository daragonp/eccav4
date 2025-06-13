<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Worship;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\WorshipDataTable;
use App\Http\Controllers\Controller;

class WorshipController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        //
        $worship = Worship::where('slug', $slug)->first();
        return view('showworship', compact('worship'));
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
        $this->validate($request, [
            'title' => 'required',
            'abstract' => 'required',
            'broadcast' => 'required',
            'autor' => 'required',
            'badge' => 'required',
        ], [
            'title.required' => 'El campo título es obligatorio.',
            'abstract.required' => 'El campo descripción es obligatorio.',
            'broadcast.required' => 'El campo fecha de emisión es obligatorio.',
            'autor.required' => 'El campo autor es obligatorio.',
        ]);

        $worship = new Worship();
        $worship->title = $request->input('title');
        $slug = Str::slug($request->title);
        $str = preg_replace('/[^a-z0-9]/', '-', $slug);
        $worship->slug = $str;
        $worship->abstract = $request->input('abstract');
        $worship->broadcast = $request->input('broadcast');
        $worship->badge = $request->input('badge');
        $document = $request->pdfdoc;

        if ($document) {

            $docuName = time() . '.' . $document->getClientOriginalExtension();

            $request->pdfdoc->move('documents/worship', $docuName);

            $worship->pdfdoc = $docuName;
        }else{
            $worship->pdfdoc = NULL;
        }

        $worship->autor = $request->input('autor');

        $image = $request->image;

        if ($image) {

            $imgName = time() . '.' . $image->getClientOriginalExtension();

            $request->image->move('images/worship', $imgName);

            $worship->image = $imgName;
        }

        $audio = $request->audio;

        if ($audio) {

            $audioName = time() . '.' . $audio->getClientOriginalExtension();

            $request->audio->move('audio/worship', $audioName);

            $worship->audio = $audioName;
        }

        $video = $request->video;

        if ($video) {

            $videoName = time() . '.' . $video->getClientOriginalExtension();

            $request->video->move('video/worship', $videoName);

            $worship->video = $videoName;
        }

        $worship->created_at = Carbon::now();
        $worship->updated_at = Carbon::now();

        //dd($worship);
        $worship->save();


        return redirect('show-worship')->with('success', 'Se ha agregado el culto dominical');
    }

    /*public function history()
    {
        //
        $feed = Worship::orderBy('created_at', 'desc')->first();
        $list = Worship::orderBy('created_at', 'desc')->paginate(3);

        return view('worship-home', compact('feed', 'list'));
    }

    public function single($slug)
    {

        $post = Worship::where('slug', $slug)->first();
        //dd($slug);
        return view('single-feed', compact('post'));
    }*/

    public function view($id)
    {
        //
        $worship = Worship::findorFail($id);

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
        //
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

        //
        $this->validate($request, [
            'title' => 'required',
            'abstract' => 'required',
            'broadcast' => 'required',
            'autor' => 'required',
        ]);

        $worship = Worship::findorFail($id);

        $worship->title = $request->input('title');
        $slug = Str::slug($request->title);
        $str = preg_replace('/[^a-z0-9]/', '-', $slug);

        $worship->slug = $str;

        $worship->abstract = $request->input('abstract');

        $worship->broadcast = $request->input('broadcast');

        $worship->badge = $request->input('badge');

        $document = $request->pdfdoc;

        if ($document) {

            $docuName = time() . '.' . $document->getClientOriginalExtension();

            $request->pdfdoc->move('documents/worship', $docuName);

            $worship->pdfdoc = $docuName;
        }

        $worship->autor = $request->input('autor');

        $image = $request->image;

        if ($image) {

            $imgName = time() . '.' . $image->getClientOriginalExtension();

            $request->image->move('images/worship', $imgName);

            $worship->image = $imgName;
        }

        $audio = $request->audio;

        if ($audio) {

            $audioName = time() . '.' . $audio->getClientOriginalExtension();

            $request->audio->move('audio/worship', $audioName);

            $worship->audio = $audioName;
        }

        $video = $request->video;

        if ($video) {

            $videoName = time() . '.' . $video->getClientOriginalExtension();

            $request->video->move('video/worship', $videoName);

            $worship->video = $videoName;
        }

        $worship->updated_at = Carbon::now();

        $worship->save();


        return  redirect()->back()->with('success', 'Se ha actualizado el culto dominical');
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
        //
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
        //
        $worship = Worship::findorFail($id);

        $worship->deleted_at = Carbon::now();

        $worship->save();

        return redirect()->back()->with('success', 'La publicación no está disponible al público');
    }

    public function activate($id)
    {
        //
        $worship = Worship::findorFail($id);

        $worship->deleted_at = NULL;

        $worship->save();

        return redirect()->back()->with('success', 'La publicación ha sido activada al público');
    }

    public function delete($id)
    {
        //
        $worship = Worship::findorFail($id);

        $worship->delete();

        return redirect()->back()->with('success', 'La publicación ha sido eliminada definitivamente.');
    }
}
