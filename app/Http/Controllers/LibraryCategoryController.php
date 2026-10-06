<?php

namespace App\Http\Controllers;

use App\Models\LibraryCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LibraryCategoryController extends Controller
{
    /** Mensajes de validación en español. */
    protected array $mensajes = [
        'name.required' => 'El nombre de la categoría es obligatorio.',
        'name.max' => 'El nombre no puede superar los 255 caracteres.',
    ];

    /**
     * Listado administrativo de categorías de biblioteca.
     */
    public function show()
    {
        $categories = LibraryCategory::orderBy('name')->paginate(15);
        return view('admin.library.categories', compact('categories'));
    }

    /**
     * Crea una categoría nueva.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ], $this->mensajes);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $category = new LibraryCategory();
        $category->name = $request->input('name');
        $category->slug = $this->uniqueSlug($request->input('name'));
        $category->description = $request->input('description');
        $category->save();

        return back()->with('success', 'La categoría se ha creado.');
    }

    /**
     * Persiste la edición de una categoría.
     */
    public function edit($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
        ], $this->mensajes);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $category = LibraryCategory::findOrFail($id);

        if ($request->input('name') !== $category->name) {
            $category->slug = $this->uniqueSlug($request->input('name'), $category->id);
        }

        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->save();

        return back()->with('success', 'La categoría se ha actualizado.');
    }

    /**
     * Soft delete de una categoría.
     */
    public function destroy($id)
    {
        $category = LibraryCategory::findOrFail($id);
        $category->delete();

        return back()->with('success', 'La categoría ha sido eliminada.');
    }

    /**
     * Genera un slug único (incremental) para el nombre.
     */
    protected function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $count = 1;

        while (
            LibraryCategory::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}
