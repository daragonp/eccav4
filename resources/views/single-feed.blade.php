@extends('layouts.main')

@section('title', 'Versículo del día')

@section('content')
    <div class="container my-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-uppercase text-success">
                Palabra de Vida
            </h2>
            <p class="text-muted fs-5">
                {{ \Carbon\Carbon::parse($verse->date)->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">

                {{-- Imagen con lightbox --}}
                <a href="{{ asset('images/bible/' . $verse->image) }}"
                   data-lightbox="verse-image"
                   data-title="Derechos Reservados E.C.C.A.">
                    <img src="{{ asset('images/bible/' . $verse->image) }}"
                         alt="Versículo"
                         class="img-fluid rounded shadow mb-4 hover-shadow"
                         style="max-height: 500px; transition: transform 0.3s ease-in-out;">
                </a>

                {{-- Video embebido o mensaje --}}
                @if ($verse->video)
                    <div class="ratio ratio-16x9 mb-4">
                        <iframe src="{{ asset('documents/quote/' . $verse->video) }}"
                                allowfullscreen
                                class="rounded border border-success-subtle shadow-sm"></iframe>
                    </div>
                @else
                    <div class="alert alert-warning">
                        Documento PDF no disponible para esta fecha.
                    </div>
                @endif

                {{-- Botón de regreso --}}
                <a href="{{ url()->previous() }}" class="btn btn-outline-success mt-3">
                    <i class="bi bi-arrow-left-circle me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>
@endsection
