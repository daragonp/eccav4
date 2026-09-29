<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PodcastController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user) {
            return view('admin.dashboard');
        } else {
            return view('auth.login');
        }
    }

    public function podcast()
    {
        $category = Category::orderBy('name')->get();
        return view('admin.new-podcast', compact('category'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'audio' => 'required|file|mimes:mp3,wav,ogg,m4a,aac|max:65536',
        ]);

        $podcast = new Podcast();

        if ($request->hasFile('audio')) {
            $audio = $request->file('audio');
            $audioName = time() . '_' . uniqid() . '.' . $audio->getClientOriginalExtension();
            $destination = public_path('audio/podcast');
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true, true);
            }
            $audio->move($destination, $audioName);
            $podcast->audio_file = $audioName;
        }

        $podcast->title = $request->name ?? $request->title ?? 'Episodio ' . date('d/m/Y');
        $podcast->description = $request->description ?? '';
        $podcast->category_id = $request->category ?? $request->category_id;
        $podcast->save();

        return redirect()->back()->with('mensaje', 'El podcast ha sido creado con éxito.');
    }

    public function show(Request $request)
    {
        $search = $request->get('search');
        $categories = Category::withCount('podcast')->orderBy('name')->get();
        
        $podcasts = Podcast::with('category')
            ->when($search, function($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.show-categories', compact('categories', 'podcasts', 'search'));
    }

    public function update($id)
    {
        $podcast = Podcast::findOrFail($id);
        $category = Category::orderBy('name')->get();

        return view('admin.update-podcast', compact('podcast', 'category'));
    }

    public function edit($id, Request $request)
    {
        $podcast = Podcast::findOrFail($id);

        if ($request->hasFile('audio')) {
            $oldPath = public_path('audio/podcast/' . $podcast->audio_file);
            if ($podcast->audio_file && File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $audio = $request->file('audio');
            $audioName = time() . '_' . uniqid() . '.' . $audio->getClientOriginalExtension();
            $destination = public_path('audio/podcast');
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true, true);
            }
            $audio->move($destination, $audioName);
            $podcast->audio_file = $audioName;
        }

        $podcast->title = $request->name ?? $request->title ?? $podcast->title;
        $podcast->description = $request->description ?? $podcast->description;
        if ($request->filled('category') || $request->filled('category_id')) {
            $podcast->category_id = $request->category ?? $request->category_id;
        }

        $podcast->save();

        return redirect()->back()->with('mensaje', 'El podcast ha sido actualizado.');
    }

    public function delete($id)
    {
        $podcast = Podcast::findOrFail($id);
        $filePath = public_path('audio/podcast/' . $podcast->audio_file);
        if ($podcast->audio_file && File::exists($filePath)) {
            File::delete($filePath);
        }

        $podcast->delete();

        return redirect()->back()->with('mensaje', 'El podcast ha sido eliminado.');
    }
}
