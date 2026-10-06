@extends('layouts.main')

@section('title', 'Resultados de búsqueda')

@section('page-hero')
  <div>
    <h1 class="page-title">Resultados de la búsqueda</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Noticias que coinciden con tu búsqueda.</p>
  </div>
@endsection

@section('content')
  <section class="panel">
    @if($buscar->isNotEmpty())
      <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ($buscar as $post)
          <article class="flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white shadow-sm">
            <img src="{{ asset('images/news/' . $post->image) }}" alt="{{ $post->title }}" class="h-48 w-full object-cover">
            <div class="flex flex-col gap-2 p-4">
              <h4 class="text-lg font-semibold" style="color: var(--green-dark);">{{ $post->title }}</h4>
              <div class="text-sm text-gray-600">{!! $post->abstract !!}</div>
            </div>
          </article>
        @endforeach
      </div>
    @else
      <p class="page-subtle">No se ha encontrado el término.</p>
    @endif
  </section>
@endsection
