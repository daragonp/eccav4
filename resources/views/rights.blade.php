@extends('layouts.main')

@section('title', 'Derechos del titular')

@section('page-hero')
  <div>
    <h1 class="page-title">Derechos del titular</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Acceso, actualización y control de información personal.</p>
  </div>
@endsection

@section('content')
  <section class="panel space-y-4">
    <ul class="content-list">
      <li>Conocer, actualizar y rectificar los datos personales frente a los responsables.</li>
      <li>Solicitar prueba de la autorización otorgada.</li>
      <li>Ser informado, previa solicitud, respecto del uso que se ha dado a sus datos.</li>
      <li>Presentar quejas ante la autoridad competente por infracciones a la normativa.</li>
      <li>Revocar la autorización y/o solicitar la supresión del dato cuando proceda.</li>
    </ul>

    <div class="pt-2">
      <a href="{{ url('pqr') }}" class="chip">
        <i class="fa-solid fa-file-pen"></i> Presentar PQR
      </a>
      <a href="{{ url('privacy') }}" class="chip">
        <i class="fa-solid fa-shield-halved"></i> Política de datos
      </a>
    </div>
  </section>
@endsection
