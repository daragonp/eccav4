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

  <section aria-labelledby="home-ad" class="mb-8 page-container">
    <h2 id="home-ad" class="sr-only">Publicidad</h2>
    <div class="flex justify-center">
      {{-- Pega aquí el código de tu bloque de anuncios --}}
      <ins class="adsbygoogle"
           style="display:block"
           data-ad-format="fluid"
           data-ad-layout-key="-fb+5w+4e-db+86"
           data-ad-client="ca-pub-2633231257763494"
           data-ad-slot="1234567890"></ins>
      <script>
           (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    </div>
  </section>

  <section aria-labelledby="home-carousel" class="mb-8">
    <h2 id="home-carousel" class="sr-only">Banners</h2>
    @include('carrusel', ['narrow' => true])
  </section>
@endsection