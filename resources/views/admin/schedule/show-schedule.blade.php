@php
    $showAddButton = true; // o la condición que desees
@endphp
@php
    $showModal = true;
@endphp

@extends('layouts.panel')

@section('title', 'Programación Radio ECCA')

@section('pageheading', 'Programación')

@section('urlbtn', 'new-schedule')
@section('addbutton', 'Nuevo programa')
@section('mainheading', 'Listado de programas')
@section('formaction', 'addschedule')
{{-- CAMPOS PARA EL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
    <div class="row">
        <div class="col-md-5">
            <div class="form-group mb-2">
                <label>Nombre del programa</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label>Hora de inicio</label>
                <input type="time" name="start" id="start" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label>Hora de finalización</label>
                <input type="time" name="end" id="end" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label>Descripción</label>
                <textarea name="about" id="about" class="form-control" rows="2"></textarea>
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group mb-2">
                <label>Imagen</label>
                <input type="file" name="image" id="image" accept="image/*" class="form-control" >
            </div>
            <div class="form-group mb-2">
                <label>Director(a)</label>
                <input type="text" name="host" id="host" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label>Día(s) de emisión</label><br>
                <input class="form-check-input" type="checkbox" name="day[]" id="day" value="1"> Lunes <br>
                <input class="form-check-input" type="checkbox" name="day[]" id="day" value="2"> Martes <br>
                <input class="form-check-input" type="checkbox" name="day[]" id="day" value="3"> Miércoles<br>
                <input class="form-check-input" type="checkbox" name="day[]" id="day" value="4"> Jueves<br>
                <input class="form-check-input" type="checkbox" name="day[]" id="day" value="5"> Viernes<br>
                <input class="form-check-input" type="checkbox" name="day[]" id="day" value="6"> Sábado<br>
                <input class="form-check-input" type="checkbox" name="day[]" id="day" value="7"> Domingo
            </div>
        </div>
    </div>
@endsection
@section('datatable')
    {!! $dataTable->table() !!}
@stop
@section('datatablscr')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
@stop
