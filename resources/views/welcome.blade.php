@extends('layouts.main')

@section('title', 'Tez brillante')

@section('content')
  <section aria-labelledby="home-quote" class="mb-8">
    <h2 id="home-quote" class="sr-only">Palabra del día</h2>
    @include('quote', ['narrow' => true])
  </section>

  <!-- Emisora de Radio en Vivo (WideStream) -->
  <section aria-labelledby="home-live-radio" class="mb-8">
    <h2 id="home-live-radio" class="sr-only">Radio en Vivo</h2>
    <div class="rounded-3xl bg-linear-to-r from-emerald-950 via-slate-900 to-teal-950 p-5 sm:p-6 border border-emerald-500/30 shadow-2xl text-white">
      <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
        <div class="flex items-center gap-4">
          <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 border border-emerald-400/30 flex items-center justify-center text-emerald-400 shrink-0 shadow-inner">
            <i class="fa-solid fa-tower-broadcast text-2xl animate-pulse"></i>
          </div>
          <div>
            <div class="flex items-center gap-2 mb-1">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-600 text-white shadow-xs">
                <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span> EN VIVO
              </span>
              <span class="text-xs font-semibold text-emerald-300">Emisora Oficial 24/7</span>
            </div>
            <h3 class="text-lg sm:text-xl font-extrabold text-white tracking-tight">Radio Emancipación Cristiana Afro</h3>
            <p class="text-xs text-slate-300">Sintoniza alabanza, palabra y programación edificante en tiempo real</p>
          </div>
        </div>

        <div class="w-full lg:max-w-xl">
          <x-radio-widget layout="main" />
        </div>
      </div>
    </div>
  </section>

  <!-- Últimos cultos -->
  <section aria-labelledby="home-latest-worships" class="mb-8">
    <h2 id="home-latest-worships" class="sr-only">Últimos cultos dominicales</h2>
    @include('components.latest-worships')
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