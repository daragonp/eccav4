@extends('layouts.main')

@section('title', $resource->title . ' - Biblioteca - Emancipación Cristiana Afro')

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Migas de pan --}}
    <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('library.index') }}" class="hover:text-green-600 dark:hover:text-green-400 transition-colors">
            <i class="fas fa-book-open mr-2"></i>Biblioteca
        </a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-700 dark:text-gray-300">{{ $resource->title }}</span>
    </nav>

    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="p-6 md:p-10">
            {{-- Encabezado --}}
            <div class="mb-6">
                <span class="inline-block bg-green-600 text-white text-xs font-semibold px-3 py-1.5 rounded-full mb-3">
                    {{ ucfirst($resource->type) }}
                </span>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-3">{{ $resource->title }}</h1>
                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                    @if($resource->author)
                        <span class="flex items-center gap-2"><i class="fas fa-user"></i>{{ $resource->author }}</span>
                    @endif
                    @if($resource->category)
                        <span class="flex items-center gap-2"><i class="fas fa-folder"></i>{{ $resource->category->name }}</span>
                    @endif
                    @if($resource->published_at)
                        <span class="flex items-center gap-2"><i class="fas fa-calendar"></i>{{ optional($resource->published_at)->translatedFormat('d \d\e F \d\e Y') }}</span>
                    @endif
                </div>
            </div>

            @if($resource->description)
                <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-600 p-6 rounded-r-lg mb-8">
                    <p class="text-gray-700 dark:text-gray-300">{{ $resource->description }}</p>
                </div>
            @endif

            {{-- Reproductor según tipo --}}
            <div class="mb-8">
                @include('public.library._player', ['resource' => $resource])
            </div>

            {{-- Acciones --}}
            <div class="flex flex-wrap gap-3">
                @if(!$resource->is_external && $resource->file)
                    <a href="{{ route('library.download', $resource->id) }}"
                       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-download"></i>Descargar
                    </a>
                @elseif($resource->is_external && $resource->external_url)
                    <a href="{{ $resource->external_url }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white font-semibold px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-external-link-alt"></i>Ver en origen
                    </a>
                @endif
            </div>
        </div>
    </article>

    {{-- Recursos relacionados --}}
    @if($relatedResources->count())
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Recursos relacionados</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($relatedResources as $related)
                    @php
                        $iconos = ['libro' => 'fa-book', 'video' => 'fa-video', 'audio' => 'fa-headphones'];
                        $icono = $iconos[$related->type] ?? 'fa-file';
                    @endphp
                    <a href="{{ route('library.show', $related->slug) }}"
                       class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all">
                        <div class="relative h-32 overflow-hidden bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center">
                            @if($related->cover_url)
                                <img src="{{ $related->cover_url }}" alt="{{ $related->title }}" class="w-full h-full object-cover">
                            @else
                                <i class="fas {{ $icono }} text-white/40 text-4xl"></i>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2">{{ $related->title }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
