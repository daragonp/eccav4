@php
    $showAddButton = true;
@endphp

@extends('layouts.panel')

@section('title', 'Programación Radio ECCA')
@section('pageheading', 'Programación')

@section('addbutton', 'Nuevo programa')
@section('formaction', url('addschedule'))

{{-- CAMPOS PARA EL MODAL PARA AGREGAR UN REGISTRO --}}
@section('modalFields')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="name" class="block text-sm font-medium mb-1">Nombre del programa</label>
            <input id="name" type="text" name="name" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
        </div>
        <div>
            <label for="host" class="block text-sm font-medium mb-1">Director(a)</label>
            <input id="host" type="text" name="host" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
        </div>
        <div>
            <label for="start" class="block text-sm font-medium mb-1">Hora de inicio</label>
            <input id="start" type="time" name="start" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
        </div>
        <div>
            <label for="end" class="block text-sm font-medium mb-1">Hora de finalización</label>
            <input id="end" type="time" name="end" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
        </div>
        <div class="md:col-span-2">
            <label for="about" class="block text-sm font-medium mb-1">Descripción</label>
            <textarea id="about" name="about" rows="3" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"></textarea>
        </div>
    </div>
    
    {{-- Sección de días de emisión --}}
    <div class="mt-6 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
        <h4 class="font-medium mb-3 flex items-center">
            <i class="fas fa-calendar-week text-blue-500 mr-2"></i> Días de emisión
        </h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="flex items-center">
                <input id="day_1" type="checkbox" name="day[]" value="1" class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-brand-green focus:ring-brand-green">
                <label for="day_1" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Lunes</label>
            </div>
            <div class="flex items-center">
                <input id="day_2" type="checkbox" name="day[]" value="2" class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-brand-green focus:ring-brand-green">
                <label for="day_2" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Martes</label>
            </div>
            <div class="flex items-center">
                <input id="day_3" type="checkbox" name="day[]" value="3" class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-brand-green focus:ring-brand-green">
                <label for="day_3" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Miércoles</label>
            </div>
            <div class="flex items-center">
                <input id="day_4" type="checkbox" name="day[]" value="4" class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-brand-green focus:ring-brand-green">
                <label for="day_4" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Jueves</label>
            </div>
            <div class="flex items-center">
                <input id="day_5" type="checkbox" name="day[]" value="5" class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-brand-green focus:ring-brand-green">
                <label for="day_5" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Viernes</label>
            </div>
            <div class="flex items-center">
                <input id="day_6" type="checkbox" name="day[]" value="6" class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-brand-green focus:ring-brand-green">
                <label for="day_6" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Sábado</label>
            </div>
            <div class="flex items-center">
                <input id="day_7" type="checkbox" name="day[]" value="7" class="rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 text-brand-green focus:ring-brand-green">
                <label for="day_7" class="ml-2 text-sm text-slate-700 dark:text-slate-300">Domingo</label>
            </div>
        </div>
    </div>
    
    {{-- Sección de imagen --}}
    <div class="mt-6 border border-slate-200 dark:border-slate-700 rounded-lg p-4">
        <h4 class="font-medium mb-3 flex items-center">
            <i class="fas fa-image text-blue-500 mr-2"></i> Imagen del Programa
        </h4>
        <input id="image" type="file" name="image" accept="image/*" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
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
                <table id="schedule-table" class="display w-full">
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