@php
    $showAddButton = true; // o la condición que desees
@endphp
@php
    $showModal = true;
@endphp

@extends('layouts.panel')

@section('title', 'Versículos del día')


@section('pageheading', 'Vesículos')

@section('urlbtn', 'new-verse')
@section('addbutton', 'Nuevo versículo')
@section('mainheading', 'Listado de versículos del día')
@section('formaction', 'addverse')
{{-- CAMPOS PARA EL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
    <div class="row">
        <div class="col-md-12">            
            <div class="form-group mb-2">
                <label>Fecha</label>
                <input type="date" name="date" min="1" max="31" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label>Documento PDF</label>
                <input type="file" name="video" accept='.pdf' class="form-control">
            </div>
            <div class="form-group mb-2">
                <label>Imagen</label>
                <input type="file" name="image" accept="image/*" class="form-control">
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