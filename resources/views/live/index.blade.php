@extends('layouts.main')

@section('title', 'Culto en Vivo')

@section('content')
<section aria-labelledby="live-title" class="page-container mb-10">
    <div class="max-w-4xl mx-auto">

        <header class="text-center mb-6">
            <h1 id="live-title" class="text-2xl sm:text-3xl font-black tracking-tight text-gray-900 dark:text-white">
                Culto Dominical en Vivo
            </h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Transmisión en directo del culto dominical de la Iglesia Emancipación Cristiana Afro.
            </p>
        </header>

        @if ($state['is_live'])
            {{-- Indicador EN VIVO --}}
            <div class="flex items-center justify-center gap-2 mb-4">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-sm font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">En vivo ahora</span>
            </div>

            @if (!empty($state['embed_url']))
                {{-- Reproductor embebido, altura fija 16:9 --}}
                <div class="relative w-full rounded-2xl overflow-hidden shadow-lg bg-black" style="aspect-ratio:16/9; min-height:360px;">
                    <iframe
                        src="{{ $state['embed_url'] }}"
                        class="absolute inset-0 w-full h-full"
                        frameborder="0"
                        allow="autoplay; encrypted-media; picture-in-picture"
                        allowfullscreen
                        title="Culto en Vivo"
                        style="border:0;"></iframe>
                </div>
            @else
                <div class="rounded-2xl border border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-950/40 p-6 text-center text-emerald-800 dark:text-emerald-200">
                    La transmisión está en curso. Usa los enlaces de abajo para verla en la plataforma correspondiente.
                </div>
            @endif

            {{-- Enlaces externos a las demás plataformas --}}
            @php
                $redes = [
                    'youtube'   => ['label' => 'YouTube',   'icon' => 'fa-brands fa-youtube'],
                    'facebook'  => ['label' => 'Facebook',  'icon' => 'fa-brands fa-facebook'],
                    'tiktok'    => ['label' => 'TikTok',    'icon' => 'fa-brands fa-tiktok'],
                    'x'         => ['label' => 'X',         'icon' => 'fa-brands fa-x-twitter'],
                    'instagram' => ['label' => 'Instagram', 'icon' => 'fa-brands fa-instagram'],
                ];
            @endphp
            @if (collect($state['watch_urls'])->filter()->isNotEmpty())
                <div class="mt-6">
                    <p class="text-center text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3">
                        Ver también en
                    </p>
                    <div class="flex flex-wrap items-center justify-center gap-3">
                        @foreach ($redes as $clave => $meta)
                            @if (!empty($state['watch_urls'][$clave]))
                                <a href="{{ $state['watch_urls'][$clave] }}" target="_blank" rel="noopener"
                                   class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <i class="{{ $meta['icon'] }}"></i>
                                    {{ $meta['label'] }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        @else
            {{-- Sin transmisión activa --}}
            <div class="rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-8 sm:p-10 text-center shadow-xs">
                <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400">
                    <i class="fa-solid fa-video-slash text-2xl"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-900 dark:text-white">No hay transmisión en vivo en este momento</h2>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    El culto se transmite los domingos. Mientras tanto puedes ver los cultos anteriores.
                </p>
                <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
                    @if (!empty($state['watch_urls']['youtube']) && $state['title'])
                        <a href="{{ $state['watch_urls']['youtube'] }}" target="_blank" rel="noopener"
                           class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <i class="fa-brands fa-youtube"></i> Última grabación
                        </a>
                    @endif
                    <a href="{{ route('worship.public.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 text-white px-4 py-2 text-sm font-bold hover:bg-emerald-700 transition-colors">
                        <i class="fa-solid fa-church"></i> Ver cultos dominicales
                    </a>
                </div>
            </div>
        @endif

    </div>
</section>
@endsection
