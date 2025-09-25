@extends('layouts.main')

@section('title', 'Misión')

{{-- Cabecera (opcional): título grande + barra de acento --}}
@section('page-hero')
  <div>
    <h1 class="page-title">Misión</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Quiénes somos y hacia dónde vamos.</p>
  </div>
@endsection

@section('content')
  <section class="panel">
    <ul class="content-list">
      <li>Llevar el Evangelio sanador de nuestro Señor Jesucristo a las comunidades de población afro.</li>
      <li>Capacitar líderes cristianos Afros, en todos los ámbitos de la vida, comprometidos para que sean guarda de sus hermanos.</li>
      <li>
        Equipar discípulos que amplíen la comprensión de lo que significa seguir a Jesús, para trabajar por los ideales Bíblicos en la familia y la comunidad,
        siempre con el principio: “<em>Biblia solo Biblia, el dedo índice en la palabra de Dios</em>”.
      </li>
    </ul>
  </section>
@endsection
