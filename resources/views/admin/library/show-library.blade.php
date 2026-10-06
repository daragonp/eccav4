@php $showAddButton = true; @endphp

@extends('layouts.panel')

@section('title', 'Biblioteca')
@section('pageheading', 'Biblioteca')

@section('addbutton', 'Agregar recurso')
@section('formaction', url('addlibrary'))
@section('modalTitle', 'Agregar recurso a la Biblioteca')

{{-- CAMPOS DEL MODAL PARA AGREGAR UN RECURSO --}}
@section('modalFields')
  @php $cats = \App\Models\LibraryCategory::orderBy('name')->get(); @endphp
  <div id="library-edit-fields" data-require-media="1" class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- 1. Título --}}
    <div>
      <label for="library_title" class="block text-sm mb-1">Título</label>
      <input id="library_title" type="text" name="title" value="{{ old('title') }}" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    {{-- 2. Autor --}}
    <div>
      <label for="library_author" class="block text-sm mb-1">Autor</label>
      <input id="library_author" type="text" name="author" value="{{ old('author') }}"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    {{-- 3. Categoría --}}
    <div>
      <label for="library_category_id" class="block text-sm mb-1">Categoría</label>
      <select id="library_category_id" name="category_id"
              class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
        <option value="">Sin categoría</option>
        @foreach($cats as $cat)
          <option value="{{ $cat->id }}" {{ (string) old('category_id') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
        @endforeach
      </select>
    </div>
    {{-- 4. Fecha de publicación --}}
    <div>
      <label for="library_published_at" class="block text-sm mb-1">Fecha de publicación</label>
      <input id="library_published_at" type="date" name="published_at" value="{{ old('published_at') }}"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    {{-- 5. Descripción --}}
    <div class="md:col-span-2">
      <label for="library_description" class="block text-sm mb-1">Descripción</label>
      <textarea id="library_description" name="description" rows="3"
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">{{ old('description') }}</textarea>
    </div>
    {{-- 6. Tipo --}}
    <div>
      <label for="library_type" class="block text-sm mb-1">Tipo de recurso</label>
      <select id="library_type" name="type"
              class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
        <option value="libro" {{ old('type', 'libro') === 'libro' ? 'selected' : '' }}>Libro</option>
        <option value="video" {{ old('type') === 'video' ? 'selected' : '' }}>Video</option>
        <option value="audio" {{ old('type') === 'audio' ? 'selected' : '' }}>Audio</option>
      </select>
    </div>
    {{-- 7. Modo del medio --}}
    <div>
      <label for="library_media_mode" class="block text-sm mb-1">Origen del medio</label>
      <select id="library_media_mode" name="media_mode"
              class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
        <option value="archivo" {{ old('media_mode', 'archivo') === 'archivo' ? 'selected' : '' }}>Archivo subido</option>
        <option value="enlace" {{ old('media_mode') === 'enlace' ? 'selected' : '' }}>Enlace externo</option>
      </select>
    </div>
    {{-- 7a. Input archivo --}}
    <div data-media-input="archivo" class="md:col-span-2 {{ old('media_mode', 'archivo') === 'archivo' ? '' : 'hidden' }}">
      <label for="library_file" class="block text-sm mb-1">Archivo</label>
      <input id="library_file" type="file" name="file"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    {{-- 7b. Input enlace --}}
    <div data-media-input="enlace" class="md:col-span-2 {{ old('media_mode') === 'enlace' ? '' : 'hidden' }}">
      <label for="library_external_url" class="block text-sm mb-1">Enlace externo</label>
      <input id="library_external_url" type="url" name="external_url" value="{{ old('external_url') }}" placeholder="https://..."
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    {{-- 8. Portada --}}
    <div class="md:col-span-2">
      <label for="library_cover" class="block text-sm mb-1">Portada (opcional)</label>
      <input id="library_cover" type="file" name="cover" accept="image/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    {{-- 9. Publicado --}}
    <div class="md:col-span-2">
      <label class="inline-flex items-center gap-2 text-sm">
        <input id="library_published" type="checkbox" name="published" value="1" {{ old('published', '1') ? 'checked' : '' }}
               class="rounded border-slate-300 dark:border-slate-700 text-emerald-600 focus:ring-emerald-500">
        Publicado (visible al público)
      </label>
    </div>
    {{-- 10. Aviso de tamaño --}}
    <div class="md:col-span-2">
      <p class="text-xs text-amber-600 dark:text-amber-400">
        <i class="fas fa-triangle-exclamation mr-1"></i>
        Tamaño máximo de archivo: ~100 MB (102400 KB). No suba archivos que superen ese límite.
      </p>
    </div>
  </div>

  {{-- IIFE de alternancia (patrón slider), compartida con el modal de edición --}}
  <script>
  (function () {
      var typeSel   = document.getElementById('library_type');
      var modeSel   = document.getElementById('library_media_mode');
      var fileInput = document.getElementById('library_file');
      if (!typeSel || !modeSel) return;
      var urlInput  = document.getElementById('library_external_url');

      function syncMode() {
          ['archivo', 'enlace'].forEach(function (kind) {
              var box = document.querySelector('#library-edit-fields [data-media-input="' + kind + '"]');
              if (box) box.classList.toggle('hidden', modeSel.value !== kind);
          });
          var requireMedia = (document.getElementById('library-edit-fields')
                              && document.getElementById('library-edit-fields').dataset.requireMedia === '1');
          if (fileInput) fileInput.required = requireMedia && modeSel.value === 'archivo';
          if (urlInput)  urlInput.required  = requireMedia && modeSel.value === 'enlace';
      }
      function syncAccept() {
          if (!fileInput) return;
          var map = { libro: '.pdf', video: 'video/*', audio: 'audio/*' };
          fileInput.setAttribute('accept', map[typeSel.value] || '*/*');
      }
      typeSel.addEventListener('change', syncAccept);
      modeSel.addEventListener('change', syncMode);
      syncMode(); syncAccept();
  })();
  </script>
@endsection

@section('datatable')
  {{-- Buscador y filtros --}}
  <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row gap-2 mb-4">
    <div class="relative flex-1">
      <input type="text" name="search" value="{{ request('search') }}"
             placeholder="Buscar por título, autor o descripción..."
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-10 py-2 text-sm text-slate-900 dark:text-white">
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
      </div>
    </div>
    <select name="type" class="rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm">
      <option value="">Todos los tipos</option>
      <option value="libro" {{ request('type') === 'libro' ? 'selected' : '' }}>Libros</option>
      <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Videos</option>
      <option value="audio" {{ request('type') === 'audio' ? 'selected' : '' }}>Audios</option>
    </select>
    <select name="category_id" class="rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm">
      <option value="">Todas las categorías</option>
      @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ (string) request('category_id') === (string) $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
      @endforeach
    </select>
    <button type="submit" class="btn btn-secondary">Filtrar</button>
    @if(request('search') || request('type') || request('category_id'))
      <a href="{{ url()->current() }}" class="btn btn-ghost">Limpiar</a>
    @endif
  </form>

  <div class="card">
    <div class="card-body p-0">
      <div class="table-wrap">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Portada</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Título</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Tipo</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Categoría</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Origen</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Publicar</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($resources as $resource)
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-center">
                  @if ($resource->cover_url)
                    <img src="{{ $resource->cover_url }}" alt="{{ $resource->title }}" class="h-10 w-10 object-cover rounded-md ring-1 ring-slate-200 dark:ring-slate-800 mx-auto">
                  @else
                    <span class="text-slate-400"><i class="fas fa-image"></i></span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">{{ $resource->title }}</td>
                <td class="px-4 py-3 text-sm">
                  <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-600">
                    {{ ucfirst($resource->type) }}
                  </span>
                </td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ optional($resource->category)->name ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                  {{ $resource->is_external ? 'Enlace' : 'Archivo' }}
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($resource->deleted_at)
                    <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-trash-alt mr-1"></i> Eliminado</span>
                  @elseif ($resource->published)
                    <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Publicado</span>
                  @else
                    <span class="chip-brand bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border-yellow-300 dark:border-yellow-700"><i class="fas fa-eye-slash mr-1"></i> Despublicado</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-center">
                  <form action="{{ url('toggle-library', $resource->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="{{ $resource->published ? 'Despublicar' : 'Publicar' }}"
                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $resource->published ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400' : 'bg-slate-50 text-slate-500 hover:bg-slate-100 dark:bg-slate-800 dark:text-slate-400' }} transition-all duration-200">
                      <i class="fa-solid {{ $resource->published ? 'fa-toggle-on' : 'fa-toggle-off' }} text-sm"></i>
                    </button>
                  </form>
                </td>
                <td class="px-4 py-3 text-sm text-center">
                  @include('admin.partials.actions', [
                      'id'           => $resource->id,
                      'view'         => url('view-library', $resource->id),
                      'activate'     => url('activate-library', $resource->id),
                      'softdelete'   => url('delete-library', $resource->id),
                      'realdelete'   => url('realdelete-library', $resource->id),
                      'formAction'   => url('update-library', $resource->id),
                      'tableM'       => $resource,
                      'sectionType'  => 'library',
                      'sectionTitle' => 'Recurso de Biblioteca',
                      'activeAxis'   => 'trashed',
                  ])
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="8" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                  <i class="fas fa-book-open text-5xl mb-4 opacity-50 block"></i>
                  <p class="font-medium">No se encontraron recursos en la Biblioteca</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($resources->hasPages())
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      {{ $resources->links() }}
    </div>
  @endif

  {{-- Reapertura del modal correcto tras un fallo de validación (§8.2.1) --}}
  @if ($errors->any() && old('title') !== null)
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      var editId = @json(old('_library_edit_id'));
      if (editId) {
        if (typeof window.openDynamicEditModal === 'function') window.openDynamicEditModal('library', editId);
      } else if (typeof window.openModal === 'function') {
        window.openModal('addModal');
      }
    });
  </script>
  @endif
@endsection
