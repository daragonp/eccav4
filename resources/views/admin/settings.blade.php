@extends('layouts.panel')

@section('title', 'Configuración')
@section('pageheading', 'Configuración del Sistema')

@section('datatable')
<div class="card">
    <div class="card-body p-6">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                <i class="fas fa-cog text-blue-600 dark:text-blue-300"></i>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">Módulo de configuración</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Esta sección está disponible para administración. Se habilitarán opciones de configuración gradualmente y de forma segura.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
