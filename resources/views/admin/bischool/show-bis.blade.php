@php
    $showAddButton = true; // o la condición que desees
@endphp
@php
    $showModal = true;
@endphp

@extends('layouts.panel')

@section('title', 'El mensaje de la semana')

@section('pageheading', 'Mensaje de la semana')

@section('urlbtn', 'new-news')
@section('addbutton', 'Nuevo mensaje de la semana')
@section('mainheading', 'Publicaciones del mensaje de la semana')
@section('formaction', 'addnews')
{{-- CAMPOS PARA EL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
    <div class="row">
        <div class="form-group mb-2">
            <select name="category" id="">
                <option value="1">Mensaje de la semana</option>
            </select>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label>Títular de la noticia</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div>
                <label>Resumen</label>
                <textarea name="abstract" id="" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group mb-2">
                <label>Autor</label>
                <input type="text" name="autor" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label>Documento PDF</label>
                <input type="file" name="pdfdoc" accept=".pdf*" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label>Imagen de contexto</label>
                <input type="file" name="image" accept="image/*" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label>Audio</label>
                <input type="file" name="audio" accept="audio/*" class="form-control">
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
