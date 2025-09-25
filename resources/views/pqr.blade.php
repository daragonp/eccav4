@extends('layouts.main')

@section('title', 'PQR en línea')

@section('page-hero')
  <div>
    <h1 class="page-title">PQR en línea</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Peticiones, Quejas y Reclamos.</p>
  </div>
@endsection

@section('content')
  <section class="panel space-y-6">
    <p class="leading-relaxed text-gray-700 dark:text-gray-200">
      Apreciamos tus comentarios. Diligencia el formulario para peticiones, quejas o reclamos.
      Daremos respuesta oportuna según nuestra política de tratamiento de datos.
    </p>

    <div class="flex flex-wrap gap-2">
      <a href="{{ url('pqr/form') }}" class="chip">
        <i class="fa-solid fa-file-pen"></i> Formulario PQR
      </a>
      <a href="{{ url('privacy') }}" class="chip">
        <i class="fa-solid fa-shield-halved"></i> Política de privacidad
      </a>
      <a href="{{ url('rights') }}" class="chip">
        <i class="fa-solid fa-scale-balanced"></i> Derechos del titular
      </a>
    </div>

    <div class="pt-2">
      <h2 class="text-lg font-semibold mb-2">Contacto alterno</h2>
      <a href="mailto:radio@emancipacioncristianaafro.org" class="content-link inline-flex items-center gap-2">
        <i class="fa-solid fa-envelope"></i> radio@emancipacioncristianaafro.org
      </a>
    </div>
  </section>
@endsection
