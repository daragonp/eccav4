<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\DataTables\NewsDataTable;
use App\DataTables\LookDataTable;

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
        //
        $news = News::findorFail($id);

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
            'title' => 'required',
            'pdfdoc' => 'required',
            'image' => 'required',
        ], [
            'date.required' => 'El campo títular de la noticia es obligatorio.',
            'pdfdoc.required' => 'El campo documento pdf es obligatorio.',
            'image.required' => 'El campo imagen es obligatorio.',
        ]);

        $news = new News();

        $news->category = $request->input('category');

        $news->title = $request->input('title');

        $slug = Str::slug($request->title);
        $str = preg_replace('/[^a-z0-9]/', '-', $slug);
        $news->slug = $str;

        $document = $request->pdfdoc;

        if ($document) {

            $docname = time() . '.' . $document->getClientOriginalExtension();

            $request->pdfdoc->move('documents/news', $docname);

            $news->pdfdoc = $docname;
        }

        $image = $request->image;

        if ($image) {

            $imgName = time() . '.' . $image->getClientOriginalExtension();

            $request->image->move('images/news', $imgName);

            $news->image = $imgName;
        }

        $audio = $request->audio;

        if ($audio) {

            $audioName = time() . '.' . $audio->getClientOriginalExtension();

            $request->audio->move('audio/news', $audioName);

            $news->audio = $audioName;
        }

        $news->abstract = $request->input('abstract');

        $news->autor = $request->input('autor');

        $news->created_at = Carbon::now();

        $news->updated_at = Carbon::now();

        $news->save();

        return redirect()->back()->with('success', 'Los datos han sido guardados exitosamente');
    }

    public function edit($id, Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'autor' => 'required',
        ], [
            'date.required' => 'El campo títular de la noticia es obligatorio.',
            'autor.required' => 'El campo autor pdf es obligatorio.',
        ]);

        $news = News::findorFail($id);

        $news->category = $request->input('category');

        $news->title = $request->input('title');

        $slug = Str::slug($request->title);
        $str = preg_replace('/[^a-z0-9]/', '-', $slug);
        $news->slug = $str;

        $document = $request->pdfdoc;

        if ($document) {

            $docname = time() . '.' . $document->getClientOriginalExtension();

            $request->pdfdoc->move('documents/news', $docname);

            $news->pdfdoc = $docname;
        }

        $image = $request->image;

        if ($image) {

            $imgName = time() . '.' . $image->getClientOriginalExtension();

            $request->image->move('images/news', $imgName);

            $news->image = $imgName;
        }

        $audio = $request->audio;

        if ($audio) {

            $audioName = time() . '.' . $audio->getClientOriginalExtension();

            $request->audio->move('audio/news', $audioName);

            $news->audio = $audioName;
        }

        $news->abstract = $request->input('abstract');

        $news->autor = $request->input('autor');

        $news->created_at = Carbon::now();

        $news->updated_at = Carbon::now();

        $news->save();

        return redirect()->back()->with('success', 'Los datos han sido actualizados');
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function show(NewsDataTable $dataTable)
    {

        return $dataTable->render('admin.news.show-news');
    }

    public function look(LookDataTable $dataTable)
    {

        return $dataTable->render('admin.news.show-looks');
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
        //
        $news = news::findorFail($id);

        $news->delete();

        return redirect()->back()->with('success', 'La publicación ha sido eliminada definitivamente.');
    }


    public function opinion()
    {

        $news = News::where('category', 2)
            ->orderBy('created_at', 'DESC')
            ->paginate(1);
        return view('opinion', compact('news'));
    }
}
