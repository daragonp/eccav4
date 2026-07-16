@php $showAddButton = true; @endphp

@extends('layouts.panel')

@section('title', 'Culto Dominical')
@section('pageheading', 'Culto Dominical')

@section('addbutton', 'Agregar')
@section('formaction', url('addworship'))

{{-- CAMPOS DEL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="broadcast" class="block text-sm mb-1">Fecha de emisión</label>
      <input id="broadcast" type="date" name="broadcast" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="title" class="block text-sm mb-1">Título (opcional)</label>
      <input id="title" type="text" name="title"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
             placeholder="Si no se proporciona, se generará automáticamente">
    </div>
    <div class="md:col-span-2">
      <label for="abstract" class="block text-sm mb-1">Resumen</label>
      <textarea id="abstract" name="abstract" rows="4"
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
                placeholder="Escribe un resumen del culto. Si no lo proporcionas, se generará automáticamente con IA."></textarea>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        <i class="fas fa-info-circle mr-1"></i> 
        Puedes escribir un resumen manualmente o dejarlo vacío para que la IA lo genere automáticamente
      </p>
    </div>
    <div class="md:col-span-2">
      <label for="image" class="block text-sm mb-1">Imagen</label>
      <input id="image" type="file" name="image" accept="image/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        <i class="fas fa-info-circle mr-1"></i> 
        Puedes subir una imagen manualmente o dejarlo vacío para que la IA genere una automáticamente
      </p>
    </div>
    <div class="md:col-span-2">
      <label for="audio" class="block text-sm mb-1">Audio</label>
      <input id="audio" type="file" name="audio" accept="audio/*" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        <i class="fas fa-microphone mr-1"></i> 
        El audio se procesará con IA para generar contenido automático si no se proporciona resumen o imagen
      </p>
    </div>
  </div>
@endsection
@section('datatable')
  {{-- Buscador y controles --}}
  <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row gap-2 mb-4">
    <div class="relative flex-1">
      <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        placeholder="Buscar cultos por título, autor o etiqueta..." 
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

  <div class="card">
    <div class="card-body p-0">
      <div class="table-wrap">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Título</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Fecha</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Autor</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Etiqueta</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Imagen</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Audio</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado IA</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($worships as $worship)
              @php
                $formattedDate = '';
                if (!empty($worship->broadcast)) {
                    $dt = is_object($worship->broadcast) && method_exists($worship->broadcast, 'format')
                        ? $worship->broadcast
                        : \Carbon\Carbon::parse($worship->broadcast);
                    $formattedDate = $dt->format('d/m/Y');
                }
              @endphp
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">{{ $worship->title }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $formattedDate }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $worship->autor ?? '-' }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                  @if($worship->badge)
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-600">
                      {{ $worship->badge }}
                    </span>
                  @else
                    -
                  @endif
                </td>
                <td class="px-4 py-3 text-sm text-center">
                  @if ($worship->image)
                    <a href="{{ asset('images/worship/' . $worship->image) }}" target="_blank" class="inline-flex">
                      <img src="{{ asset('images/worship/' . $worship->image) }}" alt="{{ $worship->title }}" class="h-10 w-10 object-cover rounded-md ring-1 ring-slate-200 dark:ring-slate-800">
                    </a>
                  @else
                    <span class="text-slate-400"><i class="fas fa-image-slash"></i></span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($worship->audio)
                    <audio controls class="w-40 h-8">
                      <source src="{{ asset('audio/worship/' . $worship->audio) }}">
                    </audio>
                  @else
                    <span class="text-slate-400"><i class="fas fa-volume-mute"></i></span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">
                  @if (!$worship->audio)
                    <span class="chip-brand bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300 border-gray-300 dark:border-gray-700"><i class="fas fa-volume-mute mr-1"></i> Sin audio</span>
                  @elseif ($worship->ai_processed)
                    <span class="chip-brand bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-300 dark:border-blue-700"><i class="fas fa-robot mr-1"></i> Procesado con IA</span>
                  @else
                    <span class="chip-brand bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300 border-yellow-300 dark:border-yellow-700"><i class="fas fa-clock mr-1"></i> Pendiente de IA</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($worship->deleted_at)
                    <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-trash-alt mr-1"></i> Inactivo</span>
                  @else
                    <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm text-center">
                  @include('admin.partials.actions', [
                      'id'           => $worship->id,
                      'view'         => url("view-worship", $worship->id),
                      'activate'     => url("activate-worship", $worship->id),
                      'softdelete'   => url("delete-worship", $worship->id),
                      'realdelete'   => url("realdelete-worship", $worship->id),
                      'reprocess'    => url("reprocess-worship-ai", $worship->id),
                      'formAction'   => url("update-worship", $worship->id),
                      'tableM'       => $worship,
                      'sectionType'  => 'worship',
                      'sectionTitle' => 'Culto Dominical',
                  ])
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="9" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                  <i class="fas fa-church text-5xl mb-4 opacity-50 block"></i>
                  <p class="font-medium">No se encontraron cultos dominicales</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($worships->hasPages())
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      {{ $worships->links() }}
    </div>
  @endif
@endsection