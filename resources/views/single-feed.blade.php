@extends('layouts.main')

@section('title', 'Versículo del día')

@push('head')
  <style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
  <div class="page-container space-y-6">
    {{-- Encabezado --}}
    <header class="text-center">
      <h1 class="page-title">Palabra de Vida</h1>
      <div class="hr-brand mx-auto"></div>
      <p class="page-subtle">
        {{ \Carbon\Carbon::parse($verse->date)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
      </p>
    </header>

    {{-- Contenido principal --}}
    <section class="panel">
      <div class="mx-auto max-w-3xl text-center">
        {{-- Imagen con Lightbox --}}
        <a href="{{ asset('images/bible/' . $verse->image) }}"
           data-turbo="false" {{-- evita que Turbo navegue y deja actuar a Lightbox --}}
           data-lightbox="verse-image"
           data-title="Derechos Reservados E.C.C.A. — {{ \Carbon\Carbon::parse($verse->date)->format('d/m/Y') }}">
          <img
            src="{{ asset('images/bible/' . $verse->image) }}"
            alt="Versículo del día"
            class="mx-auto rounded-md shadow border-2 border-[var(--green-dark)] dark:border-[var(--green-light)]
                   max-h-[70svh] w-auto transition-transform duration-200 hover:scale-[1.01]"
            loading="lazy" decoding="async"
          />
        </a>

        {{-- PDF embebido o aviso --}}
        <div class="mt-6 text-left">
          @if ($verse->video)
            <div class="rounded-xl ring-1 ring-gray-200 dark:ring-gray-800 overflow-hidden bg-white dark:bg-gray-900">
              <iframe
                src="{{ asset('documents/quote/' . $verse->video) }}"
                class="w-full h-[80svh] min-h-[560px]"
                style="border:0"
                loading="lazy"
                allowfullscreen
                title="Documento del versículo (PDF)">
              </iframe>
            </div>

            {{-- Acciones PDF --}}
            <div class="mt-3 flex flex-wrap items-center justify-center gap-2">
              <a href="{{ asset('documents/quote/' . $verse->video) }}"
                 download
                 class="chip">
                <i class="fa-solid fa-download"></i>
                Descargar PDF
              </a>
              <a href="{{ asset('documents/quote/' . $verse->video) }}"
                 target="_blank" rel="noopener"
                 class="chip">
                <i class="fa-solid fa-up-right-from-square"></i>
                Abrir en pestaña nueva
              </a>
            </div>
          @else
            <div class="mt-4 rounded-lg border border-yellow-300/60 bg-yellow-50 text-yellow-900
                        dark:bg-yellow-900/20 dark:text-yellow-200 dark:border-yellow-700/50 p-4 text-center">
              Documento PDF no disponible para esta fecha.
            </div>
          @endif
        </div>

        {{-- Botón de regreso --}}
        @php
          // Fallback por si no hay "previous" válido
          $backUrl = url()->previous();
          $isSame = request()->fullUrlIs($backUrl);
          if ($isSame || empty($backUrl)) {
            $backUrl = url('worship-home'); // ruta de listado/historial
          }
        @endphp
        <div class="mt-8">
          <a href="{{ $backUrl }}"
             class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm
                    border-black/20 hover:bg-black/5 transition
                    focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--green-light)]
                    focus-visible:ring-offset-2 focus-visible:ring-offset-white
                    dark:border-white/20 dark:hover:bg-white/10 dark:focus-visible:ring-offset-gray-900">
            <i class="fa-solid fa-arrow-left"></i>
            Volver
          </a>
        </div>
      </div>
    </section>
  </div>
@endsection

@push('scripts')
  <script>
    // Reaplica opciones de Lightbox también tras navegaciones Turbo
    function setupLightbox(){
      if (window.lightbox && typeof lightbox.option === 'function') {
        lightbox.option({
          fadeDuration: 200,
          resizeDuration: 200,
          wrapAround: true,
          disableScrolling: true,
          positionFromTop: 50
        });
      }
    }
    document.addEventListener('DOMContentLoaded', setupLightbox);
    document.addEventListener('turbo:load', setupLightbox);
  </script>
@endpush
