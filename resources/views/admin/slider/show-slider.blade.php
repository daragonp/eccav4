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
    {{-- Campo imagen izquierda --}}
    <div>
        <label for="image_left" class="block text-sm font-medium mb-3 text-slate-700 dark:text-slate-300">
            Imagen izquierda <span class="text-red-500">*</span>
        </label>
        <div class="space-y-2">
            {{-- Input de archivo sin overlay --}}
            <input 
                id="image_left" 
                type="file" 
                name="image_left" 
                accept="image/*" 
                required
                class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100
                    dark:file:bg-blue-900 dark:file:text-blue-200
                    dark:hover:file:bg-blue-800
                    cursor-pointer
                    rounded border border-slate-300 dark:border-slate-600
                    p-2"
            >
            <p class="text-xs text-slate-500 dark:text-slate-400">
                JPG, PNG, GIF, WebP • Máx. 20MB
            </p>
            {{-- Nombre del archivo seleccionado --}}
            <div id="filename-left" class="text-xs text-slate-600 dark:text-slate-400 italic hidden">
                Archivo seleccionado: <span id="filename-left-name"></span>
            </div>
        </div>
    </div>

    {{-- Campo imagen derecha --}}
    <div>
        <label for="image_right" class="block text-sm font-medium mb-3 text-slate-700 dark:text-slate-300">
            Imagen derecha <span class="text-red-500">*</span>
        </label>
        <div class="space-y-2">
            {{-- Input de archivo sin overlay --}}
            <input 
                id="image_right" 
                type="file" 
                name="image_right" 
                accept="image/*" 
                required
                class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-lg file:border-0
                    file:text-sm file:font-semibold
                    file:bg-green-50 file:text-green-700
                    hover:file:bg-green-100
                    dark:file:bg-green-900 dark:file:text-green-200
                    dark:hover:file:bg-green-800
                    cursor-pointer
                    rounded border border-slate-300 dark:border-slate-600
                    p-2"
            >
            <p class="text-xs text-slate-500 dark:text-slate-400">
                JPG, PNG, GIF, WebP • Máx. 20MB
            </p>
            {{-- Nombre del archivo seleccionado --}}
            <div id="filename-right" class="text-xs text-slate-600 dark:text-slate-400 italic hidden">
                Archivo seleccionado: <span id="filename-right-name"></span>
            </div>
        </div>
    </div>
</div>

{{-- Vista previa del carrusel --}}
<div class="mt-6 space-y-3">
    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Vista previa</h4>
    <div class="grid grid-cols-2 gap-4 bg-slate-100 dark:bg-slate-800 p-4 rounded-lg">
        {{-- Preview izquierda --}}
        <div class="aspect-video bg-slate-300 dark:bg-slate-700 rounded overflow-hidden flex items-center justify-center">
            <div id="preview-left" class="text-slate-500 dark:text-slate-400 text-center">
                <i class="fas fa-image text-3xl mb-2"></i>
                <p class="text-xs">Selecciona una imagen</p>
            </div>
        </div>
        {{-- Preview derecha --}}
        <div class="aspect-video bg-slate-300 dark:bg-slate-700 rounded overflow-hidden flex items-center justify-center">
            <div id="preview-right" class="text-slate-500 dark:text-slate-400 text-center">
                <i class="fas fa-image text-3xl mb-2"></i>
                <p class="text-xs">Selecciona una imagen</p>
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
                        Administra las imágenes del carrusel que se mostrarán en la página principal.
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
                    {{-- La tabla se generará automáticamente --}}
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Inicializando vista previa de imágenes');

            const imageLeftInput = document.getElementById('image_left');
            const imageRightInput = document.getElementById('image_right');
            const previewLeft = document.getElementById('preview-left');
            const previewRight = document.getElementById('preview-right');
            const filenameLeftDiv = document.getElementById('filename-left');
            const filenameLeftName = document.getElementById('filename-left-name');
            const filenameRightDiv = document.getElementById('filename-right');
            const filenameRightName = document.getElementById('filename-right-name');

            // Función para validar archivo
            function validateFile(file, inputName) {
                if (!file) return { valid: false, message: 'Sin archivo' };

                // Validar tipo de archivo
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                if (!validTypes.includes(file.type)) {
                    return { 
                        valid: false, 
                        message: 'Formato no válido. Usa: JPG, PNG, GIF, WebP' 
                    };
                }

                // Validar tamaño (20MB)
                const maxSize = 20 * 1024 * 1024;
                if (file.size > maxSize) {
                    return { 
                        valid: false, 
                        message: 'Archivo muy grande. Máximo 20MB' 
                    };
                }

                return { valid: true, message: 'OK' };
            }

            // Evento para imagen izquierda
            if (imageLeftInput) {
                imageLeftInput.addEventListener('change', function(e) {
                    const file = this.files[0];

                    if (!file) {
                        previewLeft.innerHTML = '<div class="text-center"><i class="fas fa-image text-3xl mb-2 text-slate-500"></i><p class="text-xs text-slate-500">Selecciona una imagen</p></div>';
                        filenameLeftDiv.classList.add('hidden');
                        return;
                    }

                    // Validar archivo
                    const validation = validateFile(file, 'image_left');
                    if (!validation.valid) {
                        alert('Imagen izquierda: ' + validation.message);
                        this.value = '';
                        previewLeft.innerHTML = '<div class="text-center"><i class="fas fa-image text-3xl mb-2 text-slate-500"></i><p class="text-xs text-slate-500">Selecciona una imagen</p></div>';
                        filenameLeftDiv.classList.add('hidden');
                        return;
                    }

                    // Mostrar nombre del archivo
                    filenameLeftName.textContent = file.name;
                    filenameLeftDiv.classList.remove('hidden');

                    // Mostrar vista previa
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewLeft.innerHTML = `<img src="${event.target.result}" alt="Preview" class="w-full h-full object-cover">`;
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Evento para imagen derecha
            if (imageRightInput) {
                imageRightInput.addEventListener('change', function(e) {
                    const file = this.files[0];

                    if (!file) {
                        previewRight.innerHTML = '<div class="text-center"><i class="fas fa-image text-3xl mb-2 text-slate-500"></i><p class="text-xs text-slate-500">Selecciona una imagen</p></div>';
                        filenameRightDiv.classList.add('hidden');
                        return;
                    }

                    // Validar archivo
                    const validation = validateFile(file, 'image_right');
                    if (!validation.valid) {
                        alert('Imagen derecha: ' + validation.message);
                        this.value = '';
                        previewRight.innerHTML = '<div class="text-center"><i class="fas fa-image text-3xl mb-2 text-slate-500"></i><p class="text-xs text-slate-500">Selecciona una imagen</p></div>';
                        filenameRightDiv.classList.add('hidden');
                        return;
                    }

                    // Mostrar nombre del archivo
                    filenameRightName.textContent = file.name;
                    filenameRightDiv.classList.remove('hidden');

                    // Mostrar vista previa
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        previewRight.innerHTML = `<img src="${event.target.result}" alt="Preview" class="w-full h-full object-cover">`;
                    };
                    reader.readAsDataURL(file);
                });
            }

            // Aplicar estilos DataTables (después de un pequeño delay)
            setTimeout(function() {
                const lengthSelect = document.querySelector('#slider-table_length select');
                const filterInput = document.querySelector('#slider-table_filter input');

                if (lengthSelect) {
                    lengthSelect.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 py-1 text-sm';
                }

                if (filterInput) {
                    filterInput.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm';
                    filterInput.placeholder = 'Buscar...';
                }
            }, 500);
        });
    </script>
@endpush
