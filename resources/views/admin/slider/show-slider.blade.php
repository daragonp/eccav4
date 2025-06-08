@php
    $showAddButton = true; // o la condición que desees
@endphp
@php
    $showModal = true;
@endphp

@extends('layouts.panel')

@section('title', 'Carrusel de imágenes')


@section('pageheading', 'Carrusel')

@section('urlbtn', 'new-slider')
@section('addbutton', 'Crear carrusel')
@section('mainheading', 'Sliders en la base de datos')
@section('formaction', 'addslider')
{{-- CAMPOS PARA EL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label>Imagen de la izquierda</label>
                <input type="file" name="izqimage" accept="image/*" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label>Imagen de la derecha</label>
                <input type="file" name="derimage" accept="image/*" class="form-control">
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
