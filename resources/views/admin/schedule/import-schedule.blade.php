@extends('layouts.panel')

@section('title', 'Importar Programación desde CSV')
@section('pageheading', 'Importación Masiva')

@section('datatable')
    {{-- Tarjeta de información --}}
    <div class="card mb-6">
        <div class="card-body p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                    <i class="fas fa-file-csv text-indigo-600 dark:text-indigo-400 text-xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">Importación Masiva de Programación</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Importa múltiples programas desde un archivo CSV. Asegúrate de que el formato sea correcto antes de importar.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Formulario de importación --}}
        <div class="card">
            <div class="card-header bg-gradient-to-r from-indigo-500 to-purple-600 text-white p-4">
                <h2 class="text-xl font-semibold">Subir Archivo CSV</h2>
            </div>
            <div class="card-body p-6">
                <form action="{{ url('/import-schedule-csv') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="csv_file" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">
                                <i class="fas fa-upload mr-2"></i>
                                Seleccionar archivo CSV
                            </label>
                            <input 
                                type="file" 
                                id="csv_file" 
                                name="csv_file" 
                                accept=".csv,.txt"
                                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400"
                                required>
                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">
                                Solo archivos CSV (máximo 10MB)
                            </p>
                        </div>

                        <div class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-500 mt-0.5"></i>
                                <div class="text-sm text-slate-700 dark:text-slate-300">
                                    <strong>Importante:</strong> Los programas con conflictos de horario serán omitidos. Revisa los mensajes de error después de la importación.
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full btn btn-primary">
                            <i class="fas fa-cloud-upload-alt mr-2"></i>
                            Importar Programación
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Instrucciones y formato --}}
        <div class="card">
            <div class="card-header bg-slate-100 dark:bg-slate-800 p-4">
                <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Formato del Archivo CSV</h2>
            </div>
            <div class="card-body p-6">
                <div class="space-y-4">
                    <div>
                        <h3 class="font-medium text-slate-900 dark:text-white mb-2">
                            <i class="fas fa-list-ol mr-2"></i>
                            Orden de las columnas
                        </h3>
                        <ol class="text-sm space-y-1 list-decimal list-inside text-slate-600 dark:text-slate-400">
                            <li>Nombre del programa</li>
                            <li>Imagen (nombre del archivo)</li>
                            <li>Slug (URL amigable, único por fila)</li>
                            <li>Descripción (sin comas ni saltos de línea)</li>
                            <li>Hora de inicio (HH:MM)</li>
                            <li>Hora de fin (HH:MM)</li>
                            <li>Director/a</li>
                            <li>Día (1-7, donde 1=Lunes, 7=Domingo)</li>
                            <li>Duración (en minutos)</li>
                            <li>created_at (opcional)</li>
                            <li>updated_at (opcional)</li>
                            <li>emission_key (opcional)</li>
                        </ol>
                    </div>

                    <div class="p-4 bg-slate-50 dark:bg-slate-800 rounded-lg">
                        <h3 class="font-medium text-slate-900 dark:text-white mb-2">
                            <i class="fas fa-file-code mr-2"></i>
                            Ejemplo de fila CSV
                        </h3>
                        <pre class="text-xs overflow-x-auto p-2 bg-white dark:bg-slate-900 rounded border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white whitespace-pre-wrap break-words"><code>Principios de Sabiduria,lbanguero.jpeg,principios-de-sabiduria,Lectura del proverbio del dia,00:01,00:17,Lorena Banguero Rivas,5,16,2025-10-24 14:19:31,2025-10-24 14:19:31,principios-de-sabiduria_00-01_00-17</code></pre>
                    </div>

                    <div class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-ban text-red-600 dark:text-red-400 mt-0.5"></i>
                            <div class="text-sm text-slate-700 dark:text-slate-300">
                                <strong>Errores comunes:</strong>
                                <ul class="list-disc list-inside mt-2 space-y-1">
                                    <li>Saltos de línea dentro de campos (usar una sola línea)</li>
                                    <li>Caracteres especiales como : en el slug</li>
                                    <li>Espacios adicionales al inicio o final</li>
                                    <li>Slugs duplicados en el mismo archivo</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-info-circle text-blue-600 dark:text-blue-400 mt-0.5"></i>
                            <div class="text-sm text-slate-700 dark:text-slate-300">
                                <strong>Consejo:</strong> Si no incluyes la columna <code class="px-1 py-0.5 bg-white dark:bg-slate-800 rounded text-xs">emission_key</code>, se generará automáticamente.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Notas adicionales --}}
    <div class="card mt-6">
        <div class="card-body p-6">
            <h3 class="font-semibold text-slate-900 dark:text-white mb-4">
                <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                Notas Importantes
            </h3>
            <ul class="space-y-2 text-sm text-slate-600 dark:text-slate-400">
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                    <span>El sistema validará automáticamente que no haya conflictos de horarios entre programas.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                    <span>Si la imagen especificada no existe en el servidor, se usará la imagen genérica.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                    <span>Los programas con el mismo <code class="px-1 py-0.5 bg-slate-100 dark:bg-slate-800 rounded text-xs">emission_key</code> se consideran el mismo programa en diferentes días.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
                    <span>Puedes importar el mismo programa para múltiples días creando una fila por cada día.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-times-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                    <span>Los programas con horarios inválidos o datos faltantes serán omitidos.</span>
                </li>
                <li class="flex items-start gap-2">
                    <i class="fas fa-times-circle text-red-500 mt-0.5 flex-shrink-0"></i>
                    <span>Asegúrate de que el slug sea único en cada fila para evitar duplicados.</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Botones de acción --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mt-6">
        <a href="{{ url('/show-schedule') }}" class="btn btn-secondary w-full sm:w-auto">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver a Programación
        </a>
        
        <a href="{{ asset('files/programacion_ejemplo.csv') }}" 
           download="programacion_ejemplo.csv"
           class="btn btn-success w-full sm:w-auto">
            <i class="fas fa-download mr-2"></i>
            Descargar Ejemplo CSV
        </a>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('csv_file');
        
        if (fileInput) {
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Validar tamaño
                    if (file.size > 10 * 1024 * 1024) {
                        alert('El archivo es demasiado grande. Máximo 10MB.');
                        this.value = '';
                        return;
                    }
                    
                    // Validar extensión
                    const validExtensions = ['.csv', '.txt'];
                    const fileName = file.name.toLowerCase();
                    const isValid = validExtensions.some(ext => fileName.endsWith(ext));
                    
                    if (!isValid) {
                        alert('Por favor selecciona un archivo CSV válido.');
                        this.value = '';
                        return;
                    }
                    
                    // Mostrar nombre del archivo
                    console.log('Archivo seleccionado:', file.name);
                }
            });
        }
    });
</script>
@endpush
