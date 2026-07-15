@extends('layouts.panel')

@section('title', 'Sugerencias')
@section('pageheading', 'Centro de Sugerencias')

@section('datatable')
<div class="space-y-6">
    {{-- Tarjeta informativa --}}
    <div class="card">
        <div class="card-body p-6">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-brand-green/10 flex items-center justify-center">
                    <i class="fas fa-comment-dots text-brand-green text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Queremos escucharte</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Tu opinión es invaluable para nosotros. Comparte tus ideas, reporta fallas o envíanos tus comentarios sobre el funcionamiento de la plataforma. Analizaremos tu sugerencia para seguir mejorando.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario de sugerencias --}}
    <div class="card">
        <div class="card-body p-6">
            <form action="{{ route('suggestions.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label for="type" class="block text-sm font-medium text-slate-700 dark:text-slate-350 mb-1">
                        Tipo de sugerencia
                    </label>
                    <select 
                        id="type" 
                        name="type" 
                        class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-green/50"
                        required>
                        <option value="sugerencia">Sugerencia o idea de mejora</option>
                        <option value="error">Reporte de error (Bug)</option>
                        <option value="comentario">Comentario general</option>
                        <option value="otro">Otro asunto</option>
                    </select>
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-350 mb-1">
                        Detalle de tu mensaje
                    </label>
                    <textarea 
                        id="message" 
                        name="message" 
                        rows="6" 
                        class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-brand-green/50"
                        placeholder="Describe detalladamente tu propuesta o comentario..."
                        required></textarea>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn btn-primary flex items-center gap-2">
                        <i class="fas fa-paper-plane"></i>
                        Enviar sugerencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
