@extends('layouts.panel')

@section('mainheading', 'Información detallada del versículo diario')

@section('pageheading', $schedule->title)

@section('datatable')
    <table class="table table-responsive">
        <tr>
            <td colspan="2" class="text-end">
                <a class="btn btn-md btn-warning" href="../show-schedule">Regresar</a>
            </td>
        </tr>
        <tr>
            <th>ID</th>
            <td>{{$schedule->id}}</td>
        </tr>
        <tr>
            <th>Nombre del programa</th>
            <td>{{$schedule->name}}</td>
        </tr>
        <tr>
            <th>Dirige</th>
            <td>{{$schedule->host}}</td>
        </tr>
        <tr>
            <th>Duración</th>
            <td>{{$schedule->duration}} minutos</td>
        </tr>
        <tr>
            <th>Días de emisión</th>
            <td>{{ $schedule->day }}</td>
        </tr>
        <tr>
            <th>Hora de inicio</th>
            <td>{{ $schedule->start }}</td>
        </tr>
        <tr>
            <th>Hora de finalización</th>
            <td>{{ $schedule->end }}</td>
        </tr>
        <tr>
            <th>Imagen</th>
            <td><img class="imgcenter" src="../images/schedule/{{ $schedule->image }}" width="20%"
                alt="{{ $schedule->host }}" srcset=""></td>
        </tr>
        <tr>
            <th>Ingreso a Base de Datos</th>
            <td>{{$schedule->created_at}}</td>
        </tr>
        <tr>
            <th>Última actualización</th>
            <td>{{$schedule->updated_at}}</td>
        </tr>
        <tr>
            <th>Estado</th>
            @if($schedule->deleted_at)
                <td>Inactivo</td>
            @else
                <td>Publicado</td>
            @endif
        </tr>
    </table>
@stop
