@extends('layouts.main')

@section('title', 'Lumbrera a mi camino')

{{-- Cabecera (opcional): título grande + barra de acento --}}
@section('page-hero')
  <div>
    <h1 class="page-title">Lumbrera a mi camino - Viéndome con los ojos de Dios</h1>
    <div class="hr-brand"></div>
  </div>
@endsection

@section('content')
  <section class="panel">
    <div class="w-full">
      <iframe src="https://www.ivoox.com/player_es_podcast_1444438_zp_1.html?c1=e4e46b"
              width="100%" height="400" frameborder="0" allowfullscreen=""
              scrolling="no" loading="lazy" class="w-full"></iframe>
    </div>
  </section>
@endsection
