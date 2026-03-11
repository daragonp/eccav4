@extends('layouts.panel')

@section('title', 'Días Festivos y Programación Especial')
@section('pageheading', 'Programación Especial')

@section('datatable')
    {{-- Tarjeta de información --}}
    <div class="card mb-6">
        <div class="card-body p-4">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                    <i class="fa-solid fa-calendar-days text-purple-600 dark:text-purple-400"></i>
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
                                        {{-- Botón editar --}}
                                        <button 
                                            type="button"
                                            class="btn btn-sm btn-info"
                                            onclick="openEditModal({{ json_encode($override) }})"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- Botón duplicar --}}
                                        <button 
                                            type="button"
                                            class="btn btn-sm btn-secondary"
                                            onclick="openDuplicateModal({{ json_encode($override) }})"
                                            title="Duplicar">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                        
                                        {{-- Botón toggle activo/inactivo --}}
                                        <form
                                            action="{{ url('/toggle-override/' . $override->id) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('¿Desea {{ $override->is_active ? 'desactivar' : 'activar' }} esta programación especial?');">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="btn btn-sm {{ $override->is_active ? 'btn-warning' : 'btn-success' }}"
                                                title="{{ $override->is_active ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas fa-{{ $override->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        
                                        {{-- Botón eliminar --}}
                                        <form
                                            action="{{ url('/delete-override/' . $override->id) }}"
                                            method="POST"
                                            class="inline"
                                            onsubmit="return confirm('¿Está seguro de eliminar esta programación especial?');">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="btn btn-sm btn-danger"
                                                title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
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
<div id="backdrop" class="backdrop"></div>

{{-- Modal: Agregar --}}
<div id="addOverrideModal" class="tw-modal">
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
</div>

{{-- Modal: Editar --}}
<div id="editOverrideModal" class="tw-modal">
    <div class="tw-modal-panel">
        <div class="tw-modal-header">
            <h3 class="text-lg font-semibold">Editar Programación Especial</h3>
            <button data-modal-close class="btn btn-ghost" aria-label="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <form id="editOverrideForm" method="POST">
            @csrf
            @method('POST')
            <div class="tw-modal-body">
                <div class="space-y-4">
                    {{-- Fecha --}}
                    <div>
                        <label for="edit_date" class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Fecha del evento especial
                        </label>
                        <input 
                            type="date" 
                            id="edit_date" 
                            name="date" 
                            class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white"
                            required>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Selecciona la fecha en la que quieres usar programación alternativa
                        </p>
                    </div>

                    {{-- Día de programación a usar --}}
                    <div>
                        <label for="edit_override_day" class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Usar programación de
                        </label>
                        <select 
                            id="edit_override_day" 
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
                        <label for="edit_reason" class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Motivo (opcional)
                        </label>
                        <input 
                            type="text" 
                            id="edit_reason" 
                            name="reason" 
                            placeholder="Ej: Día de la Independencia"
                            class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white">
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Describe el motivo de la programación especial
                        </p>
                    </div>

                    {{-- Estado --}}
                    <div>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input 
                                type="checkbox" 
                                id="edit_is_active" 
                                name="is_active" 
                                value="1"
                                class="rounded border-slate-300 dark:border-slate-700 text-blue-600 focus:ring-blue-500">
                            <span class="text-sm font-medium text-slate-700 dark:text-slate-300">
                                Programación activa
                            </span>
                        </label>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Solo las programaciones activas se aplicarán en las fechas configuradas
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="tw-modal-footer">
                <button type="button" data-modal-close class="btn">Cancelar</button>
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Duplicar --}}
<div id="duplicateOverrideModal" class="tw-modal">
    <div class="tw-modal-panel">
        <div class="tw-modal-header">
            <h3 class="text-lg font-semibold">Duplicar Programación Especial</h3>
            <button data-modal-close class="btn btn-ghost" aria-label="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        
        <form id="duplicateOverrideForm" method="POST">
            @csrf
            <div class="tw-modal-body">
                <div class="space-y-4">
                    {{-- Información original --}}
                    <div class="p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded">
                        <p class="text-sm text-blue-800 dark:text-blue-300">
                            <i class="fas fa-info-circle mr-1"></i>
                            Se creará una copia con los datos de la programación original. Puedes modificar cualquier campo antes de guardar.
                        </p>
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label for="duplicate_date" class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Nueva fecha del evento especial
                        </label>
                        <input 
                            type="date" 
                            id="duplicate_date" 
                            name="date" 
                            class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-slate-900 dark:text-white"
                            min="{{ date('Y-m-d') }}"
                            required>
                        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                            Selecciona una nueva fecha (debe ser diferente a la original)
                        </p>
                    </div>

                    {{-- Día de programación a usar --}}
                    <div>
                        <label for="duplicate_override_day" class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Usar programación de
                        </label>
                        <select 
                            id="duplicate_override_day" 
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
                    </div>

                    {{-- Motivo --}}
                    <div>
                        <label for="duplicate_reason" class="block text-sm font-medium mb-1 text-slate-700 dark:text-slate-300">
                            Motivo (opcional)
                        </label>
                        <input 
                            type="text" 
                            id="duplicate_reason" 
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
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-copy mr-1"></i>
                    Duplicar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Abrir modal de edición con los datos del override
     */
    function openEditModal(override) {
        // Formatear la fecha para el input tipo date (YYYY-MM-DD)
        const dateObj = new Date(override.date);
        const formattedDate = dateObj.toISOString().split('T')[0];
        
        // Llenar los campos del formulario
        document.getElementById('edit_date').value = formattedDate;
        document.getElementById('edit_override_day').value = override.override_day;
        document.getElementById('edit_reason').value = override.reason || '';
        document.getElementById('edit_is_active').checked = override.is_active;
        
        // Configurar la acción del formulario
        document.getElementById('editOverrideForm').action = '/update-override/' + override.id;
        
        // Abrir el modal usando el sistema de admin.js
        const modal = document.getElementById('editOverrideModal');
        const backdrop = document.getElementById('backdrop');
        
        document.querySelectorAll('.tw-modal.open').forEach(m => m.classList.remove('open'));
        backdrop.classList.add('open');
        modal.classList.add('open');
    }

    /**
     * Abrir modal de duplicación con los datos del override
     */
    function openDuplicateModal(override) {
        // NO llenar la fecha (el usuario debe elegir una nueva)
        document.getElementById('duplicate_date').value = '';
        
        // Llenar el día de programación y motivo con los valores originales
        document.getElementById('duplicate_override_day').value = override.override_day;
        document.getElementById('duplicate_reason').value = override.reason || '';
        
        // Configurar la acción del formulario
        document.getElementById('duplicateOverrideForm').action = '/duplicate-override/' + override.id;
        
        // Abrir el modal usando el sistema de admin.js
        const modal = document.getElementById('duplicateOverrideModal');
        const backdrop = document.getElementById('backdrop');
        
        document.querySelectorAll('.tw-modal.open').forEach(m => m.classList.remove('open'));
        backdrop.classList.add('open');
        modal.classList.add('open');
    }
</script>
@endpush
