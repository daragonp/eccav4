@php $showAddButton = true; @endphp

@extends('layouts.panel')

@section('title', 'Versículos del día')
@section('pageheading', 'Palabra de vida')

@section('addbutton', 'Agregar')
@section('formaction', url('addverse'))

{{-- CAMPOS DEL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="dateInput" class="block text-sm mb-1">Fecha</label>
      <input id="dateInput" type="date" name="date" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="pdfInput" class="block text-sm mb-1">Documento PDF</label>
      <input id="pdfInput" type="file" name="video" accept=".pdf"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div class="md:col-span-2">
      <label for="imageInput" class="block text-sm mb-1">Imagen</label>
      <input id="imageInput" type="file" name="image" accept="image/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div class="md:col-span-2">
      <label for="audioInput" class="block text-sm mb-1">Audio</label>
      <input id="audioInput" type="file" name="audio" accept="audio/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
  </div>
@endsection

@section('datatable')
  <div class="table-wrap">
    {!! $dataTable->table(['class' => 'w-full']) !!}
  </div>
@endsection

@push('scripts')
  {!! $dataTable->scripts() !!}
@endpush