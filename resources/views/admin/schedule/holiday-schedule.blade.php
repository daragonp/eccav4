@extends('layouts.panel')

@section('title', 'Días Festivos y Programación Especial')
@section('pageheading', 'Programación Especial')

@section('datatable')
    {{-- Tarjeta de información --}}
    <div class="card mb-6">
        <div class="card-body p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="fas fa-calendar-star text-purple-600 dark:text-purple-400"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900 dark:text-white">Programación Especial para Días Festivos</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                        Configura programación alternativa para fechas específicas. Por ejemplo, puedes usar la programación de sábado para un día festivo que cae en martes.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Botón para agregar nuevo override --}}
    <div class="flex justify-end mb-4">
        <button 
            data-modal-open="addOverrideModal" 
            class="btn btn-primary">
            <i class="fa-solid fa-plus-circle"></i>
            Nueva Programación Especial
        </button>
    </div>

    {{-- Tabla de overrides --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Fecha</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Día Real</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Usar Programación De</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Motivo</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse ($overrides as $override)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">
                                    <div class="font-medium">{{ $override->date->format('d/m/Y') }}</div>
                                    <div class="text-xs text-slate-500 dark:text-slate-400">
                                        {{ $override->date->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                                    {{ $override->date_day_name }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        {{ $override->override_day_name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                                    {{ $override->reason ?? '-' }}
                                </td>
                                <td class="px-4 py-3 text-sm">
                                    @if ($override->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                            <i class="fas fa-check-circle mr-1"></i> Activo
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400">
                                            <i class="fas fa-times-circle mr-1"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- Botón toggle activo/inactivo --}}
                                        <a 
                                            href="{{ url('/toggle-override/' . $override->id) }}" 
                                            class="btn btn-sm {{ $override->is_active ? 'btn-warning' : 'btn-success' }}"
                                            title="{{ $override->is_active ? 'Desactivar' : 'Activar' }}">
                                            <i class="fas fa-{{ $override->is_active ? 'pause' : 'play' }}"></i>
                                        </a>
                                        
                                        {{-- Botón eliminar --}}
                                        <a 
                                            href="{{ url('/delete-override/' . $override->id) }}" 
                                            class="btn btn-sm btn-danger"
                                            onclick="return confirm('¿Está seguro de eliminar esta programación especial?')"
                                            title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                                    <i class="fas fa-calendar-times text-5xl mb-4 opacity-50 block"></i>
                                    <p class="font-medium">No hay programación especial configurada</p>
                                    <p class="text-sm mt-1">Haz clic en "Nueva Programación Especial" para agregar una</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        @if (isset($overrides) && $overrides->hasPages())
            <div class="card-footer p-4 border-t border-slate-200 dark:border-slate-700">
                {{ $overrides->links() }}
            </div>
        @endif
    </div>
@endsection

{{-- Modal para agregar nuevo override --}}
@push('scripts')
<section id="addOverrideModal" class="tw-modal" aria-modal="true" role="dialog" aria-hidden="true">
    <div class="tw-modal-panel">
        <div class="tw-modal-header">
            <h3 class="text-lg font-semibold">Nueva Programación Especial</h3>
            <button data-modal-close class="btn btn-ghost" aria-label="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <form action="{{ url('add-override') }}" method="POST">
            @csrf
            <div class="tw-modal-body">
                <div class="space-y-4">
                    {{-- Fecha --}}
                    <div>
                        <label for="date" class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Fecha del evento especial
                        </label>
                        <input 
                            type="date" 
                            id="date" 
                            name="date" 
                            class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white"
                            min="{{ date('Y-m-d') }}"
                            required>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Selecciona la fecha en la que quieres usar programación alternativa
                        </p>
                    </div>

                    {{-- Día de programación a usar --}}
                    <div>
                        <label for="override_day" class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Usar programación de
                        </label>
                        <select 
                            id="override_day" 
                            name="override_day" 
                            class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white"
                            required>
                            <option value="">Seleccionar día...</option>
                            <option value="1">Lunes</option>
                            <option value="2">Martes</option>
                            <option value="3">Miércoles</option>
                            <option value="4">Jueves</option>
                            <option value="5">Viernes</option>
                            <option value="6">Sábado</option>
                            <option value="7">Domingo</option>
                        </select>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Ejemplo: Si el 11 de noviembre es martes pero quieres usar la programación de sábado, selecciona "Sábado"
                        </p>
                    </div>

                    {{-- Motivo --}}
                    <div>
                        <label for="reason" class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Motivo (opcional)
                        </label>
                        <input 
                            type="text" 
                            id="reason" 
                            name="reason" 
                            placeholder="Ej: Día de la Independencia"
                            class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Describe el motivo de la programación especial
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="tw-modal-footer">
                <button type="button" data-modal-close class="btn">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</section>
@endpush
