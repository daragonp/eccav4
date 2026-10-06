@extends('layouts.main')

@section('title', 'Biblioteca - Emancipación Cristiana Afro')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Hero de marca --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-green-700 to-green-500 px-6 py-12 md:py-16 mb-8 text-center">
        <div class="relative z-10">
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">
                <i class="fas fa-book-open mr-2"></i>Biblioteca
            </h1>
            <p class="text-white/90 max-w-2xl mx-auto">
                Libros, videos y audios para tu crecimiento espiritual. Explora, lee, escucha y descarga.
            </p>
        </div>
    </div>

    {{-- Barra de filtros --}}
    <form method="GET" action="{{ route('library.index') }}" class="flex flex-wrap gap-3 mb-8">
        <div class="flex gap-2">
            <a href="{{ route('library.index', array_filter(['category' => request('category')])) }}"
               class="px-4 py-2 rounded-full text-sm font-medium {{ !request('type') ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                Todos
            </a>
            @foreach(['libro' => 'Libros', 'video' => 'Videos', 'audio' => 'Audios'] as $t => $label)
                <a href="{{ route('library.index', array_filter(['type' => $t, 'category' => request('category')])) }}"
                   class="px-4 py-2 rounded-full text-sm font-medium {{ request('type') === $t ? 'bg-green-600 text-white' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
        @if($categories->count())
            <select name="category" onchange="this.form.submit()"
                    class="rounded-full border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                <option value="">Todas las categorías</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
        @endif
    </form>

    {{-- Grid de recursos --}}
    @if($resources->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($resources as $resource)
                @php
                    $iconos = ['libro' => 'fa-book', 'video' => 'fa-video', 'audio' => 'fa-headphones'];
                    $icono = $iconos[$resource->type] ?? 'fa-file';
                @endphp
                <a href="{{ route('library.show', $resource->slug) }}"
                   class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700 hover:shadow-md transition-all">
                    <div class="relative h-44 overflow-hidden bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center">
                        @if($resource->cover_url)
                            <img src="{{ $resource->cover_url }}" alt="{{ $resource->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        @else
                            <i class="fas {{ $icono }} text-white/40 text-5xl"></i>
                        @endif
                        <span class="absolute top-3 left-3 bg-green-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full">
                            {{ ucfirst($resource->type) }}
                        </span>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2">{{ $resource->title }}</h3>
                        <div class="mt-2 flex flex-wrap items-center gap-3 text-xs text-gray-500 dark:text-gray-400">
                            @if($resource->category)
                                <span><i class="fas fa-folder mr-1"></i>{{ $resource->category->name }}</span>
                            @endif
                            @if($resource->author)
                                <span><i class="fas fa-user mr-1"></i>{{ $resource->author }}</span>
                            @endif
                            @if($resource->published_at)
                                <span><i class="fas fa-calendar mr-1"></i>{{ optional($resource->published_at)->format('d/m/Y') }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $resources->links('pagination::tailwind') }}
        </div>
    @else
        {{-- Estado vacío --}}
        <div class="text-center py-20">
            <i class="fas fa-book-open text-6xl text-gray-300 dark:text-gray-600 mb-4 block"></i>
            <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300">No hay recursos disponibles</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-2">Pronto encontrarás libros, videos y audios aquí.</p>
        </div>
    @endif
</div>
@endsection
