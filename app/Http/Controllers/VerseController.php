<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Verse;
use Illuminate\Http\Request;
use App\DataTables\VerseDataTable;

class VerseController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'date'  => 'required',
        ], [
            'image.required' => 'Debe seleccionar la imagen del recurso.',
            'date.required'  => 'Debe seleccionar la fecha en la que se mostrará el recurso.',
        ]);

        $quote = new Verse();

        if ($request->hasFile('image')) {
            $imgName = time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move('images/bible', $imgName);
            $quote->image = $imgName;
        }

        if ($request->hasFile('audio')) {
            $audioName = time().'.'.$request->file('audio')->getClientOriginalExtension();
            $request->file('audio')->move('audio/quote', $audioName);
            $quote->audio = $audioName;
        }

        if ($request->hasFile('video')) {
            $videoName = time().'.'.$request->file('video')->getClientOriginalExtension();
            $request->file('video')->move('documents/quote', $videoName);
            $quote->video = $videoName;
        }

        $quote->date       = $request->input('date');
        $quote->created_at = Carbon::now();
        $quote->updated_at = Carbon::now();
        $quote->save();

        return back()->with('success', 'El recurso ha sido creado');
    }

    public function view($id)
    {
        $quote = Verse::findOrFail($id);
        return view('admin.quote.view-quote', compact('quote'));
    }

    public function show(VerseDataTable $dataTable)
    {
        return $dataTable->render('admin.quote.show-quote');
    }

    public function edit($id, Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
        ], [
            'date.required' => 'El campo fecha es obligatorio.',
        ]);

        $quote = Verse::findOrFail($id);

        if ($request->hasFile('image')) {
            $imgName = time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move('images/bible', $imgName);
            $quote->image = $imgName;
        }

        if ($request->hasFile('audio')) {
            $audioName = time().'.'.$request->file('audio')->getClientOriginalExtension();
            $request->file('audio')->move('audio/quote', $audioName);
            $quote->audio = $audioName;
        }

        if ($request->hasFile('video')) {
            $videoName = time().'.'.$request->file('video')->getClientOriginalExtension();
            $request->file('video')->move('documents/quote', $videoName);
            $quote->video = $videoName;
        }

        $quote->date       = $request->input('date');
        // NO tocar created_at en edición
        $quote->updated_at = Carbon::now();
        $quote->save();

        return back()->with('success', 'El versículo ha sido actualizado');
    }

    public function update(Request $request, $id)
    {
        $quote = Verse::find($id);
        return view('admin.quote.update-quote', compact('quote'));
    }

    public function destroy($id)
    {
        $quote = Verse::findOrFail($id);
        $quote->deleted_at = Carbon::now();
        $quote->save();

        return back()->with('success', 'La publicación no está disponible al público');
    }

    public function activate($id)
    {
        $quote = Verse::findOrFail($id);
        $quote->deleted_at = null;
        $quote->save();

        return back()->with('success', 'La publicación ha sido activada al público');
    }

    public function delete($id)
    {
        $quote = Verse::findOrFail($id);
        $quote->delete();

        return back()->with('success', 'La publicación ha sido eliminada definitivamente.');
    }

    public function history()
    {
        $today = Carbon::today();
        $twoMonthsAgo = $today->copy()->subMonths(2);

        $verses = Verse::whereBetween('date', [$twoMonthsAgo, $today])
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy(fn ($item) => Carbon::parse($item->date)->translatedFormat('F Y'));

        $availableDates = Verse::pluck('date')->map(fn($date) => Carbon::parse($date)->format('Y-m-d'));

        return view('worship-home', compact('verses', 'availableDates'));
    }

    public function single($date)
    {
        try {
            $verse = Verse::whereDate('date', $date)->firstOrFail();
            return view('single-feed', compact('verse'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('home')->with('error', 'No se encontró el contenido para la fecha seleccionada.');
        }
    }
}
