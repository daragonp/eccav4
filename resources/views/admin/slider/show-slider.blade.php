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
    {{-- Campo media izquierda (imagen / video / YouTube) --}}
    <div>
        <label class="block text-sm font-medium mb-3 text-slate-700 dark:text-slate-300">
            Medio izquierda <span class="text-red-500">*</span>
        </label>
        <div class="space-y-2">
            <select id="left_type" name="left_type" class="w-full p-2 rounded border border-slate-300 dark:border-slate-600">
                <option value="image" selected>Imagen (por defecto)</option>
                <option value="video">Video (archivo)</option>
                <option value="youtube">YouTube (enlace)</option>
            </select>

            {{-- Input imagen --}}
            <input
                id="image_left"
                type="file"
                name="image_left"
                accept="image/*"
                class="mt-2 block w-full text-sm text-slate-500
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
            <p id="image-left-help" class="text-xs text-slate-500 dark:text-slate-400">JPG, PNG, GIF, WebP • Máx. 20MB</p>

            {{-- Input video --}}
            <input
                id="video_left"
                type="file"
                name="video_left"
                accept="video/*"
                class="hidden mt-2 block w-full text-sm text-slate-500 rounded border border-slate-300 dark:border-slate-600 p-2"
            >
            <p id="video-left-help" class="hidden text-xs text-slate-500 dark:text-slate-400">Video cualquier formato válido • Máx. 50MB</p>

            {{-- Input YouTube --}}
            <input id="youtube_left" name="youtube_left" type="url" placeholder="https://www.youtube.com/watch?v=..." class="hidden mt-2 block w-full p-2 rounded border border-slate-300 dark:border-slate-600">

            {{-- Nombre del archivo seleccionado --}}
            <div id="filename-left" class="text-xs text-slate-600 dark:text-slate-400 italic hidden">
                Archivo seleccionado: <span id="filename-left-name"></span>
            </div>
        </div>
    </div>

    {{-- Campo media derecha (imagen / video / YouTube) --}}
    <div>
        <label class="block text-sm font-medium mb-3 text-slate-700 dark:text-slate-300">
            Medio derecha <span class="text-red-500">*</span>
        </label>
        <div class="space-y-2">
            <select id="right_type" name="right_type" class="w-full p-2 rounded border border-slate-300 dark:border-slate-600">
                <option value="image" selected>Imagen (por defecto)</option>
                <option value="video">Video (archivo)</option>
                <option value="youtube">YouTube (enlace)</option>
            </select>

            {{-- Input imagen --}}
            <input
                id="image_right"
                type="file"
                name="image_right"
                accept="image/*"
                class="mt-2 block w-full text-sm text-slate-500
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
            <p id="image-right-help" class="text-xs text-slate-500 dark:text-slate-400">JPG, PNG, GIF, WebP • Máx. 20MB</p>

            {{-- Input video --}}
            <input
                id="video_right"
                type="file"
                name="video_right"
                accept="video/*"
                class="hidden mt-2 block w-full text-sm text-slate-500 rounded border border-slate-300 dark:border-slate-600 p-2"
            >
            <p id="video-right-help" class="hidden text-xs text-slate-500 dark:text-slate-400">Video cualquier formato válido • Máx. 50MB</p>

            {{-- Input YouTube --}}
            <input id="youtube_right" name="youtube_right" type="url" placeholder="https://www.youtube.com/watch?v=..." class="hidden mt-2 block w-full p-2 rounded border border-slate-300 dark:border-slate-600">

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
            const leftType = document.getElementById('left_type');
            const rightType = document.getElementById('right_type');

            const imageLeftInput = document.getElementById('image_left');
            const imageRightInput = document.getElementById('image_right');
            const videoLeftInput = document.getElementById('video_left');
            const videoRightInput = document.getElementById('video_right');
            const youtubeLeft = document.getElementById('youtube_left');
            const youtubeRight = document.getElementById('youtube_right');

            const previewLeft = document.getElementById('preview-left');
            const previewRight = document.getElementById('preview-right');
            const filenameLeftDiv = document.getElementById('filename-left');
            const filenameLeftName = document.getElementById('filename-left-name');
            const filenameRightDiv = document.getElementById('filename-right');
            const filenameRightName = document.getElementById('filename-right-name');

            function showAsImage(target, src) {
                target.innerHTML = `<img src="${src}" alt="Preview" class="w-full h-full object-cover">`;
            }

            function showAsVideo(target, src) {
                target.innerHTML = `<video controls class="w-full h-full object-cover"><source src="${src}"></video>`;
            }

            function showAsYouTube(target, url) {
                const id = parseYouTubeId(url);
                if (!id) {
                    target.innerHTML = `<div class="text-center text-slate-500"><i class="fas fa-video text-3xl mb-2"></i><p class="text-xs">Enlace YouTube inválido</p></div>`;
                    return;
                }
                const embed = `https://www.youtube.com/embed/${id}`;
                target.innerHTML = `<iframe src="${embed}" class="w-full h-full" frameborder="0" allowfullscreen></iframe>`;
            }

            function parseYouTubeId(url) {
                if (!url) return null;
                const regExp = /(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|embed|shorts)\/|.*[?&]v=)|youtu\.be\/)([A-Za-z0-9_-]{11})/;
                const match = url.match(regExp);
                return match ? match[1] : null;
            }

            function updateVisibility(side) {
                const type = (side === 'left') ? leftType.value : rightType.value;

                const imageInput = document.getElementById(side === 'left' ? 'image_left' : 'image_right');
                const videoInput = document.getElementById(side === 'left' ? 'video_left' : 'video_right');
                const youtubeInput = document.getElementById(side === 'left' ? 'youtube_left' : 'youtube_right');
                const imageHelp = document.getElementById(side === 'left' ? 'image-left-help' : 'image-right-help');
                const videoHelp = document.getElementById(side === 'left' ? 'video-left-help' : 'video-right-help');

                if (type === 'image') {
                    imageInput.classList.remove('hidden'); imageHelp.classList.remove('hidden');
                    videoInput.classList.add('hidden'); videoHelp.classList.add('hidden');
                    youtubeInput.classList.add('hidden');
                } else if (type === 'video') {
                    imageInput.classList.add('hidden'); imageHelp.classList.add('hidden');
                    videoInput.classList.remove('hidden'); videoHelp.classList.remove('hidden');
                    youtubeInput.classList.add('hidden');
                } else if (type === 'youtube') {
                    imageInput.classList.add('hidden'); imageHelp.classList.add('hidden');
                    videoInput.classList.add('hidden'); videoHelp.classList.add('hidden');
                    youtubeInput.classList.remove('hidden');
                }
            }

            // Inicializar
            updateVisibility('left');
            updateVisibility('right');

            leftType.addEventListener('change', function() { updateVisibility('left'); });
            rightType.addEventListener('change', function() { updateVisibility('right'); });

            // Handlers de selección de archivos y youtube
            imageLeftInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                filenameLeftName.textContent = file.name; filenameLeftDiv.classList.remove('hidden');
                const reader = new FileReader();
                reader.onload = function(e) { showAsImage(previewLeft, e.target.result); };
                reader.readAsDataURL(file);
            });

            imageRightInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                filenameRightName.textContent = file.name; filenameRightDiv.classList.remove('hidden');
                const reader = new FileReader();
                reader.onload = function(e) { showAsImage(previewRight, e.target.result); };
                reader.readAsDataURL(file);
            });

            videoLeftInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                filenameLeftName.textContent = file.name; filenameLeftDiv.classList.remove('hidden');
                const url = URL.createObjectURL(file);
                showAsVideo(previewLeft, url);
            });

            videoRightInput.addEventListener('change', function() {
                const file = this.files[0];
                if (!file) return;
                filenameRightName.textContent = file.name; filenameRightDiv.classList.remove('hidden');
                const url = URL.createObjectURL(file);
                showAsVideo(previewRight, url);
            });

            youtubeLeft.addEventListener('input', function() {
                const url = this.value.trim();
                if (!url) {
                    previewLeft.innerHTML = '<div class="text-center"><i class="fas fa-video text-3xl mb-2 text-slate-500"></i><p class="text-xs text-slate-500">Introduce enlace YouTube</p></div>';
                    return;
                }
                showAsYouTube(previewLeft, url);
            });

            youtubeRight.addEventListener('input', function() {
                const url = this.value.trim();
                if (!url) {
                    previewRight.innerHTML = '<div class="text-center"><i class="fas fa-video text-3xl mb-2 text-slate-500"></i><p class="text-xs text-slate-500">Introduce enlace YouTube</p></div>';
                    return;
                }
                showAsYouTube(previewRight, url);
            });

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
