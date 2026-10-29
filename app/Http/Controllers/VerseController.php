<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Verse;
use Illuminate\Http\Request;

class VerseController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required',
            'date'  => 'required',
            'text'  => 'required|string',
        ], [
            'image.required' => 'Debe seleccionar la imagen del recurso.',
            'date.required'  => 'Debe seleccionar la fecha en la que se mostrará el recurso.',
            'text.required'  => 'Debe ingresar el texto del versículo.',
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
        $quote->text       = $request->input('text');
        $quote->created_at = Carbon::now();
        $quote->updated_at = Carbon::now();
        $quote->save();

        return back()->with('success', 'El recurso ha sido creado');
    }

    public function view($id)
    {
        // withTrashed: permite ver el detalle de versículos inactivos (soft-deleted),
        // que la lista sí muestra. Sin esto, /view-quote/{id} de un inactivo da 404.
        $quote = Verse::withTrashed()->findOrFail($id);
        return view('admin.quote.view-quote', compact('quote'));
    }

    public function show(Request $request)
    {
        $query = Verse::query()->withTrashed();

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('date', 'like', "%{$search}%")
                  ->orWhere('image', 'like', "%{$search}%");
            });
        }

        $verses = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();

        return view('admin.quote.show-quote', compact('verses'));
    }

    public function edit($id, Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'text' => 'required|string',
        ], [
            'date.required' => 'El campo fecha es obligatorio.',
            'text.required' => 'Debe ingresar el texto del versículo.',
        ]);

        $quote = Verse::withTrashed()->findOrFail($id);

        if ($request->hasFile('image')) {
            $imgName = time().'.'.$request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(public_path('images/bible'), $imgName);
            $quote->image = $imgName;
        }

        if ($request->hasFile('audio')) {
            $audioName = time().'.'.$request->file('audio')->getClientOriginalExtension();
            $request->file('audio')->move(public_path('audio/quote'), $audioName);
            $quote->audio = $audioName;
        }

        if ($request->hasFile('video')) {
            $videoName = time().'.'.$request->file('video')->getClientOriginalExtension();
            $request->file('video')->move(public_path('documents/quote'), $videoName);
            $quote->video = $videoName;
        }

        $quote->date       = $request->input('date');
        $quote->text       = $request->input('text');
        $quote->updated_at = Carbon::now();
        $quote->save();

        return back()->with('success', 'El versículo ha sido actualizado');
    }

    public function update(Request $request, $id)
    {
        $quote = Verse::withTrashed()->findOrFail($id);
        return view('admin.quote.update-quote', compact('quote'));
    }

    public function destroy($id)
    {
        $quote = Verse::findOrFail($id);
        $quote->delete();

        return back()->with('success', 'La publicación no está disponible al público');
    }

    public function activate($id)
    {
        $quote = Verse::withTrashed()->findOrFail($id);
        $quote->restore();

        return back()->with('success', 'La publicación ha sido activada al público');
    }

    public function delete($id)
    {
        $quote = Verse::withTrashed()->findOrFail($id);
        
        // Clean up files if exist
        if ($quote->image && \Illuminate\Support\Facades\File::exists(public_path('images/bible/' . $quote->image))) {
            \Illuminate\Support\Facades\File::delete(public_path('images/bible/' . $quote->image));
        }
        if ($quote->audio && \Illuminate\Support\Facades\File::exists(public_path('audio/quote/' . $quote->audio))) {
            \Illuminate\Support\Facades\File::delete(public_path('audio/quote/' . $quote->audio));
        }
        if ($quote->video && \Illuminate\Support\Facades\File::exists(public_path('documents/quote/' . $quote->video))) {
            \Illuminate\Support\Facades\File::delete(public_path('documents/quote/' . $quote->video));
        }

        $quote->forceDelete();

        // Redirige a la lista: back() volvería a /view-quote/{id} de un registro ya
        // eliminado permanentemente, lo que produciría un 404.
        return redirect('show-quote')->with('success', 'La publicación ha sido eliminada definitivamente.');
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
            return redirect('/')->with('error', 'No se encontró el contenido para la fecha seleccionada.');
        }
    }
}
