@extends('layouts.panel')

@section('mainheading', 'Información detallada del versículo diario')

@section('pageheading', $quote->title)

@section('datatable')
    <table class="table table-responsive">
        <tr>
            <td colspan="2" class="text-end">
                <a class="btn btn-md btn-warning" href="../show-quote">Regresar</a>
            </td>
        </tr>
        <tr>
            <th>Imagen</th>
            <td><img class="imgcenter" src="../images/bible/{{ $quote->image }}" width="70%"
                alt="{{ $quote->text }}" srcset=""></td>
        </tr>
        <tr>
            <th>Audio</th>
            <td><audio src="../audio/quote/{{ $quote->audio }}"></audio></td>
        </tr>
        <tr>
            <th>Video</th>
            <td><video width="180" height="200" controls>
                <source src="../video/quote/{{ $quote->video }}" type="video/mp4">
                <source src="../video/quote/{{ $quote->video }}" type="video/ogg">
                Actualice su navegador para poder ver este contenido (video).
              </video></td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{$quote->date}}</td>
        </tr>
        <tr>
            <th>Ingreso a Base de Datos</th>
            <td>{{$quote->created_at}}</td>
        </tr>
        <tr>
            <th>Última actualización</th>
            <td>{{$quote->updated_at}}</td>
        </tr>
        <tr>
            <th>Estado</th>
            @if($quote->deleted_at)
                <td>Inactivo</td>
            @else
                <td>Publicado</td>
            @endif
        </tr>
    </table>
@stop
