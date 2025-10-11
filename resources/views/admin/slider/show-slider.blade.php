@php
    $showAddButton = true;
@endphp

@extends('layouts.panel')

@section('title', 'Carrusel de imágenes')
@section('pageheading', 'Carrusel')

@section('addbutton', 'Crear carrusel')
@section('formaction', url('addslider'))

{{-- CAMPOS PARA EL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="image_left" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">
            Imagen de la izquierda
        </label>
        <div class="relative">
            <input id="image_left" type="file" name="image_left" accept="image/*" 
                   class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
            <label class="absolute inset-0 w-full h-full cursor-pointer" for="image_left"></label>
        </div>
        <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
            Formatos admitidos: JPG, PNG, GIF, WebP (máx. 20MB)
        </div>
    </div>
    <div>
        <label for="image_right" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">
            Imagen de la derecha
        </label>
        <div class="relative">
            <input id="image_right" type="file" name="image_right" accept="image/*" 
                   class="w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
            <label class="absolute inset-0 w-full h-full cursor-pointer" for="image_right"></label>
        </div>
        <div class="mt-2 text-xs text-slate-500 dark:text-slate-400">
            Formatos admitidos: JPG, PNG, GIF, WebP (máx. 20MB)
        </div>
    </div>
</div>

{{-- Vista previa de imágenes --}}
<div class="mt-6 border-t border-slate-200 dark:border-slate-700 pt-6">
    <h4 class="text-sm font-medium mb-4 text-slate-700 dark:text-slate-300">Vista previa del carrusel</h4>
    <div class="bg-slate-100 dark:bg-slate-800 rounded-lg p-4">
        <div class="grid grid-cols-2 gap-4">
            <div class="aspect-video bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                <div id="preview-left" class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-image text-slate-400 dark:text-slate-500 text-2xl"></i>
                </div>
            </div>
            <div class="aspect-video bg-slate-200 dark:bg-slate-700 rounded-lg flex items-center justify-center">
                <div id="preview-right" class="w-full h-full flex items-center justify-center">
                    <i class="fas fa-image text-slate-400 dark:text-slate-500 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('datatable')
    {{-- Tarjeta de información --}}
    <div class="card mb-6">
        <div class="card-body p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md">
                    <i class="fas fa-images"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">Carrusel de imágenes</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Administra las imágenes del carrusel que se mostrarán en la página principal. Usa el botón "Crear carrusel" para añadir nuevos elementos o las acciones por fila para ver, editar o eliminar carruseles existentes.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjeta de la tabla --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-wrap">
                <table id="slider-table" class="display w-full">
                    <!-- La tabla se generará automáticamente -->
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Vista previa de imágenes al seleccionar archivos
        const imageLeftInput = document.getElementById('image_left');
        const imageRightInput = document.getElementById('image_right');
        const previewLeft = document.getElementById('preview-left');
        const previewRight = document.getElementById('preview-right');

        imageLeftInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewLeft.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="w-full h-full object-cover rounded-lg">`;
                }
                reader.readAsDataURL(file);
            }
        });

        imageRightInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewRight.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="w-full h-full object-cover rounded-lg">`;
                }
                reader.readAsDataURL(file);
            }
        });
        
        // Aplicar estilos a los controles de DataTables después de que se cargue
        setTimeout(function() {
            const lengthSelect = document.querySelector('#slider-table_length select');
            const filterInput = document.querySelector('#slider-table_filter input');
            
            if (lengthSelect) {
                lengthSelect.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 py-1 text-sm';
            }
            
            if (filterInput) {
                filterInput.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm';
                filterInput.placeholder = 'Buscar...';
                
                // Asegurar que la búsqueda funcione correctamente
                filterInput.addEventListener('keyup', function() {
                    $('#slider-table').DataTable().search(this.value).draw();
                });
            }
        }, 100);
    });
    </script>
@endpush