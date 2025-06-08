@php
    $showAddButton = true; // o la condición que desees
@endphp
@php
    $showModal = true;
@endphp

@extends('layouts.panel')

@section('title', 'Versículos del día')


@section('pageheading', 'Palabra de vida')

@section('urlbtn', 'new-verse')
@section('addbutton', 'Agregar')
@section('mainheading', 'Listado general')
@section('formaction', 'addverse')
{{-- CAMPOS DEL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
    <div class="row g-3">
        <div class="col-md-6">
            <label for="dateInput" class="form-label fw-semibold">Fecha</label>
            <input id="dateInput" type="date" name="date" class="form-control shadow-sm border-primary" required>
        </div>
        <div class="col-md-6">
            <label for="pdfInput" class="form-label fw-semibold">Documento PDF</label>
            <input id="pdfInput" type="file" name="video" accept=".pdf"
                class="form-control shadow-sm border-primary">
        </div>
        <div class="col-md-6">
            <label for="imageInput" class="form-label fw-semibold">Imagen</label>
            <input id="imageInput" type="file" name="image" accept="image/*"
                class="form-control shadow-sm border-primary">
        </div>
    </div>
@endsection
@section('datatable')
    {!! $dataTable->table() !!}
@stop
@section('datatablscr')
    {!! $dataTable->scripts(attributes: ['type' => 'module']) !!}
@stop
