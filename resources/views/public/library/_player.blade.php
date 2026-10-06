{{-- Reproductor embebido del recurso según §5 (tipo / archivo-vs-enlace / embebible). --}}
@php
    $path = $resource->external_url ? (parse_url($resource->external_url, PHP_URL_PATH) ?: $resource->external_url) : '';
    $extEnlace = strtolower(pathinfo($path, PATHINFO_EXTENSION));
@endphp

@if($resource->type === 'libro')
    @if(!$resource->is_external && $resource->file)
        <iframe src="{{ $resource->file_url }}" class="w-full rounded-lg" style="height:80vh;" title="{{ $resource->title }}"></iframe>
    @elseif($resource->is_external && $extEnlace === 'pdf')
        <iframe src="{{ $resource->external_url }}" class="w-full rounded-lg" style="height:80vh;" title="{{ $resource->title }}"></iframe>
    @else
        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
            <i class="fas fa-book text-4xl mb-3 block"></i>
            <p>Este libro está disponible en un enlace externo.</p>
        </div>
    @endif

@elseif($resource->type === 'video')
    @if(!$resource->is_external && $resource->file)
        <video controls class="w-full rounded-lg">
            <source src="{{ $resource->media_src }}" @if($resource->media_mime) type="{{ $resource->media_mime }}" @endif>
            Tu navegador no soporta la reproducción de video.
        </video>
    @elseif($resource->is_embeddable)
        <div class="aspect-video">
            <iframe src="{{ $resource->embed_url }}" class="w-full h-full rounded-lg" frameborder="0" allowfullscreen title="{{ $resource->title }}"></iframe>
        </div>
    @elseif($resource->is_external && in_array($extEnlace, ['mp4', 'webm', 'ogg', 'mov']))
        <video controls class="w-full rounded-lg">
            <source src="{{ $resource->media_src }}">
            Tu navegador no soporta la reproducción de video.
        </video>
    @else
        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
            <i class="fas fa-video text-4xl mb-3 block"></i>
            <p>Este video está disponible en un enlace externo.</p>
        </div>
    @endif

@elseif($resource->type === 'audio')
    @if(!$resource->is_external && $resource->file)
        <audio controls class="w-full">
            <source src="{{ $resource->media_src }}" @if($resource->media_mime) type="{{ $resource->media_mime }}" @endif>
            Tu navegador no soporta la reproducción de audio.
        </audio>
    @elseif($resource->is_external && in_array($extEnlace, ['mp3', 'wav', 'ogg', 'm4a', 'aac']))
        <audio controls class="w-full">
            <source src="{{ $resource->media_src }}">
            Tu navegador no soporta la reproducción de audio.
        </audio>
    @else
        <div class="text-center py-8 text-slate-500 dark:text-slate-400">
            <i class="fas fa-headphones text-4xl mb-3 block"></i>
            <p>Este audio está disponible en un enlace externo.</p>
        </div>
    @endif
@endif
