@extends('layouts.main')

@section('title', "$postpdf->title")
<style>
    audio, h2, h5{
      text-align: center;
      color: #4287f5;
      padding-top: 20px;
    }
    .center {
      display: block;
      margin-left: auto;
      margin-right: auto;
      padding-bottom: 10px;
    }
    a{
      text-decoration: none;
    }

    h4{
      text-align: center;
      color: #4287f5;
      padding-top: 20px;
    }
    .row {
      display: flex;
    }

    .column {
      flex: 50%;
    }
</style>
@section('content')
    <div class="container">
        <div class="row">
          <div class="col">
            <h2 class="m-t-0 m-b-12">{{$postpdf->title}}</h2>
          </div>
        </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="column shadow">
          @if($postpdf->audio)
            <h4>Escuche el mensaje</h4>
            <audio controls class="center">
              <source src="../audio/readings/{{$postpdf->audio}}" type="audio/mpeg">
              Su navegador no soporta este elemento de audio. Pero puede leer el artículo.
            </audio>
          @endif
        </div>
        <div class="column shadow">
          <a href="{{url('history')}}" target=_blank><h4>Historial del mensaje de la semana</h4></a>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col">
        <iframe src ="{{asset('/laraview/#../documents/news/' .$postpdf->pdfdoc)}}" width="100%" height="600px" style="padding-top: 20px;">
        </iframe>
        </div>
      </div>
    </div>
@stop
