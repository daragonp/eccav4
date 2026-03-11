@extends('layouts.panel')

@section('title', 'Información del carrusel')
@section('pageheading', 'Ver información del carrusel')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lightbox para imágenes
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalCaption = document.getElementById('modalCaption');
    
    document.querySelectorAll('.carousel-image').forEach(img => {
        img.addEventListener('click', function() {
            modalImage.src = this.src;
            modalCaption.textContent = this.alt;
            imageModal.classList.add('open');
        });
    });

    // Cerrar modal
    if (imageModal) {
        imageModal.addEventListener('click', function(e) {
            if (e.target === imageModal || e.target.closest('[data-modal-close]')) {
                imageModal.classList.remove('open');
            }
        });
    }
});
</script>
@endpush

@section('datatable')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    {{-- Contenido Principal --}}
    <div class="xl:col-span-2 space-y-6">
        {{-- Card de Encabezado --}}
        <div class="card">
            <div class="card-body p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Carrusel #{{ $slider->id }}</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            ID: {{ $slider->id }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        @if ($slider->active)
                            <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700">
                                <i class="fas fa-check-circle mr-1"></i> Activo
                            </span>
                        @else
                            <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700">
                                <i class="fas fa-trash-alt mr-1"></i> Inactivo
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card de Imágenes --}}
        <div class="card">
            <div class="card-body p-6">
                <h2 class="text-lg font-semibold mb-4 text-slate-800 dark:text-slate-200">
                    <i class="fas fa-images mr-2 text-green-600"></i> Imágenes del Carrusel
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Imagen Izquierda --}}
                    <div>
                        <h3 class="text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Imagen izquierda</h3>
                        @if($slider->image_left)
                            <div class="rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 cursor-pointer group carousel-image" title="Haz clic para ampliar">
                                <img src="{{ asset('images/slider/' . $slider->image_left) }}" 
                                     alt="Imagen izquierda del carrusel" 
                                     class="w-full h-auto object-cover transition-transform duration-300 group-hover:scale-105">
                            </div>
                        @else
                            <div class="rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center h-48">
                                <i class="fas fa-image text-slate-400 dark:text-slate-500 text-4xl"></i>
                                <p class="text-slate-500 dark:text-slate-400 mt-2">No hay imagen</p>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Imagen Derecha --}}
                    <div>
                        <h3 class="text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Imagen derecha</h3>
                        @if($slider->image_right)
                            <div class="rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 cursor-pointer group carousel-image" title="Haz clic para ampliar">
                                <img src="{{ asset('images/slider/' . $slider->image_right) }}" 
                                     alt="Imagen derecha del carrusel" 
                                     class="w-full h-auto object-cover transition-transform duration-300 group-hover:scale-105">
                            </div>
                        @else
                            <div class="rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 flex items-center justify-center h-48">
                                <i class="fas fa-image text-slate-400 dark:text-slate-500 text-4xl"></i>
                                <p class="text-slate-500 dark:text-slate-400 mt-2">No hay imagen</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra Lateral (Información y Acciones) --}}
    <div class="xl:col-span-1">
        <div class="sticky top-20 space-y-6">
            {{-- Card de Metadatos --}}
            <div class="card">
                <div class="card-body p-6">
                    <h2 class="text-lg font-semibold mb-4 text-slate-800 dark:text-slate-200">
                        <i class="fas fa-info-circle mr-2 text-green-600"></i> Información
                    </h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">ID:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $slider->id }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Creado:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $slider->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Actualizado:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $slider->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Card de Acciones --}}
            <div class="card">
                <div class="card-body p-6">
                    <h2 class="text-lg font-semibold mb-4 text-slate-800 dark:text-slate-200">
                        <i class="fas fa-tools mr-2 text-green-600"></i> Acciones
                    </h2>
                    <div class="space-y-3">
                        {{-- Editar --}}
                        <button type="button" class="btn btn-info w-full justify-center" data-modal-open="EditModal_{{ $slider->id }}">
                            <i class="fas fa-edit mr-2"></i> Editar Carrusel
                        </button>
                        
                        {{-- Activar / Desactivar --}}
                        @if ($slider->active)
                            <form action="{{ url('delete-slider', $slider->id) }}" method="POST" class="w-full" onsubmit="return confirm('¿Desea desactivar este carrusel?');">
                                @csrf
                                <button type="submit" class="btn btn-warning w-full justify-center">
                                    <i class="fas fa-power-off mr-2"></i> Desactivar
                                </button>
                            </form>
                        @else
                            <form action="{{ url('activate-slider', $slider->id) }}" method="POST" class="w-full" onsubmit="return confirm('¿Desea activar este carrusel?');">
                                @csrf
                                <button type="submit" class="btn btn-success w-full justify-center">
                                    <i class="fas fa-toggle-on mr-2"></i> Activar
                                </button>
                            </form>
                        @endif

                        {{-- Eliminar --}}
                        <form action="{{ url('realdelete-slider', $slider->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar este carrusel permanentemente?');" class="inline w-full">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-full justify-center">
                                <i class="fas fa-trash-alt mr-2"></i> Eliminar Definitivamente
                            </button>
                        </form>

                        <hr class="border-slate-200 dark:border-slate-700">

                        {{-- Regresar --}}
                        <a href="{{ url('show-slider') }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-arrow-left mr-2"></i> Regresar a la Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Universal de Edición --}}
@include('admin.partials.universal-edit-modal', [
    'modalId' => 'EditModal_' . $slider->id,
    'formAction' => url('update-slider', $slider->id),
    'tableM' => $slider,
    'sectionType' => 'slider',
    'sectionTitle' => 'Carrusel',
])

{{-- Modal para Imagen (Lightbox) --}}
<section id="imageModal" class="tw-modal" aria-modal="true" role="dialog" aria-hidden="true">
    <div class="tw-modal-panel max-w-5xl">
        <div class="tw-modal-header">
            <h3 class="text-lg font-semibold">Imagen del carrusel</h3>
            <button data-modal-close class="btn btn-ghost" aria-label="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="tw-modal-body p-0">
            <img id="modalImage" src="" alt="Imagen del carrusel" class="w-full h-auto">
            <p id="modalCaption" class="text-center p-4 text-slate-600 dark:text-slate-400"></p>
        </div>
    </div>
</section>
@stop
