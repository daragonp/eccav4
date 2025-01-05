@extends('layouts.panel')

@section('title', 'Información del mensaje de la semana')

@section('pageheading', $news->title)

@section('datatable')

<div class="container">
    <table class="table table-responsive">
        <tr>
            <td colspan="2" class="text-end">
                <a class="btn btn-md btn-warning" href="../show-news">Regresar</a>
            </td>
        </tr>
        <tr>
            <th>ID</th>
            <td>{{ $news->id }}</td>
        </tr>
        <tr>
            <th>Título</th>
            <td>{{ $news->title }}</td>
        </tr>
        <tr>
            <th>Web URL</th>
            <td>{{ $news->slug }}</td>
        </tr>
        <tr>
            <th>Resumen</th>
            <td>{{ $news->abstract }}</td>
        </tr>
        <tr>
            <th>Fecha de emisión</th>
            <td>{{ $news->broadcast }}</td>
        </tr>
        <tr>
            <th>Documento PDF</th>
            <td><iframe src ="{{ asset('/laraview/#../documents/news/' . $news->pdfdoc) }}" width="100%" height="800px"
                    style="padding-top: 20px;">
                </iframe></td>
        </tr>
        <tr>
            <th>Imagen</th>
            <td><img class="imgcenter" src="../images/news/{{ $news->image }}" width="70%"
                    alt="{{ $news->abstract }}" srcset=""></td>
        </tr>
        <tr>
            <th>Audio</th>
            <td><audio controls>
                    <source src="../audio/news/{{ $news->audio }}" type="audio/mpeg">
                    Su navegador no soporta este elemento de audio. Pero puede leer el
                    artículo.
                </audio></td>
        </tr>
        <tr>
            <th>Autor</th>
            <td>{{ $news->autor }}</td>
        </tr>
        <tr>
            <th>Ingreso a Base de Datos</th>
            <td>{{ $news->created_at }}</td>
        </tr>
        <tr>
            <th>Última actualización</th>
            <td>{{ $news->updated_at }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            @if ($news->deleted_at)
                <td>Inactivo</td>
            @else
                <td>Publicado</td>
            @endif
        </tr>
    </table>
</div>
@stop
