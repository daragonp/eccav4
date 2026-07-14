<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {

        //dd($showpost);
        $postpdf = News::where('slug', $slug)->first();
        return view('showpostpdf', compact('postpdf'));
    }

    public function newsweek()
    {

        $news = News::where('category', 1)
            ->orderBy('created_at', 'DESC')
            ->paginate(1);
        return view('newsweek', compact('news'));
    }

    public function history()
    {
        //
        $message = News::orderBy('created_at', 'desc')->get();;

        return view('history', compact('message'));
    }

    public function view($id)
    {
        $news = News::findOrFail($id);

        return view('admin.news.view-news', compact('news'));
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
            'title' => 'required|string|max:255',
            'category' => 'required|integer',
            'autor' => 'required|string|max:30',
            'abstract' => 'nullable|string|max:5000',
            'pdfdoc' => 'required|file|mimes:pdf|max:5120',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg|max:102400',
        ], [
            'title.required' => 'El campo titular de la noticia es obligatorio.',
            'category.required' => 'La categoría es obligatoria.',
            'autor.required' => 'El autor es obligatorio.',
            'pdfdoc.required' => 'El campo documento PDF es obligatorio.',
            'image.required' => 'El campo imagen es obligatorio.',
        ]);

        $slugBase = Str::slug($request->title);
        $slug = $slugBase;
        $counter = 1;

        while (News::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }

        $news = new News();
        $news->category = $request->input('category');
        $news->title = $request->input('title');
        $news->slug = $slug;
        $news->abstract = $request->input('abstract');
        $news->autor = $request->input('autor');

        $pdfdoc = $request->file('pdfdoc');
        if ($pdfdoc && $pdfdoc->isValid()) {
            $docname = time() . '.' . $pdfdoc->getClientOriginalExtension();
            $pdfdoc->storeAs('documents/news', $docname, 'public');
            $news->pdfdoc = $docname;
        }

        $image = $request->file('image');
        if ($image && $image->isValid()) {
            $imgName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('images/news', $imgName, 'public');
            $news->image = $imgName;
        }

        $audio = $request->file('audio');
        if ($audio && $audio->isValid()) {
            $audioName = time() . '.' . $audio->getClientOriginalExtension();
            $audio->storeAs('audio/news', $audioName, 'public');
            $news->audio = $audioName;
        }

        $news->save();

        return redirect()->back()->with('success', 'Los datos han sido guardados exitosamente');
    }

    public function edit($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required|string|max:255',
            'category' => 'required|integer',
            'autor' => 'required|string|max:30',
            'abstract' => 'nullable|string|max:5000',
            'pdfdoc' => 'nullable|file|mimes:pdf|max:5120',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'audio' => 'nullable|file|mimes:mp3,wav,ogg|max:102400',
        ], [
            'title.required' => 'El campo titular de la noticia es obligatorio.',
            'category.required' => 'La categoría es obligatoria.',
            'autor.required' => 'El autor es obligatorio.',
        ]);

        $news = News::findOrFail($id);
        $news->category = $request->input('category');
        $news->title = $request->input('title');

        $slugBase = Str::slug($request->title);
        $slug = $slugBase;
        $counter = 1;
        while (News::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
            $slug = $slugBase . '-' . $counter++;
        }
        $news->slug = $slug;

        $pdfdoc = $request->file('pdfdoc');
        if ($pdfdoc && $pdfdoc->isValid()) {
            $docname = time() . '.' . $pdfdoc->getClientOriginalExtension();
            $pdfdoc->storeAs('documents/news', $docname, 'public');
            $news->pdfdoc = $docname;
        }

        $image = $request->file('image');
        if ($image && $image->isValid()) {
            $imgName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('images/news', $imgName, 'public');
            $news->image = $imgName;
        }

        $audio = $request->file('audio');
        if ($audio && $audio->isValid()) {
            $audioName = time() . '.' . $audio->getClientOriginalExtension();
            $audio->storeAs('audio/news', $audioName, 'public');
            $news->audio = $audioName;
        }

        $news->abstract = $request->input('abstract');
        $news->autor = $request->input('autor');
        $news->save();

        return redirect()->back()->with('success', 'Los datos han sido actualizados');
    }


    /**
     * Display the specified resource with native tables.
     */
    public function show(Request $request)
    {
        $query = News::query()->where('category', 1);

        $user = auth()->user();
        $isSuperAdmin = $user && $user->roles->pluck('name')->map(fn($n) => mb_strtolower($n))->contains('superadministrador');

        if ($isSuperAdmin) {
            $query->withTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%");
            });
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.news.show-news', compact('news'));
    }

    public function look(Request $request)
    {
        $query = News::query()->where('category', 2);

        $user = auth()->user();
        $isSuperAdmin = $user && $user->roles->pluck('name')->map(fn($n) => mb_strtolower($n))->contains('superadministrador');

        if ($isSuperAdmin) {
            $query->withTrashed();
        } else {
            $query->whereNull('deleted_at');
        }

        if ($search = $request->input('search')) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('autor', 'like', "%{$search}%");
            });
        }

        $news = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('admin.news.show-looks', compact('news'));
    }

    public function destroy($id)
    {
        //
        $news = News::findorFail($id);

        $news->deleted_at = Carbon::now();

        $news->save();

        return redirect()->back()->with('success', 'La publicación no está disponible al público');
    }

    public function activate($id)
    {
        //
        $news = News::findorFail($id);

        $news->deleted_at = NULL;

        $news->save();

        return redirect()->back()->with('success', 'La publicación ha sido activada al público');
    }

    public function delete($id)
    {
        $news = News::findOrFail($id);

        $news->delete();

        return redirect()->back()->with('success', 'La publicación ha sido eliminada definitivamente.');
    }

    public function adminindex(Request $request)
    {
        return $this->show($request);
    }

    public function opinion()
    {

        $news = News::where('category', 2)
            ->orderBy('created_at', 'DESC')
            ->paginate(1);
        return view('opinion', compact('news'));
    }
}
