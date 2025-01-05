@extends('layouts.main')

@section('title', $post->title)

@section('content')
    <div class="container" style="margin-top: 20px">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="">
                        <div class="wrapper">
                            <div class="headernews">{{ $post->title }}</div><br>
                            <div class="content">{{ $post->abstract }}</div><br>
                            <div class="image"
                                style="background-image: url('{{ asset('images/worship/' . $post->image) }}');">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-responsive">
                <tr>
                    @if ($post->audio)
                        <th>Audio</th>
                        <td><audio controls>
                                <source src="../audio/worship/{{ $post->audio }}" type="audio/mpeg">
                                Su navegador no soporta este elemento de audio. Pero puede leer el
                                artículo.
                            </audio>
                        </td>
                    @endif
                </tr>
                <tr>
                    @if ($post->video)
                        <th>Video</th>
                        <td><video width="50%" controls>
                                <source src="../video/worship/{{ $post->video }}" type="video/mp4">
                                <source src="movie.ogg" type="video/ogg">
                                Your browser does not support the video tag.
                            </video>
                        </td>
                    @endif
                </tr>
            </table>
        </div>
    </div>
    @if ($post->pdfdoc)
        <div class="container">
            <iframe src ="{{ asset('/laraview/#../documents/worship/' . $post->pdfdoc) }}" width="100%" height="600px"
                style="padding-top: 20px;">
            </iframe>
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col">
            </div>
        </div>
    </div>
@stop
