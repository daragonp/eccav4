@extends('layouts.panel')

@section('title','Noticias')
@section('pageheading','Mensajes de la Semana')

{{-- Botón crear (abre el modal base del layout) --}}
@section('addbutton')
  Crear mensaje
@endsection

{{-- Modal de creación --}}
@section('modalTitle','Crear nuevo mensaje')
@section('formaction', url('/addnews'))
@section('modalFields')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="title" class="block text-sm font-medium mb-1">Título</label>
      <input id="title" name="title" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="autor" class="block text-sm font-medium mb-1">Autor</label>
      <input id="autor" name="autor" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="broadcast" class="block text-sm font-medium mb-1">Fecha de emisión</label>
      <input id="broadcast" name="broadcast" type="date" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="category" class="block text-sm font-medium mb-1">Categoría (ID)</label>
      <input id="category" name="category" type="number" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" value="1">
    </div>
    <div>
      <label for="pdfdoc" class="block text-sm font-medium mb-1">Documento PDF</label>
      <input id="pdfdoc" name="pdfdoc" type="file" accept="application/pdf" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="image" class="block text-sm font-medium mb-1">Imagen</label>
      <input id="image" name="image" type="file" accept="image/*" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="audio" class="block text-sm font-medium mb-1">Audio (opcional)</label>
      <input id="audio" name="audio" type="file" accept="audio/*" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div class="md:col-span-2">
      <label for="abstract" class="block text-sm font-medium mb-1">Resumen</label>
      <textarea id="abstract" name="abstract" rows="3" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"></textarea>
    </div>
  </div>
@endsection

@section('datatable')
  {{-- Tarjeta de información --}}
  <div class="card mb-6">
    <div class="card-body p-4">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center">
          <i class="fas fa-newspaper text-brand-green"></i>
        </div>
        <div>
          <h3 class="font-semibold text-slate-900 dark:text-white">Mensajes de la Semana</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
            Administra los mensajes semanales de la comunidad. Usa el botón "Crear mensaje" para añadir nuevos contenidos o las acciones por fila para ver, editar o eliminar mensajes existentes.
          </p>
        </div>
      </div>
    </div>
  </div>

  {{-- Buscador --}}
  <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row gap-2 mb-4">
    <div class="relative flex-1">
      <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        placeholder="Buscar mensajes por título o autor..." 
        class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-10 py-2 text-sm text-slate-900 dark:text-white"
      >
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
      </div>
    </div>
    <button type="submit" class="btn btn-secondary">Buscar</button>
    @if(request('search'))
      <a href="{{ url()->current() }}" class="btn btn-ghost">Limpiar</a>
    @endif
  </form>

  {{-- Tarjeta de la tabla --}}
  <div class="card">
    <div class="card-body p-0">
      <div class="table-wrap">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Título</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Autor</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Documento</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Imagen</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Audio</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($news as $item)
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">{{ $item->title }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $item->autor ?? '-' }}</td>
                <td class="px-4 py-3 text-sm">
                  @if ($item->deleted_at)
                    <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-trash-alt mr-1"></i> Inactivo</span>
                  @else
                    <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Publicado</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($item->pdfdoc)
                    <a href="{{ asset('documents/news/' . $item->pdfdoc) }}" target="_blank" class="btn btn-sm btn-ghost text-red-600">
                      <i class="fas fa-file-pdf me-1"></i> Ver PDF
                    </a>
                  @else
                    <span class="text-slate-400"><i class="fas fa-file-pdf"></i> Sin PDF</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm text-center">
                  @if ($item->image)
                    <a href="{{ asset('images/news/' . $item->image) }}" target="_blank" class="inline-flex">
                      <img src="{{ asset('images/news/' . $item->image) }}" alt="{{ $item->title }}" class="h-10 w-10 object-cover rounded-md ring-1 ring-slate-200 dark:ring-slate-800">
                    </a>
                  @else
                    <span class="text-slate-400"><i class="fas fa-image-slash"></i></span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($item->audio)
                    <audio controls class="w-40 h-8">
                      <source src="{{ asset('audio/news/' . $item->audio) }}">
                    </audio>
                  @else
                    <span class="text-slate-400"><i class="fas fa-volume-mute"></i></span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm text-center">
                  @include('admin.partials.actions', [
                      'id'           => $item->id,
                      'view'         => url("view-news", $item->id),
                      'activate'     => url("activate-news", $item->id),
                      'softdelete'   => url("delete-news", $item->id),
                      'realdelete'   => url("realdelete-news", $item->id),
                      'formAction'   => url("update-news", $item->id),
                      'tableM'       => $item,
                      'sectionType'  => 'news',
                      'sectionTitle' => 'Mensaje',
                  ])
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                  <i class="fas fa-newspaper text-5xl mb-4 opacity-50 block"></i>
                  <p class="font-medium">No se encontraron mensajes</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($news->hasPages())
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      {{ $news->links() }}
    </div>
  @endif
@endsection