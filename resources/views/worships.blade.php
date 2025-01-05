@extends('layouts.main')

@section('title', 'Culto dominical')

@section('content')
    <div class="container">
        <div class="row">
            <div class="column shadow">
                <a href="{{ url('worshipw') }}" target=_blank>
                    <h4>Historial del mensaje de la semana</h4>
                </a>
            </div>
        </div>
    </div>
    @foreach ($worships as $nw)
        <div class="container" style="margin-top: 20px">
            <div class="row">
                <table class="table table-responsive">
                    <tr>
                        <th>Título</th>
                        <td>{{ $nw->title }}</td>
                        <th>Resumen</th>
                        <td>{{ $nw->abstract }}</td>
                    </tr>
                    <tr>
                        @if ($nw->image)
                            <th>Imagen de contexto</th>
                            <td><img class="imgcenter" src="../images/worship/{{ $nw->image }}" alt="{{ $nw->abstract }}"
                                    srcset="">
                            </td>
                        @endif
                    </tr>
                    <tr>
                        @if ($nw->audio)
                            <th>Audio</th>
                            <td><audio controls>
                                    <source src="../audio/worship/{{ $nw->audio }}" type="audio/mpeg">
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
                                    <source src="../video/worship/{{ $nw->video }}" type="video/mp4">
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
            <iframe src ="{{ asset('/laraview/#../documents/worship/' . $nw->pdfdoc) }}" width="100%" height="600px"
                style="padding-top: 20px;">
            </iframe>
        </div>
    @endforeach
    <div class="container">
        <div class="row">
            <div class="col">
                <h4>{{ $worships->links() }}</h4>
            </div>
        </div>
    </div>
@stop
