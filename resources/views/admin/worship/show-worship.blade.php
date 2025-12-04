@php $showAddButton = true; @endphp

@extends('layouts.panel')

@section('title', 'Culto Dominical')
@section('pageheading', 'Culto Dominical')

@section('addbutton', 'Agregar')
@section('formaction', url('addworship'))

{{-- CAMPOS DEL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="broadcast" class="block text-sm mb-1">Fecha de emisión</label>
      <input id="broadcast" type="date" name="broadcast" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="title" class="block text-sm mb-1">Título (opcional)</label>
      <input id="title" type="text" name="title"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
             placeholder="Si no se proporciona, se generará automáticamente">
    </div>
    <div class="md:col-span-2">
      <label for="abstract" class="block text-sm mb-1">Resumen</label>
      <textarea id="abstract" name="abstract" rows="4"
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
                placeholder="Escribe un resumen del culto. Si no lo proporcionas, se generará automáticamente con IA."></textarea>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        <i class="fas fa-info-circle mr-1"></i> 
        Puedes escribir un resumen manualmente o dejarlo vacío para que la IA lo genere automáticamente
      </p>
    </div>
    <div class="md:col-span-2">
      <label for="image" class="block text-sm mb-1">Imagen</label>
      <input id="image" type="file" name="image" accept="image/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        <i class="fas fa-info-circle mr-1"></i> 
        Puedes subir una imagen manualmente o dejarlo vacío para que la IA genere una automáticamente
      </p>
    </div>
    <div class="md:col-span-2">
      <label for="audio" class="block text-sm mb-1">Audio</label>
      <input id="audio" type="file" name="audio" accept="audio/*" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        <i class="fas fa-microphone mr-1"></i> 
        El audio se procesará con IA para generar contenido automático si no se proporciona resumen o imagen
      </p>
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