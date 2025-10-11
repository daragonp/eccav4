@extends('layouts.panel')

@section('title', 'Información del programa')
@section('pageheading', $schedule->name)

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simple lightbox for the main image
    const imageModal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const scheduleImage = document.getElementById('scheduleImage');

    if (scheduleImage) {
        scheduleImage.addEventListener('click', function() {
            modalImage.src = this.src;
            imageModal.classList.add('open');
        });
    }

    // Close modal on backdrop click or close button
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
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $schedule->name }}</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            ID: {{ $schedule->id }} • Dirigido por: {{ $schedule->host }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        @if ($schedule->deleted_at)
                            <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700">
                                <i class="fas fa-trash-alt mr-1"></i> Inactivo
                            </span>
                        @else
                            <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700">
                                <i class="fas fa-check-circle mr-1"></i> Activo
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Card de Información del Programa --}}
        <div class="card">
            <div class="card-body p-6">
                <h2 class="text-lg font-semibold mb-3 text-slate-800 dark:text-slate-200">
                    <i class="fas fa-info-circle mr-2 text-brand-green"></i> Información del Programa
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex items-center">
                        <i class="fas fa-clock text-brand-green mr-3"></i>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Horario</p>
                            <p class="font-medium">{{ $schedule->start }} - {{ $schedule->end }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-hourglass-half text-brand-green mr-3"></i>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Duración</p>
                            <p class="font-medium">{{ $schedule->duration }} minutos</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user text-brand-green mr-3"></i>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Director(a)</p>
                            <p class="font-medium">{{ $schedule->host }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar text-brand-green mr-3"></i>
                        <div>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Días de emisión</p>
                            <p class="font-medium">{{ $schedule->day }}</p>
                        </div>
                    </div>
                </div>
                
                @if($schedule->about)
                <div class="mt-4">
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-1">Descripción</p>
                    <p class="text-slate-700 dark:text-slate-300">{{ $schedule->about }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Card de Imagen --}}
        <div class="card">
            <div class="card-body p-6">
                <h2 class="text-lg font-semibold mb-3 text-slate-800 dark:text-slate-200">
                    <i class="fas fa-image mr-2 text-brand-green"></i> Imagen del Programa
                </h2>
                @if($schedule->image)
                    <div class="rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 cursor-pointer group" id="scheduleImage">
                        <img src="{{ asset('images/schedule/' . $schedule->image) }}" 
                             alt="{{ $schedule->name }}" 
                             class="w-full h-auto object-cover transition-transform duration-300 group-hover:scale-105">
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center">
                        <i class="fas fa-search-plus mr-1"></i> Haz clic en la imagen para verla en tamaño completo
                    </p>
                @else
                    <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                        <i class="fas fa-image text-4xl mb-2"></i>
                        <p>No hay imagen disponible</p>
                    </div>
                @endif
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
                        <i class="fas fa-info-circle mr-2 text-brand-green"></i> Información del Sistema
                    </h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">ID:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $schedule->id }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Creado:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $schedule->created_at->format('d/m/Y H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Actualizado:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">{{ $schedule->updated_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            {{-- Card de Acciones --}}
            <div class="card">
                <div class="card-body p-6">
                    <h2 class="text-lg font-semibold mb-4 text-slate-800 dark:text-slate-200">
                        <i class="fas fa-tools mr-2 text-brand-green"></i> Acciones
                    </h2>
                    <div class="space-y-3">
                        {{-- Editar --}}
                        <button class="btn btn-info w-full justify-center" data-modal-open="EditModal_{{ $schedule->id }}">
                            <i class="fas fa-edit mr-2"></i> Editar Programa
                        </button>
                        
                        {{-- Activar / Desactivar --}}
                        @if ($schedule->deleted_at)
                            <a href="{{ url('activate-schedule', $schedule->id) }}" class="btn btn-success w-full justify-center">
                                <i class="fas fa-toggle-on mr-2"></i> Activar
                            </a>
                        @else
                            <a href="{{ url('delete-schedule', $schedule->id) }}" class="btn btn-warning w-full justify-center">
                                <i class="fas fa-power-off mr-2"></i> Desactivar
                            </a>
                        @endif

                        {{-- Eliminar --}}
                        <form action="{{ url('realdelete-schedule', $schedule->id) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar este programa permanentemente?');" class="inline w-full">
                            @csrf
                            <button type="submit" class="btn btn-danger w-full justify-center">
                                <i class="fas fa-trash-alt mr-2"></i> Eliminar Definitivamente
                            </button>
                        </form>

                        <hr class="border-slate-200 dark:border-slate-700">

                        {{-- Regresar --}}
                        <a href="{{ url('show-schedule') }}" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-arrow-left mr-2"></i> Regresar a la Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal para Imagen (Lightbox) --}}
@if($schedule->image)
<section id="imageModal" class="tw-modal" aria-modal="true" role="dialog" aria-hidden="true">
    <div class="tw-modal-panel max-w-5xl">
        <div class="tw-modal-header">
            <h3 class="text-lg font-semibold">{{ $schedule->name }}</h3>
            <button data-modal-close class="btn btn-ghost" aria-label="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="tw-modal-body p-0">
            <img id="modalImage" src="" alt="{{ $schedule->name }}" class="w-full h-auto">
        </div>
    </div>
</section>
@endif

{{-- Modal de Edición --}}
@include('admin.partials.universal-edit-modal', [
    'modalId' => 'EditModal_' . $schedule->id,
    'formAction' => url('update-schedule', $schedule->id),
    'tableM' => $schedule,
    'sectionType' => 'schedule',
    'sectionTitle' => 'Programa',
])
@stop