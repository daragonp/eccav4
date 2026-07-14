@php $showAddButton = true; @endphp

@extends('layouts.panel')

@section('title', 'Versículos del día')
@section('pageheading', 'Palabra de vida')

@section('addbutton', 'Agregar')
@section('formaction', url('addverse'))

{{-- CAMPOS DEL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="dateInput" class="block text-sm mb-1">Fecha</label>
      <input id="dateInput" type="date" name="date" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="pdfInput" class="block text-sm mb-1">Documento PDF</label>
      <input id="pdfInput" type="file" name="video" accept=".pdf"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div class="md:col-span-2">
      <label for="imageInput" class="block text-sm mb-1">Imagen</label>
      <input id="imageInput" type="file" name="image" accept="image/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div class="md:col-span-2">
      <label for="audioInput" class="block text-sm mb-1">Audio</label>
      <input id="audioInput" type="file" name="audio" accept="audio/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
  </div>
@endsection

@section('datatable')
  {{-- Buscador --}}
  <form method="GET" action="{{ url()->current() }}" class="flex gap-2 mb-4">
    <div class="relative flex-1">
      <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        placeholder="Buscar palabra de vida por fecha o nombre de imagen..." 
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
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Fecha</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Imagen</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Documento</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Audio</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($verses as $verse)
              @php
                $formattedDate = '';
                if (!empty($verse->date)) {
                    $dt = is_object($verse->date) && method_exists($verse->date, 'format')
                        ? $verse->date
                        : \Carbon\Carbon::parse($verse->date);
                    $formattedDate = $dt->format('d/m/Y');
                }
              @endphp
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">{{ $formattedDate }}</td>
                <td class="px-4 py-3 text-sm text-center">
                  @if ($verse->image)
                    <a href="{{ asset('images/bible/' . $verse->image) }}" target="_blank" class="inline-flex">
                      <img src="{{ asset('images/bible/' . $verse->image) }}" alt="Versículo" class="h-10 w-10 object-cover rounded-md ring-1 ring-slate-200 dark:ring-slate-800">
                    </a>
                  @else
                    <span class="text-slate-400"><i class="fas fa-image-slash"></i></span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($verse->video)
                    <a href="{{ asset('documents/quote/' . $verse->video) }}" target="_blank" class="btn btn-sm btn-ghost text-red-600">
                      <i class="fas fa-file-pdf me-1"></i> Ver PDF
                    </a>
                  @else
                    <span class="text-slate-400"><i class="fas fa-file-pdf"></i> Sin documento</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($verse->audio)
                    <audio controls class="w-40 h-8">
                      <source src="{{ asset('audio/quote/' . $verse->audio) }}">
                    </audio>
                  @else
                    <span class="text-slate-400"><i class="fas fa-volume-mute"></i></span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($verse->deleted_at)
                    <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-trash-alt mr-1"></i> Inactivo</span>
                  @else
                    <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm text-center">
                  @include('admin.partials.actions', [
                      'id'           => $verse->id,
                      'view'         => url("view-quote", $verse->id),
                      'activate'     => url("activate-quote", $verse->id),
                      'softdelete'   => url("delete-quote", $verse->id),
                      'realdelete'   => url("realdelete-quote", $verse->id),
                      'formAction'   => url("update-quote", $verse->id),
                      'tableM'       => $verse,
                      'sectionType'  => 'verse',
                      'sectionTitle' => 'Palabra de vida',
                  ])
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                  <i class="fas fa-quote-left text-5xl mb-4 opacity-50 block"></i>
                  <p class="font-medium">No se encontraron versículos</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($verses->hasPages())
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      {{ $verses->links() }}
    </div>
  @endif
@endsection