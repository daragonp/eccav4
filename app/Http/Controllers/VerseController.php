<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Verse;
use Illuminate\Http\Request;
use App\DataTables\VerseDataTable;
use App\Http\Controllers\Controller;

class VerseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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

        $this->validate($request, [
            'image' => 'required',
            'date' => 'required',
        ], [
            'image.required' => 'Debe seleccionar la fecha en la que se mostrará el recurso.',
            'date.required' => 'Debe seleccionar la fecha en la que se mostrará el recurso.',
        ]);

        $quote = new Verse();

        $image = $request->image;

        if ($image) {

            $imgName = time() . '.' . $image->getClientOriginalExtension();

            $request->image->move('images/bible', $imgName);

            $quote->image = $imgName;
        }

        $audio = $request->audio;

        if ($audio) {

            $audioName = time() . '.' . $audio->getClientOriginalExtension();

            $request->audio->move('audio/quote', $audioName);

            $quote->audio = $audioName;
        }
        $video = $request->video;

        if ($video) {

            $videoName = time() . '.' . $video->getClientOriginalExtension();

            $request->video->move('documents/quote', $videoName);

            $quote->video = $videoName;
        }

        $quote->date = $request->input('date');

        $quote->created_at = Carbon::now();

        $quote->updated_at = Carbon::now();

        //dd($quote);
        $quote->save();

        return redirect()->back()->with('success', 'El recurso ha sido creado');
    }

    public function view($id)
    {
        //
        $quote = Verse::findorFail($id);

        return view('admin.quote.view-quote', compact('quote'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(VerseDataTable $dataTable)
    {
        //
        return $dataTable->render('admin.quote.show-quote');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        //
        $this->validate($request, [
            'date' => 'required',
            
        ], [
            'date.required' => 'El campo día es obligatorio.',
        ]);

        $quote = Verse::findorFail($id);

        $image = $request->image;

        if ($image) {

            $imgName = time() . '.' . $image->getClientOriginalExtension();

            $request->image->move('images/bible', $imgName);

            $quote->image = $imgName;
        }

        $audio = $request->audio;

        if ($audio) {

            $audioName = time() . '.' . $audio->getClientOriginalExtension();

            $request->audio->move('audio/quote', $audioName);

            $quote->audio = $audioName;
        }

        $video = $request->video;

        if ($video) {

            $videoName = time() . '.' . $video->getClientOriginalExtension();

            $request->video->move('documents/quote', $videoName);

            $quote->video = $videoName;
        }


        $quote->date = $request->input('date');

        $quote->created_at = Carbon::now();

        $quote->updated_at = Carbon::now();
        

        $quote->save();

        return redirect()->back()->with('success', 'El versículo ha sido actualizado');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $quote = Verse::find($id);

        return view('admin.quote.update-quote', compact('quote'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $quote = Verse::findorFail($id);

        $quote->deleted_at = Carbon::now();

        $quote->save();

        return redirect()->back()->with('success', 'La publicación no está disponible al público');
    }
    public function activate($id)
    {
        //
        $quote = Verse::findorFail($id);

        $quote->deleted_at = NULL;

        $quote->save();

        return redirect()->back()->with('success', 'La publicación ha sido activada al público');
    }

    public function delete($id)
    {
        //
        $quote = Verse::findorFail($id);

        $quote->delete();

        return redirect()->back()->with('success', 'La publicación ha sido eliminada definitivamente.');
    }
}
