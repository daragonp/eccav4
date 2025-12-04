@extends('layouts.main')

@section('title', $worship->title . ' - Emancipación Cristiana Afro')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Navegación de migas de pan -->
    <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="{{ route('worship.public.index') }}" class="hover:text-green-600 dark:hover:text-green-400 transition-colors">
            <i class="fas fa-home mr-2"></i>Cultos
        </a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-700 dark:text-gray-300">{{ $worship->title }}</span>
    </nav>
    
    <!-- Encabezado del artículo -->
    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <!-- Imagen principal a todo el ancho -->
        <div class="relative h-64 md:h-96 overflow-hidden">
            @if($worship->image)
                <img src="{{ asset('images/worship/' . $worship->image) }}" 
                     alt="{{ $worship->title }}" 
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center">
                    <i class="fas fa-church text-white/30 text-8xl"></i>
                </div>
            @endif
            
            <!-- Overlay con información -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            
            <!-- Badge y fecha -->
            <div class="absolute bottom-6 left-6 right-6">
                @if($worship->badge)
                    <span class="inline-block bg-green-600 text-white text-xs font-semibold px-3 py-1.5 rounded-full mb-3">
                        {{ $worship->badge }}
                    </span>
                @endif
                
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ $worship->title }}</h1>
                
                <div class="flex flex-wrap items-center gap-4 text-white/90 text-sm">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-calendar"></i>
                        {{ \Carbon\Carbon::parse($worship->broadcast)->format('d \d\e F \d\e Y') }}
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-user"></i>
                        {{ $worship->autor }}
                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-clock"></i>
                        {{ \Carbon\Carbon::parse($worship->broadcast)->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Contenido del artículo -->
        <div class="p-6 md:p-10">
            <!-- Resumen -->
            @if($worship->abstract)
                <div class="mb-8">
                    <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-600 p-6 rounded-r-lg">
                        <h2 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3 flex items-center gap-2">
                            <i class="fas fa-quote-left"></i>
                            Resumen del mensaje
                        </h2>
                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            {!! $worship->abstract !!}
                        </div>
                    </div>
                </div>
            @endif
            
            <!-- Sección de multimedia -->
            <div class="space-y-8 mb-8">
                <!-- Audio -->
                @if($worship->audio)
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-headphones text-green-600 dark:text-green-400"></i>
                            Escuchar el audio completo
                        </h3>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <audio controls class="w-full">
                                <source src="{{ asset('audio/worship/' . $worship->audio) }}" type="audio/mpeg">
                                Tu navegador no soporta el elemento de audio.
                            </audio>
                        </div>
                    </div>
                @endif
                
                <!-- Video -->
                @if($worship->video)
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-video text-green-600 dark:text-green-400"></i>
                            Ver el video completo
                        </h3>
                        <div class="bg-black rounded-lg overflow-hidden shadow-sm">
                            <video controls class="w-full" poster="{{ $worship->image ? asset('images/worship/' . $worship->image) : '' }}">
                                <source src="{{ asset('video/worship/' . $worship->video) }}" type="video/mp4">
                                Tu navegador no soporta el elemento de video.
                            </video>
                        </div>
                    </div>
                @endif
                
                <!-- PDF -->
                @if($worship->pdfdoc)
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-file-pdf text-red-600"></i>
                            Descargar el documento
                        </h3>
                        <a href="{{ asset('documents/worship/' . $worship->pdfdoc) }}" 
                           target="_blank"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-download"></i>
                            Descargar PDF
                        </a>
                    </div>
                @endif
            </div>
            
            <!-- Botones de acción -->
            <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                    <i class="fas fa-share-alt"></i>
                    Compartir
                </button>
                
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                    <i class="fas fa-bookmark"></i>
                    Guardar
                </button>
                
                <button class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors">
                    <i class="fas fa-print"></i>
                    Imprimir
                </button>
            </div>
        </div>
    </article>
    
    <!-- Navegación entre artículos -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        @if($previous)
            <a href="{{ route('worship.public.show', $previous->slug) }}" 
               class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-green-100 dark:group-hover:bg-green-900/30 transition-colors">
                        <i class="fas fa-chevron-left text-gray-600 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Anterior</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2">
                            {{ $previous->title }}
                        </h3>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($previous->broadcast)->format('d/m/Y') }}
                        </div>
                    </div>
                </div>
            </a>
        @else
            <div></div>
        @endif
        
        @if($next)
            <a href="{{ route('worship.public.show', $next->slug) }}" 
               class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700 text-right">
                <div class="flex items-start gap-4 justify-end">
                    <div class="flex-1">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Siguiente</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2">
                            {{ $next->title }}
                        </h3>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            {{ \Carbon\Carbon::parse($next->broadcast)->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-green-100 dark:group-hover:bg-green-900/30 transition-colors">
                        <i class="fas fa-chevron-right text-gray-600 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400"></i>
                    </div>
                </div>
            </a>
        @else
            <div></div>
        @endif
    </div>
    
    <!-- Sección de relacionados -->
    @if($relatedWorships->isNotEmpty())
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Cultos relacionados</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedWorships as $related)
                    <a href="{{ route('worship.public.show', $related->slug) }}" 
                       class="group bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                        <div class="h-32 overflow-hidden">
                            @if($related->image)
                                <img src="{{ asset('images/worship/' . $related->image) }}" 
                                     alt="{{ $related->title }}" 
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            @else
                                <div class="w-full h-full bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center">
                                    <i class="fas fa-church text-white/30 text-3xl"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2 mb-2">
                                {{ $related->title }}
                            </h3>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                {{ \Carbon\Carbon::parse($related->broadcast)->format('d/m/Y') }}
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection