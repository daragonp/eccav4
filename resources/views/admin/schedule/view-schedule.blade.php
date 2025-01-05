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
            <th>ID</th>
            <td>{{$quote->id}}</td>
        </tr>
        <tr>
            <th>Imagen</th>
            <td><img class="imgcenter" src="../images/bible/{{ $quote->image }}" width="70%"
                alt="{{ $quote->text }}" srcset=""></td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{$quote->date}}</td>
        </tr>
        <tr>
            <th>Mes</th>
            <td>{{$quote->month}}</td>
        </tr>
        <tr>
            <th>Libro</th>
            <td>{{$quote->book}}</td>
        </tr>
        <tr>
            <th>Capítulo</th>
            <td>{{ $quote->chapter }}</td>
        </tr>
        <tr>
            <th>Versículo</th>
            <td>{{ $quote->cite }}</td>
        </tr>
        <tr>
            <th>Texto</th>
            <td>{{ $quote->text }}</td>
        </tr>
        <tr>
            <th>Conmemoración del día</th>
            <td>{{$quote->commemoration}}</td>
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
