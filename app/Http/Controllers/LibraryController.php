<?php

namespace App\Http\Controllers;

use App\Models\LibraryCategory;
use App\Models\LibraryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LibraryController extends Controller
{
    /** Mensajes de validación en español reutilizados por store/edit. */
    protected array $mensajes = [
        'title.required' => 'El título es obligatorio.',
        'type.required' => 'El tipo de recurso es obligatorio.',
        'type.in' => 'El tipo de recurso no es válido.',
        'category_id.exists' => 'La categoría seleccionada no existe.',
        'external_url.required' => 'Debe proporcionar un enlace externo.',
        'external_url.url' => 'El enlace externo no es una URL válida.',
        'file.required' => 'Debe subir un archivo.',
        'file.max' => 'El archivo no puede superar los 100 MB.',
        'file.mimes' => 'El formato del archivo no es válido para este tipo de recurso.',
        'cover.image' => 'La portada debe ser una imagen.',
        'cover.mimes' => 'La portada debe ser JPG, PNG o WebP.',
        'cover.max' => 'La portada no puede superar los 4 MB.',
    ];

    // ==================================================================
    // ADMIN
    // ==================================================================

    /**
     * Listado administrativo de recursos (incluye eliminados).
     */
    public function show(Request $request)
    {
        $query = LibraryResource::withTrashed()->with('category');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($categoryId = $request->input('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $resources = $query->latest()->paginate(15)->withQueryString();
        $categories = LibraryCategory::orderBy('name')->get();

        return view('admin.library.show-library', compact('resources', 'categories'));
    }

    /**
     * Crea un recurso nuevo.
     */
    public function store(Request $request)
    {
        if ($redirect = $this->detectPostMaxSizeExceeded($request)) {
            return $redirect;
        }

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, true),
            $this->mensajes
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $resource = new LibraryResource();
        $resource->title = $request->input('title');
        $resource->slug = $this->uniqueSlug($request->input('title'));
        $resource->description = $request->input('description');
        $resource->type = $request->input('type');
        $resource->category_id = $request->input('category_id') ?: null;
        $resource->author = $request->input('author');
        $resource->published_at = $request->input('published_at') ?: null;
        $resource->published = $request->boolean('published');

        try {
            // Portada opcional
            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                $resource->cover = $this->storeUploadedFile($request->file('cover'), 'covers');
            }

            // Resolución del medio (archivo vs enlace)
            if ($request->input('media_mode') === 'enlace') {
                $resource->external_url = $request->input('external_url');
                $resource->file = null;
            } else { // archivo
                if ($request->hasFile('file') && $request->file('file')->isValid()) {
                    $resource->file = $this->storeUploadedFile($request->file('file'), 'files');
                    $resource->external_url = null;
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error al guardar archivo de biblioteca: ' . $e->getMessage());
            return back()->withErrors(['error' => 'No se pudo guardar el archivo. Intente nuevamente.'])->withInput();
        }

        // Invariante final: exactamente uno de file / external_url.
        if (empty($resource->file) && empty($resource->external_url)) {
            return back()->withErrors(['media' => 'Debe subir un archivo o proporcionar un enlace externo.'])->withInput();
        }

        $resource->save();

        return redirect('show-library')->with('success', 'El recurso se ha agregado a la Biblioteca.');
    }

    /**
     * Muestra el detalle administrativo de un recurso.
     */
    public function view($id)
    {
        $resource = LibraryResource::withTrashed()->findOrFail($id);
        return view('admin.library.view-library', compact('resource'));
    }

    /**
     * Persiste la edición de un recurso (recibe el POST del modal universal).
     */
    public function edit($id, Request $request)
    {
        if ($redirect = $this->detectPostMaxSizeExceeded($request)) {
            return $redirect;
        }

        $validator = Validator::make(
            $request->all(),
            $this->rules($request, false),
            $this->mensajes
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $resource = LibraryResource::withTrashed()->findOrFail($id);

        // Recalcular slug solo si cambió el título.
        if ($request->input('title') !== $resource->title) {
            $resource->slug = $this->uniqueSlug($request->input('title'), $resource->id);
        }

        $resource->title = $request->input('title');
        $resource->description = $request->input('description');
        $resource->type = $request->input('type');
        $resource->category_id = $request->input('category_id') ?: null;
        $resource->author = $request->input('author');
        $resource->published_at = $request->input('published_at') ?: null;
        $resource->published = $request->boolean('published');

        try {
            // Portada opcional (vacío = conservar)
            if ($request->hasFile('cover') && $request->file('cover')->isValid()) {
                $this->deleteFileIfExists('covers', $resource->cover);
                $resource->cover = $this->storeUploadedFile($request->file('cover'), 'covers');
            }

            // Resolución explícita de modo archivo-vs-enlace (§8.1)
            if ($request->input('media_mode') === 'enlace') {
                $resource->external_url = $request->input('external_url');
                if (!empty($resource->file)) {
                    $this->deleteFileIfExists('files', $resource->file);
                    $resource->file = null;
                }
            } else { // archivo
                if ($request->hasFile('file') && $request->file('file')->isValid()) {
                    $nuevo = $this->storeUploadedFile($request->file('file'), 'files');
                    if (!empty($resource->file)) {
                        $this->deleteFileIfExists('files', $resource->file);
                    }
                    $resource->file = $nuevo;
                    $resource->external_url = null;
                } elseif (empty($resource->file)) {
                    return back()->withErrors(['file' => 'Debe subir un archivo.'])->withInput();
                } else {
                    // Conservar el archivo actual y normalizar la invariante.
                    $resource->external_url = null;
                }
            }
        } catch (\Throwable $e) {
            Log::error('Error al actualizar archivo de biblioteca (id ' . $resource->id . '): ' . $e->getMessage());
            return back()->withErrors(['error' => 'No se pudo guardar el archivo. Intente nuevamente.'])->withInput();
        }

        // Invariante final.
        if (empty($resource->file) && empty($resource->external_url)) {
            return back()->withErrors(['media' => 'Debe subir un archivo o proporcionar un enlace externo.'])->withInput();
        }

        $resource->save();

        return back()->with('success', 'El recurso de la Biblioteca se ha actualizado.');
    }

    /**
     * Alterna la publicación sin tocar el estado de borrado.
     */
    public function toggleActive($id)
    {
        $resource = LibraryResource::withTrashed()->findOrFail($id);
        $resource->published = !$resource->published;
        $resource->save();

        $estado = $resource->published ? 'publicado' : 'despublicado';
        return back()->with('success', "El recurso ha sido {$estado}.");
    }

    /**
     * Soft delete.
     */
    public function destroy($id)
    {
        $resource = LibraryResource::findOrFail($id);
        $resource->delete();

        return back()->with('success', 'El recurso ha sido eliminado de la gestión.');
    }

    /**
     * Restaura un recurso eliminado.
     */
    public function activate($id)
    {
        $resource = LibraryResource::withTrashed()->findOrFail($id);
        $resource->restore();

        return back()->with('success', 'El recurso ha sido restaurado.');
    }

    /**
     * Eliminación permanente (incluye archivos físicos).
     */
    public function delete($id)
    {
        $resource = LibraryResource::withTrashed()->findOrFail($id);

        $this->deleteFileIfExists('files', $resource->file);
        $this->deleteFileIfExists('covers', $resource->cover);

        $resource->forceDelete();

        return back()->with('success', 'El recurso ha sido eliminado definitivamente.');
    }

    // ==================================================================
    // PÚBLICO
    // ==================================================================

    /**
     * Galería pública filtrable.
     */
    public function publicIndex(Request $request)
    {
        $query = LibraryResource::where('published', true)->with('category');

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        if ($category = $request->input('category')) {
            $query->whereHas('category', function ($q) use ($category) {
                $q->where('slug', $category)->orWhere('id', $category);
            });
        }

        $resources = $query->latest('published_at')->latest()->paginate(12)->withQueryString();
        $categories = LibraryCategory::orderBy('name')->get();

        return view('public.library.index', compact('resources', 'categories'));
    }

    /**
     * Detalle público de un recurso publicado y no eliminado.
     */
    public function publicShow($slug)
    {
        $resource = LibraryResource::where('slug', $slug)
            ->where('published', true)
            ->firstOrFail();

        $relatedResources = LibraryResource::where('published', true)
            ->where('id', '!=', $resource->id)
            ->where(function ($q) use ($resource) {
                $q->where('type', $resource->type);
                if ($resource->category_id) {
                    $q->orWhere('category_id', $resource->category_id);
                }
            })
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('public.library.show', compact('resource', 'relatedResources'));
    }

    /**
     * Descarga libre (sin login) del archivo físico de un recurso.
     */
    public function download($id)
    {
        $resource = LibraryResource::findOrFail($id);

        abort_if(empty($resource->file), 404);
        abort_unless(file_exists(public_path('library/files/' . $resource->file)), 404);

        return response()->download(public_path('library/files/' . $resource->file));
    }

    // ==================================================================
    // Helpers
    // ==================================================================

    /**
     * Reglas de validación comunes y de medio según el modo.
     */
    protected function rules(Request $request, bool $isStore): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:libro,video,audio',
            'category_id' => 'nullable|exists:library_categories,id',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'description' => 'nullable|string|max:5000',
            'published' => 'nullable|boolean',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ];

        $mimesPorTipo = [
            'libro' => 'mimes:pdf',
            'video' => 'mimes:mp4,webm,mov,ogg',
            'audio' => 'mimes:mp3,wav,ogg,m4a,aac',
        ];
        $tipo = $request->input('type');
        $mime = $mimesPorTipo[$tipo] ?? '';

        if ($request->input('media_mode') === 'enlace') {
            $rules['external_url'] = $isStore ? 'required|url' : 'nullable|url';
        } else { // archivo
            $base = $isStore ? 'required|file' : 'nullable|file';
            $rules['file'] = trim($base . '|' . $mime . '|max:102400', '|');
        }

        return $rules;
    }

    /**
     * Detecta un POST que excedió post_max_size (PHP vacía $_POST/$_FILES).
     */
    protected function detectPostMaxSizeExceeded(Request $request)
    {
        if (empty($request->all()) && (int) $request->server('CONTENT_LENGTH') > 0) {
            return back()->withErrors(['file' => 'El archivo excede el tamaño permitido por el servidor.'])->withInput();
        }

        return null;
    }

    /**
     * Genera un slug único (incremental) para el título.
     */
    protected function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $count = 1;

        while (
            LibraryResource::withTrashed()
                ->where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Guarda un archivo subido en public/library/{$dir} y devuelve su nombre.
     */
    protected function storeUploadedFile($file, string $dir): string
    {
        $target = public_path('library/' . $dir);
        if (!file_exists($target)) {
            mkdir($target, 0775, true);
        }

        $name = uniqid('lib_') . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move($target, $name);

        return $name;
    }

    /**
     * Borra un archivo físico si existe.
     */
    protected function deleteFileIfExists(string $dir, ?string $name): void
    {
        if (empty($name)) {
            return;
        }

        $path = public_path('library/' . $dir . '/' . $name);
        if (file_exists($path)) {
            unlink($path);
        }
    }
}
