@extends('layouts.panel')

@section('mainheading', 'Información detallada del versículo diario')

@section('pageheading', 'Ver información del banner')

@section('datatable')
    <table class="table table-responsive">
        <tr>
            <td colspan="2" class="text-end">
                <a class="btn btn-md btn-warning" href="../show-slider">Regresar</a>
            </td>
        </tr>
        <tr>
            <th>Imagen de la izquierda</th>
            <td>
            <td>
                <img class="imgcenter" src="../images/slider/{{$slider->image_left}}" width="70%">
            </td>

            </td>
        </tr>
        <tr>
            <th>Imagen de la derecha</th>
            <td>
            <td>
                <img class="imgcenter" src="../images/slider/{{$slider->image_right}}" width="70%">
            </td>
            </td>
        </tr>
        <tr>
            <th>Ingreso a Base de Datos</th>
            <td>{{ $slider->created_at }}</td>
        </tr>
        <tr>
            <th>Última actualización</th>
            <td>{{ $slider->updated_at }}</td>
        </tr>
        <tr>
            <th>Estado</th>
            @if ($slider->active == 0)
                <td>Inactivo</td>
            @else
                <td>Publicado</td>
            @endif
        </tr>
    </table>
@stop
