@extends('layouts.panel')

@section('mainheading', 'Información detallada del usuario')

@section('pageheading', $user->title)

@section('datatable')
    <table class="table table-responsive">
        <tr>
            <td colspan="2" class="text-end">
                <a class="btn btn-md btn-warning" href="../show-user">Regresar</a>
            </td>
        </tr>
        <tr>
            <th>ID</th>
            <td>{{$user->id}}</td>
        </tr>
        <tr>
            <th>Imagen</th>
            <td><img class="imgcenter" src="../images/bible/{{ $user->image }}" width="70%"
                alt="{{ $user->text }}" srcset=""></td>
        </tr>
        <tr>
            <th>Fecha</th>
            <td>{{$user->date}}</td>
        </tr>
        <tr>
            <th>Mes</th>
            <td>{{$user->month}}</td>
        </tr>
        <tr>
            <th>Libro</th>
            <td>{{$user->book}}</td>
        </tr>
        <tr>
            <th>Capítulo</th>
            <td>{{ $user->chapter }}</td>
        </tr>
        <tr>
            <th>Versículo</th>
            <td>{{ $user->cite }}</td>
        </tr>
        <tr>
            <th>Texto</th>
            <td>{{ $user->text }}</td>
        </tr>
        <tr>
            <th>Conmemoración del día</th>
            <td>{{$user->commemoration}}</td>
        </tr>
        <tr>
            <th>Ingreso a Base de Datos</th>
            <td>{{$user->created_at}}</td>
        </tr>
        <tr>
            <th>Última actualización</th>
            <td>{{$user->updated_at}}</td>
        </tr>
        <tr>
            <th>Estado</th>
            @if($user->deleted_at)
                <td>Inactivo</td>
            @else
                <td>Publicado</td>
            @endif
        </tr>
    </table>
@stop
