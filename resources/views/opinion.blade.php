@extends('layouts.main')

@section('title', 'Mirada Afro')

@section('content')
    @foreach ($news as $nw)
        <div class="container" style="margin-top: 20px">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div class="">
                            <div class="wrapper">
                                <div class="headernews">{{ $nw->title }}</div>
                                <div class="content">{{ $nw->abstract }}</div>
                                <div class="image" style="background-image: url('{{ asset('feed/imgs/' . $nw->image) }}');">
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
                                    <source src="../feed/audio/{{ $nw->audio }}" type="audio/mpeg">
                                    Su navegador no soporta este elemento de audio. Pero puede leer el
                                    artículo.
                                </audio>
                            </td>
                        @endif
                    </tr>
                </table>
            </div>
        </div>
        <div class="container">
            <iframe src ="{{ asset('/laraview/#../feed/docs/' . $nw->pdfdoc) }}" width="100%" height="600px"
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
