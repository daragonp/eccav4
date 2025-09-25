@extends('layouts.main')

@section('title', 'Política de privacidad')

{{-- Cabecera --}}
@section('page-hero')
  <div>
    <h1 class="page-title">Política de privacidad y tratamiento de datos</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Consulta el documento oficial en formato PDF.</p>
  </div>
@endsection

@section('content')
  @php
    $pdfViewer = asset('/laraview/#../documents/ecca/EccaPTDPv1.pdf');
    $pdfFile   = asset('documents/ecca/EccaPTDPv1.pdf');
  @endphp

  <section class="panel space-y-4">

    {{-- Acciones / utilidades --}}
    <div class="flex flex-wrap items-center justify-between gap-3">
      <p class="page-subtle m-0">
        Si tienes problemas para visualizar, puedes abrir o descargar el PDF.
      </p>
      <div class="flex flex-wrap gap-2">
        <a href="{{ $pdfFile }}" target="_blank" rel="noopener" class="chip">
          <i class="fa-solid fa-up-right-from-square"></i> Abrir en pestaña nueva
        </a>
        <a href="{{ $pdfFile }}" download class="chip">
          <i class="fa-solid fa-download"></i> Descargar PDF
        </a>
      </div>
    </div>

    {{-- Visor del documento (mantiene tu laraview) --}}
    <div class="rounded-xl overflow-hidden ring-1 ring-gray-200 dark:ring-gray-800 bg-white dark:bg-gray-900">
      <iframe
        src="{{ $pdfViewer }}"
        title="Política de privacidad (PDF)"
        class="w-full h-[85dvh] md:h-[400dvh] min-h-[720px]"
        loading="lazy"
        allowfullscreen
      ></iframe>
    </div>

    {{-- Fallback si el visor no carga --}}
    <p class="text-xs text-gray-500 dark:text-gray-400">
      ¿No ves el documento? <a href="{{ $pdfFile }}" class="content-link">Ábrelo directamente aquí</a>.
    </p>
  </section>
@endsection
