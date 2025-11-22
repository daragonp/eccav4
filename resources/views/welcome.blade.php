@extends('layouts.main')

@section('title', 'Tez brillante')

@section('content')
  <section aria-labelledby="home-quote" class="mb-8">
    <h2 id="home-quote" class="sr-only">Palabra del día</h2>
    @include('quote', ['narrow' => true])
  </section>

  <!-- Sección social sin contenedor para que ocupe todo el ancho -->
  <section aria-labelledby="home-social" class="mb-8 px-none">
    <h2 id="home-social" class="sr-only">Enlaces sociales y recursos</h2>
    @include('social')
  </section>

  <section aria-labelledby="home-carousel" class="mb-8">
    <h2 id="home-carousel" class="sr-only">Banners</h2>
    @include('carrusel', ['narrow' => true])
  </section>
@endsection