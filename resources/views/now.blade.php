@extends('layouts.main')

@section('title', 'Mirada Afro')

@section('content')
    <div class="container"><br>
        <hr>
        <div class="container blog-page">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12">
                    <div class="card single_post">
                        <div class="container">
                            <div class="row">
                                @foreach ($now as $op)
                                    <h2 class="m-t-0 m-b-12">{{ $op->title }}</h2>
                                    <div class="col-lg-4 float-right">
                                        <ul class="meta">
                                            @if ($op->audio)
                                                <h5>Escuche el audio</h5>
                                                <audio controls>
                                                    <source src="../audio/feeder/{{ $op->audio }}" type="audio/mpeg">
                                                    Su navegador no soporta este elemento de audio. Pero puede leer el
                                                    artículo.
                                                </audio>
                                            @endif
                                        </ul>
                                    </div>
                                    <iframe src ="{{ asset('/laraview/#../pdfdoc/feeder/' . $op->pdfdoc) }}" width="100%"
                                        height="600px" style="padding-top: 20px;">
                                    </iframe>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <h4>{{ $now->links() }}</h4>
            </div>
        </div>
    </div>
@stop
