@extends('layouts.main')

@section('title', 'Culto dominical')

@section('content')
    <br>
    @if ($feed)
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="wrapper">
                    <div class="headernews">{{ $feed->title }}</div>
                    <div class="content">{{ $feed->abstract }}</div>
                    <div class="read-more-container">
                        <a href="{{ url('single-feed', $feed->slug) }}" class="read-more">LEER</a>
                    </div>
                    <div class="imagenews" style="background-image: url('{{ asset('images/worship/' . $feed->image) }}');"></div>
                </div>
            </div>
        </div>
    </div>
@endif
    @if ($list)
        <div class="container containernews">
            <div class="row">
                @foreach ($list as $l)
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-image">
                                <img src="{{ asset('images/worship/' . $l->image) }}" alt="Emancipación Cristiana Afro">
                            </div>
                            <div class="card-text">
                                <p class="card-badge">{{ $l->badge }}</p>
                                <h2 class="card-title">{{ $l->title }}</h2>
                                <p class="card-body">{{ $l->abstract }}</p>
                            </div>
                            <div class="card-link">
                                <a href="{{ url('single-feed', $l->slug) }}">Leer</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <h3>{{ $list->links() }}</h3>
        </div>
    @endif
@stop