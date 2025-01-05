@extends('layouts.panel')

@section('title', 'Información de la palabra de vida')

@section('pageheading', $worship->title)

@section('datatable')

<div class="container">
    <table class="table table-responsive">
        <tr>
            <td colspan="2" class="text-end">
                <a class="btn btn-md btn-warning" href="../show-worship">Regresar</a>
            </td>
        </tr>
        <tr>
            <th>ID</th>
            <td>{{ $worship->id }}</td>
        </tr>
        <tr>
            <th>Título</th>
            <td>{{ $worship->title }}</td>
        </tr>
        <tr>
            <th>Web URL</th>
            <td>{{ $worship->slug }}</td>
        </tr>
        <tr>
            <th>Resumen</th>
            <td>{{ $worship->abstract }}</td>
        </tr>
        <tr>
            <th>Fecha de emisión</th>
            <td>{{ $worship->broadcast }}</td>
        </tr>
        <tr>
            <th>Documento PDF</th>
            <td><iframe src ="{{ asset('/laraview/#../documents/worship/' . $worship->pdfdoc) }}" width="100%" height="800px"
                    style="padding-top: 20px;">
                </iframe></td>
        </tr>
        <tr>
            <th>Imagen</th>
            <td><img class="imgcenter" src="../images/worship/{{ $worship->image }}" width="70%"
                    alt="{{ $worship->abstract }}" srcset=""></td>
        </tr>
        <tr>
            <th>Audio</th>
            <td><audio controls>
                    <source src="../audio/worship/{{ $worship->audio }}" type="audio/mpeg">
                    Su navegador no soporta este elemento de audio. Pero puede leer el
                    artículo.
                </audio></td>
        </tr>
        <tr>
            <th>Video</th>
            <td><video controls width="80%">
                    <source src="../video/worship/{{ $worship->video }}" type="video/mp4">
                    <source src="movie.ogg" type="video/ogg">
                    Your browser does not support the video tag.
                </video></td>
        </tr>
        <tr>
            <th>Autor</th>
            <td>{{ $worship->autor }}</td>
        </tr>
        <tr>
            <th>Ingreso a Base de Datos</th>
            <td>{{ $worship->created_at }}</td>
        </tr>
        <tr>
            <th>Última actualización</th>
            <td>{{ $worship->updated_at }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            @if ($worship->deleted_at)
                <td>Inactivo</td>
            @else
                <td>Publicado</td>
            @endif
        </tr>
    </table>
</div>
@stop
