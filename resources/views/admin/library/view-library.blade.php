@extends('layouts.panel')

@section('title', 'Recurso de Biblioteca')
@section('pageheading', $resource->title)

@section('datatable')
  <div class="card">
    <div class="card-body space-y-6">
      <div class="flex items-start gap-4">
        @if($resource->cover_url)
          <img src="{{ $resource->cover_url }}" alt="{{ $resource->title }}" class="h-28 w-28 object-cover rounded-xl ring-1 ring-slate-200 dark:ring-slate-700">
        @else
          <div class="h-28 w-28 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
            <i class="fas fa-book-open text-3xl"></i>
          </div>
        @endif
        <div class="flex-1">
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ $resource->title }}</h2>
          <div class="mt-2 flex flex-wrap gap-2 text-sm">
            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-slate-100 dark:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-200 dark:border-slate-600">{{ ucfirst($resource->type) }}</span>
            @if($resource->category)
              <span class="text-slate-600 dark:text-slate-300"><i class="fas fa-folder mr-1"></i>{{ $resource->category->name }}</span>
            @endif
            @if($resource->author)
              <span class="text-slate-600 dark:text-slate-300"><i class="fas fa-user mr-1"></i>{{ $resource->author }}</span>
            @endif
          </div>
          <div class="mt-2 text-sm text-slate-500 dark:text-slate-400">
            @if($resource->published_at)
              <span><i class="fas fa-calendar mr-1"></i>{{ optional($resource->published_at)->translatedFormat('d \d\e F \d\e Y') }}</span>
            @endif
            <span class="ml-3">
              @if($resource->deleted_at)
                <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700">Eliminado</span>
              @else
                {{ $resource->status_formatted }}
              @endif
            </span>
          </div>
        </div>
      </div>

      @if($resource->description)
        <p class="text-slate-700 dark:text-slate-300">{{ $resource->description }}</p>
      @endif

      {{-- Reproductor según tipo --}}
      <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-4">
        @include('public.library._player', ['resource' => $resource])
      </div>

      <div class="flex gap-3">
        @if(!$resource->is_external && $resource->file)
          <a href="{{ route('library.download', $resource->id) }}" class="btn btn-primary"><i class="fas fa-download mr-2"></i>Descargar</a>
        @elseif($resource->is_external && $resource->external_url)
          <a href="{{ $resource->external_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary"><i class="fas fa-external-link-alt mr-2"></i>Ver en origen</a>
        @endif
        <a href="{{ url('show-library') }}" class="btn btn-ghost">Volver</a>
      </div>
    </div>
  </div>
@endsection
