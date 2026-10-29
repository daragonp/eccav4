<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Verse;
use App\Services\BibleTextService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class VerseController extends Controller
{
    public function store(Request $request)
    {
        $this->validate($request, [
            'image'       => 'required',
            'date'        => 'required',
            'text'        => 'required|string',
            'verses_refs' => 'nullable|string',
        ], [
            'image.required' => 'Debe seleccionar la imagen del recurso.',
            'date.required'  => 'Debe seleccionar la fecha en la que se mostrará el recurso.',
            'text.required'  => 'Debe ingresar el texto del versículo.',
        ]);

        // El backend es la única autoridad: resuelve text/reference/verses_refs
        // contra biblia.json y descarta cualquier dato enviado por el navegador.
        $bible = app(BibleTextService::class);

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

        $payload = $this->resolveVersesPayload($request, $bible);

        $quote->date        = $request->input('date');
        $quote->text        = $payload['text'];
        $quote->reference   = $payload['reference'];
        $quote->verses_refs = $payload['verses_refs'];
        $quote->created_at  = Carbon::now();
        $quote->updated_at  = Carbon::now();
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
            'date'        => 'required',
            'text'        => 'required|string',
            'verses_refs' => 'nullable|string',
        ], [
            'date.required' => 'El campo fecha es obligatorio.',
            'text.required' => 'Debe ingresar el texto del versículo.',
        ]);

        // El backend es la única autoridad: resuelve text/reference/verses_refs
        // contra biblia.json y descarta cualquier dato enviado por el navegador.
        $bible = app(BibleTextService::class);

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

        $payload = $this->resolveVersesPayload($request, $bible);

        $quote->date        = $request->input('date');
        $quote->text        = $payload['text'];
        $quote->reference   = $payload['reference'];
        $quote->verses_refs = $payload['verses_refs'];
        $quote->updated_at  = Carbon::now();
        $quote->save();

        return back()->with('success', 'El versículo ha sido actualizado');
    }

    /**
     * Centraliza el parseo, validación y resolución server-side del carrito de
     * versículos (campo oculto `verses_refs`). Es la única autoridad que decide el
     * valor final de text/reference/verses_refs: se descartan por completo los
     * campos pretty/text que pudiera enviar el navegador.
     *
     * - Si el carrito viene vacío/ausente: versículo manual. Conserva el `text`
     *   escrito a mano y deja reference y verses_refs en null.
     * - Si el carrito trae ítems: se resuelven contra biblia.json y se regeneran
     *   text, reference y verses_refs. Las refs inválidas (parciales o totales)
     *   provocan un error de validación recuperable, sin persistir nada.
     *
     * @return array{text:string, reference:?string, verses_refs:?array}
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function resolveVersesPayload(Request $request, BibleTextService $bible): array
    {
        $raw = $request->input('verses_refs');
        $refs = [];

        if (filled($raw)) {
            $decoded = json_decode($raw, true);

            if (!is_array($decoded)) {
                throw ValidationException::withMessages([
                    'verses_refs' => 'La selección de versículos no es válida.',
                ]);
            }

            foreach ($decoded as $item) {
                $book    = is_array($item) ? ($item['book'] ?? null) : null;
                $chapter = is_array($item) ? ($item['chapter'] ?? null) : null;
                $verse   = is_array($item) ? ($item['verse'] ?? null) : null;

                if (!is_string($book) || !preg_match('/^[a-z0-9\-]+$/', $book)
                    || !is_numeric($chapter) || (int) $chapter < 1
                    || !is_numeric($verse)   || (int) $verse < 1) {
                    throw ValidationException::withMessages([
                        'verses_refs' => 'La selección de versículos contiene datos inválidos.',
                    ]);
                }

                // Se ignoran pretty/text del navegador: solo viajan book/chapter/verse.
                $refs[] = [
                    'book'    => $book,
                    'chapter' => (int) $chapter,
                    'verse'   => (int) $verse,
                ];
            }
        }

        if (count($refs) > 20) {
            throw ValidationException::withMessages([
                'verses_refs' => 'Puedes seleccionar un máximo de 20 versículos.',
            ]);
        }

        // Caso manual: sin carrito, el texto escrito a mano manda.
        if (empty($refs)) {
            return [
                'text'        => $request->input('text'),
                'reference'   => null,
                'verses_refs' => null,
            ];
        }

        try {
            $result = $bible->buildTextAndReference($refs);
        } catch (\RuntimeException $e) {
            // Fallo de lectura de biblia.json: fatal para la operación pero
            // recuperable para el administrador. Se registra a nivel error.
            report($e);
            throw ValidationException::withMessages([
                'verses_refs' => 'No fue posible leer la Biblia en el servidor. Intenta más tarde.',
            ]);
        }

        if (empty($result['resolvedRefs'])) {
            throw ValidationException::withMessages([
                'verses_refs' => 'No fue posible resolver ningún versículo seleccionado.',
            ]);
        }

        if (!empty($result['invalidRefs'])) {
            // No descartar en silencio: avisar exactamente qué refs no existen.
            $citas = collect($result['invalidRefs'])
                ->map(fn ($r) => $bible->prettyRef($r['book'], (int) $r['chapter'], (int) $r['verse']))
                ->implode(', ');

            throw ValidationException::withMessages([
                'verses_refs' => "No se encontraron estos versículos en la Biblia: {$citas}. "
                               . 'Quítalos del carrito e intenta de nuevo.',
            ]);
        }

        return [
            'text'        => $result['text'],
            'reference'   => $result['reference'],
            'verses_refs' => $result['resolvedRefs'],
        ];
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
