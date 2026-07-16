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

    {{-- Buscador por ID --}}
    <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row gap-2 mb-4">
        <div class="relative flex-1">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Buscar carrusel por ID..." 
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-10 py-2 text-sm text-slate-900 dark:text-white"
            >
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
            </div>
        </div>
        <button type="submit" class="btn btn-secondary">Buscar</button>
        @if(request('search'))
            <a href="{{ url()->current() }}" class="btn btn-ghost">Limpiar</a>
        @endif
    </form>

    {{-- Tarjeta de la tabla --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-wrap">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Carrusel</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse ($sliders as $slider)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-3 text-sm">
                                    <div class="flex items-center">
                                        <div class="flex mr-2">
                                            {{-- Lado Izquierdo --}}
                                            @if ($slider->left_media_src)
                                                @if ($slider->left_media_type === 'video')
                                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-900 mr-2">
                                                        <video muted playsinline class="w-full h-full object-cover">
                                                            <source src="{{ $slider->left_media_src }}" type="{{ $slider->left_media_mime ?? 'video/mp4' }}">
                                                        </video>
                                                    </div>
                                                @elseif ($slider->left_media_type === 'youtube')
                                                    <div class="w-16 h-16 rounded-lg bg-black text-white flex items-center justify-center mr-2">
                                                        <i class="fab fa-youtube text-lg"></i>
                                                    </div>
                                                @else
                                                    <img src="{{ $slider->left_media_src }}" alt="Imagen izquierda" class="w-16 h-16 rounded-lg object-cover mr-2">
                                                @endif
                                            @else
                                                <div class="w-16 h-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center mr-2">
                                                    <i class="fas fa-image text-slate-500 dark:text-slate-400"></i>
                                                </div>
                                            @endif

                                            {{-- Lado Derecho --}}
                                            @if ($slider->right_media_src)
                                                @if ($slider->right_media_type === 'video')
                                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-900 mr-2">
                                                        <video muted playsinline class="w-full h-full object-cover">
                                                            <source src="{{ $slider->right_media_src }}" type="{{ $slider->right_media_mime ?? 'video/mp4' }}">
                                                        </video>
                                                    </div>
                                                @elseif ($slider->right_media_type === 'youtube')
                                                    <div class="w-16 h-16 rounded-lg bg-black text-white flex items-center justify-center mr-2">
                                                        <i class="fab fa-youtube text-lg"></i>
                                                    </div>
                                                @else
                                                    <img src="{{ $slider->right_media_src }}" alt="Imagen derecha" class="w-16 h-16 rounded-lg object-cover mr-2">
                                                @endif
                                            @else
                                                <div class="w-16 h-16 rounded-lg bg-slate-200 dark:bg-slate-700 flex items-center justify-center mr-2">
                                                    <i class="fas fa-image text-slate-500 dark:text-slate-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="font-medium text-slate-900 dark:text-white text-sm truncate">Carrusel #{{ $slider->id }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 truncate">ID: {{ $slider->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($slider->active)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-700 shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-300 border border-rose-200 dark:border-rose-700 shadow-sm">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                            </svg>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-center">
                                    @include('admin.partials.actions', [
                                        'id'         => $slider->id,
                                        'view'       => url("view-slider", $slider->id),
                                        'activate'   => url("activate-slider", $slider->id),
                                        'softdelete' => url("delete-slider", $slider->id),
                                        'realdelete' => url("realdelete-slider", $slider->id),
                                        'formAction' => url("update-slider", $slider->id),
                                        'tableM'     => $slider,
                                        'sectionType'=> 'slider',
                                        'sectionTitle' => 'Carrusel',
                                    ])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                                    <i class="fas fa-images text-5xl mb-4 opacity-50 block"></i>
                                    <p class="font-medium">No se encontraron imágenes del carrusel</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($sliders->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-700">
            {{ $sliders->links() }}
        </div>
    @endif
@endsection

@push('scripts')
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

            // URL Parser helper
            function parseYouTubeId(url) {
                if (!url) return null;
                const regExp = /(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|embed|shorts)\/|.*[?&]v=)|youtu\.be\/)([A-Za-z0-9_-]{11})/;
                const match = url.match(regExp);
                return match ? match[1] : null;
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
        });
    </script>
@endpush
