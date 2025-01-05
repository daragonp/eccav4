@php
    $showAddButton = true;
@endphp
@php
    $showModal = true;
@endphp
@extends('layouts.panel')

@section('title', 'Meditad en la Palabra de vida')

@section('pageheading', 'Meditad en la Palabra de vida')

@section('urlbtn', 'new-worship')
@section('addbutton', 'Nueva palabra de vida')
@section('mainheading', 'Información general de las meditaciones publicadas')
@section('formaction', 'addworship')
{{-- CAMPOS PARA EL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label>Título</label>
                <input type="text" name="title" class="form-control" placeholder="Título o tema principal"
                    required>
            </div>
            <div class="form-group mb-2">
                <label>Descripción</label>
                <textarea name="abstract" id="" class="form-control" cols="30" rows="3" required></textarea>
            </div>
            <div class="form-group mb-2">
                <label>Fecha de emisión</label>
                <input type="date" name="broadcast" class="form-control" required>
            </div>
            <div class="form-group mb-2">
                <label>Palabra</label>
                <input type="text" name="badge" class="form-control" placeholder="Con una palabra describa esta publicación, por ejemplo: Profecía" required>
            </div>
            <div class="form-group mb-2">
                <label>Autor</label>
                <input type="text" name="autor" class="form-control" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label>Documento PDF</label>
                <input type="file" name="pdfdoc" accept='.pdf' class="form-control">
            </div>
            <div class="form-group mb-2">
                <label>Audio</label>
                <input type="file" name="audio" accept="audio/*" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label>Video</label>
                <input type="file" name="video" accept="video/*" class="form-control">
            </div>
            <div class="form-group mb-2">
                <label>Imagen de contexto</label>
                <input type="file" name="image" accept="image/*" class="form-control">
            </div>
        </div>
    </div>
@stop

@section('datatable')
    {!! $dataTable->table() !!}
@stop
@section('datatablscr')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
@stop
