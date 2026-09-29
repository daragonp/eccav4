<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Podcast;
use Illuminate\Http\Request;

class CategoryController extends Controller
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

    public function category()
    {
        return view('admin.new-category');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'subcategory' => 'nullable|string|max:100',
        ]);

        $categoria = new Category();
        $categoria->name = $request->name;
        $categoria->description = $request->description;
        $categoria->subcategory = $request->subcategory;
        $categoria->save();

        return redirect()->back()->with('mensaje', 'La categoría ha sido creada con éxito.');
    }

    public function show(Request $request)
    {
        $search = $request->get('search');
        $activeTab = $request->get('tab', 'podcasts');

        $categories = Category::withCount('podcast')
            ->when($search, function($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%")
                      ->orWhere('subcategory', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->get();

        $podcasts = Podcast::with('category')
            ->when($search, function($query, $search) {
                $query->where('title', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.show-categories', compact('categories', 'podcasts', 'search', 'activeTab'));
    }

    public function update($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.update-category', compact('category'));
    }

    public function edit($id, Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'required|string|max:255',
            'subcategory' => 'nullable|string|max:100',
        ]);

        $category = Category::findOrFail($id);
        $category->name = $request->name;
        $category->description = $request->description;
        $category->subcategory = $request->subcategory;
        $category->save();

        return redirect()->back()->with('mensaje', 'La categoría ha sido actualizada.');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id);

        if ($category->podcast()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar esta categoría porque contiene podcasts asociados. Reasigna o elimina los episodios primero.');
        }

        $category->delete();

        return redirect()->back()->with('mensaje', 'La categoría ha sido eliminada.');
    }
}
