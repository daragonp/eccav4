@extends('layouts.main')

@section('title', 'Tez brillante')

@section('content')
  <section aria-labelledby="home-quote">
    <h2 id="home-quote" class="sr-only">Palabra del día</h2>
    @include('quote')
  </section>

  <section aria-labelledby="home-links" class="mt-8">
    <h2 id="home-links" class="sr-only">Enlaces principales</h2>
    @include('social')
  </section>

  <section aria-labelledby="home-carousel" class="mt-4">
    <h2 id="home-carousel" class="sr-only">Banners</h2>
    @include('carrusel')
  </section>
@endsection
