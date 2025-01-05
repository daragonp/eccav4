@php
    $showAddButton = true; // o la condición que desees
@endphp
@php
    $showModal = true;
@endphp

@extends('layouts.panel')

@section('title', 'Usuarios')

@section('pageheading', 'Usuarios')

@section('urlbtn', 'new-user')
@section('addbutton', 'Nuevo usuario')
@section('mainheading', 'Listado de usuarios en el sistema')
@section('formaction', 'adduser')
{{-- CAMPOS PARA EL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
    <div class="row">
        <div class="form-group mb-2">
            <label>Nombre del rol</label>
            <input type="text" name="name" class="form-control" placeholder="Por ejemplo, Administrador" required>
        </div>
    </div>
@endsection
@section('datatable')
    {!! $dataTable->table() !!}
@stop
@section('datatablscr')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
@stop
