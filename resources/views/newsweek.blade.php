@extends('layouts.main')

@section('title', 'Mensaje de la semana')

@section('content')
    @foreach ($news as $nw)
        <div class="container" style="margin-top: 20px">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="">
                            <div class="wrapper">
                                <div class="headernews">{{ $nw->title }}</div><br>
                                <div class="content">{{ $nw->abstract }}</div><br><br>
                                <div class="image" style="background-image: url('{{ asset('images/news/' . $nw->image) }}');">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <table class="table table-responsive">
                    <tr>
                        @if ($nw->audio)
                            <th>Audio</th>
                            <td><audio controls>
                                    <source src="../audio/news/{{ $nw->audio }}" type="audio/mpeg">
                                    Su navegador no soporta este elemento de audio. Pero puede leer el
                                    artículo.
                                </audio>
                            </td>
                        @endif
                    </tr>
                    <tr>
                        @if ($nw->video)
                            <th>Video</th>
                            <td><video width="50%" controls>
                                    <source src="../video/news/{{ $nw->video }}" type="video/mp4">
                                    <source src="movie.ogg" type="video/ogg">
                                    Your browser does not support the video tag.
                                </video>
                            </td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>
        <div class="container">
            <iframe src ="{{ asset('/laraview/#../documents/news/' . $nw->pdfdoc) }}" width="100%" height="600px"
                style="padding-top: 20px;">
            </iframe>
        </div>
    @endforeach
    <div class="container">
        <div class="row">
            <div class="col">
                <h4>{{ $news->links() }}</h4>
            </div>
        </div>
    </div>
@stop
