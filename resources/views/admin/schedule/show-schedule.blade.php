@php $showAddButton = true; @endphp
@extends('layouts.panel')

@section('title', 'Programación Radio ECCA')
@section('pageheading', 'Programación')
@section('addbutton', 'Nuevo programa')
@section('formaction', url('addschedule'))

{{-- CAMPOS PARA EL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Nombre del programa -->
        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nombre del programa</label>
            <input 
                id="name" 
                type="text" 
                name="name" 
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
                value="{{ old('name') }}"
                required>
        </div>

        <!-- Director/a -->
        <div>
            <label for="host" class="block text-sm font-medium mb-1">Director(a)</label>
            <input 
                id="host" 
                type="text" 
                name="host" 
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
                value="{{ old('host') }}"
                required>
        </div>

        <!-- Hora de inicio -->
        <div>
            <label for="start" class="block text-sm font-medium mb-1">Hora de inicio</label>
            <input 
                id="start" 
                type="time" 
                name="start" 
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
                value="{{ old('start') }}"
                required>
        </div>

        <!-- Hora de finalización -->
        <div>
            <label for="end" class="block text-sm font-medium mb-1">Hora de finalización</label>
            <input 
                id="end" 
                type="time" 
                name="end" 
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
                value="{{ old('end') }}"
                required>
        </div>

        <!-- Descripción -->
        <div class="md:col-span-2">
            <label for="about" class="block text-sm font-medium mb-1">Descripción</label>
            <textarea 
                id="about" 
                name="about" 
                rows="3" 
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">{{ old('about') }}</textarea>
        </div>
    </div>

    {{-- Sección de días de emisión --}}
    <div class="mt-6 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
        <h4 class="font-medium mb-3 flex items-center">
            <i class="fas fa-calendar-week text-blue-500 mr-2"></i>
            Días de emisión
        </h4>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">
            Selecciona los días en que se emitirá este programa
        </p>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            @php
                $dias = [
                    1 => 'Lunes',
                    2 => 'Martes',
                    3 => 'Miércoles',
                    4 => 'Jueves',
                    5 => 'Viernes',
                    6 => 'Sábado',
                    7 => 'Domingo',
                ];
                $oldDays = old('day', []);
            @endphp
            
            @foreach ($dias as $num => $nombre)
                <div class="flex items-center">
                    <input 
                        id="day{{ $num }}" 
                        type="checkbox" 
                        name="day[]" 
                        value="{{ $num }}"
                        {{ in_array($num, $oldDays) ? 'checked' : '' }}
                        class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-brand-green focus:ring-brand-green">
                    <label for="day{{ $num }}" class="ml-2 text-sm text-slate-700 dark:text-slate-300">
                        {{ $nombre }}
                    </label>
                </div>
            @endforeach
        </div>
        
        @error('day')
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
        @enderror
    </div>

    {{-- Sección de imagen --}}
    <div class="mt-6 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
        <h4 class="font-medium mb-3 flex items-center">
            <i class="fas fa-image text-blue-500 mr-2"></i>
            Imagen del Programa
        </h4>
        <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
            Sube una imagen representativa del programa (opcional)
        </p>
        <input 
            id="image" 
            type="file" 
            name="image" 
            accept="image/*"
            class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
        <p class="mt-2 text-xs text-slate-500 dark:text-slate-500">
            Formatos aceptados: JPG, PNG, GIF, WEBP (máx. 2MB)
        </p>
    </div>

    {{-- Botón para importar CSV --}}
    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
        <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-blue-500 mt-1"></i>
            <div class="flex-1">
                <p class="text-sm font-medium text-slate-900 dark:text-white mb-1">
                    ¿Tienes muchos programas para agregar?
                </p>
                <p class="text-sm text-slate-600 dark:text-slate-400 mb-3">
                    Puedes importarlos desde un archivo CSV
                </p>
                <a 
                    href="{{ url('/import-schedule') }}" 
                    class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                    <i class="fas fa-file-csv mr-2"></i>
                    Ir a importación masiva
                </a>
            </div>
        </div>
    </div>
@endsection

@section('datatable')
    {{-- Tarjeta de información --}}
    <div class="card mb-6">
        <div class="card-body p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-brand-green"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">Programación Radio ECCA</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Administra la programación de la radio. Usa el botón "Nuevo programa" para añadir nuevos programas o las acciones por fila para ver, editar o eliminar programas existentes.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tarjeta de la tabla --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-wrap">
                {!! $dataTable->table() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {!! $dataTable->scripts() !!}
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Aplicar estilos a los controles de DataTables después de que se cargue
            setTimeout(function() {
                const lengthSelect = document.querySelector('#schedule-table_length select');
                const filterInput = document.querySelector('#schedule-table_filter input');
                
                if (lengthSelect) {
                    lengthSelect.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 py-1 text-sm';
                }
                
                if (filterInput) {
                    filterInput.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm';
                    filterInput.placeholder = 'Buscar...';
                }
            }, 100);
        });
    </script>
@endpush
