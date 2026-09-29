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
        
        @if(isset($errors) && $errors->has('day'))
            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $errors->first('day') }}</p>
        @endif
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
    {{-- Tarjeta de Programa En Vivo --}}
    @if(isset($currentProgram) && $currentProgram)
    <div class="mb-6 rounded-2xl bg-linear-to-r from-emerald-900 via-slate-900 to-teal-950 p-5 text-white shadow-lg border border-emerald-500/30 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="relative shrink-0">
                @if ($currentProgram->image)
                    <img src="{{ asset('images/schedule/' . $currentProgram->image) }}" alt="{{ $currentProgram->name }}" class="w-14 h-14 rounded-xl object-cover ring-2 ring-emerald-400/50 shadow-md">
                @else
                    <div class="w-14 h-14 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400 text-2xl ring-2 ring-emerald-400/50">
                        <i class="fa-solid fa-tower-broadcast"></i>
                    </div>
                @endif
                <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500"></span>
                </span>
            </div>
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                        <i class="fa-solid fa-circle text-[6px] animate-pulse"></i> Transmitiendo Al Aire Ahora
                    </span>
                    <span class="text-xs text-slate-400">
                        {{ is_object($currentProgram->start) ? $currentProgram->start->format('H:i') : substr($currentProgram->start, 0, 5) }} - 
                        {{ is_object($currentProgram->end) ? $currentProgram->end->format('H:i') : substr($currentProgram->end, 0, 5) }}
                    </span>
                </div>
                <h3 class="text-lg font-bold text-white mt-1">{{ $currentProgram->name }}</h3>
                <p class="text-xs text-slate-300">Conduce: <span class="font-medium text-emerald-400">{{ $currentProgram->host ?? 'Equipo ECCA' }}</span></p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <a href="https://a12.asurahosting.com/station/199/" target="_blank" rel="noopener noreferrer" class="btn btn-primary text-xs py-2 px-3.5 inline-flex items-center gap-2 shadow-xs">
                <i class="fa-solid fa-sliders"></i>
                <span>Consola Asura</span>
            </a>
            <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary text-xs py-2 px-3.5 inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 border-white/10 text-white">
                <i class="fa-solid fa-play"></i>
                <span>Escuchar en Vivo</span>
            </a>
        </div>
    </div>
    @endif

    {{-- Filtro de Días de la Semana --}}
    @php
        $daysMap = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];
        $selectedDay = request('day');
    @endphp
    <div class="flex items-center gap-1.5 overflow-x-auto pb-2 mb-4 scrollbar-none">
        <a href="{{ url()->current() . (request('search') ? '?search='.request('search') : '') }}" 
           class="px-3 py-1.5 text-xs font-semibold rounded-lg shrink-0 transition-all {{ empty($selectedDay) ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700' }}">
            Todos los días
        </a>
        @foreach($daysMap as $dayNum => $dayLabel)
            <a href="{{ url()->current() }}?day={{ $dayNum }}{{ request('search') ? '&search='.request('search') : '' }}" 
               class="px-3 py-1.5 text-xs font-semibold rounded-lg shrink-0 transition-all {{ $selectedDay == $dayNum ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 border border-slate-200 dark:border-slate-700' }}">
                {{ $dayLabel }}
            </a>
        @endforeach
    </div>

    {{-- Buscador y Controles --}}
    <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row gap-2 mb-4">
        @if(request('day'))
            <input type="hidden" name="day" value="{{ request('day') }}">
        @endif
        <div class="relative flex-1">
            <input 
                type="text" 
                name="search" 
                value="{{ request('search') }}" 
                placeholder="Buscar programas por nombre o conductor(a)..." 
                class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-10 py-2.5 text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500"
            >
            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                <i class="fa-solid fa-magnifying-glass"></i>
            </div>
        </div>
        <button type="submit" class="btn btn-secondary px-5">Buscar</button>
        @if(request('search') || request('day'))
            <a href="{{ url()->current() }}" class="btn btn-ghost">Limpiar Filtros</a>
        @endif
    </form>

    {{-- Tarjeta de la tabla --}}
    <div class="card">
        <div class="card-body p-0">
            <div class="table-wrap">
                <table class="w-full">
                    <thead class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Programa & Director</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Horario</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Días de Emisión</th>
                            <th class="px-4 py-3.5 text-left text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Estado</th>
                            <th class="px-4 py-3.5 text-center text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                        @forelse ($schedules as $schedule)
                            @php
                                $daysSelected = !empty($schedule->days_list) 
                                    ? explode(',', $schedule->days_list) 
                                    : \App\Models\Schedule::where('emission_key', $schedule->emission_key)->pluck('day')->toArray();
                                $dayNames = [1 => 'Lu', 2 => 'Ma', 3 => 'Mi', 4 => 'Ju', 5 => 'Vi', 6 => 'Sa', 7 => 'Do'];
                                $isCurrentLive = isset($currentProgram) && $currentProgram && ($currentProgram->emission_key === $schedule->emission_key);
                            @endphp
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors {{ $isCurrentLive ? 'bg-emerald-50/50 dark:bg-emerald-950/20' : '' }}">
                                <td class="px-4 py-3.5 text-sm">
                                    <div class="flex items-center gap-3">
                                        @if ($schedule->image)
                                            <img src="{{ asset('images/schedule/' . $schedule->image) }}" alt="{{ $schedule->name }}" class="w-10 h-10 rounded-xl object-cover shrink-0 ring-1 ring-slate-200 dark:ring-slate-700">
                                        @else
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 flex items-center justify-center shrink-0">
                                                <i class="fa-solid fa-radio text-sm"></i>
                                            </div>
                                        @endif
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <div class="font-bold text-slate-900 dark:text-white text-sm truncate">{{ $schedule->name }}</div>
                                                @if($isCurrentLive)
                                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-600 text-white animate-pulse">
                                                        <i class="fa-solid fa-tower-broadcast text-[8px]"></i> EN VIVO
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $schedule->host }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fa-regular fa-clock text-slate-400 text-xs"></i>
                                        <span>{{ is_object($schedule->start) ? $schedule->start->format('H:i') : substr($schedule->start, 0, 5) }}</span>
                                        <span class="text-slate-400">-</span>
                                        <span>{{ is_object($schedule->end) ? $schedule->end->format('H:i') : substr($schedule->end, 0, 5) }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-sm">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($daysSelected as $d)
                                            @if (isset($dayNames[$d]))
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                                    {{ $dayNames[$d] }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 text-sm">
                                    @if ($schedule->deleted_at)
                                        <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700">
                                            <i class="fa-solid fa-ban mr-1"></i> Inactivo
                                        </span>
                                    @else
                                        <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700">
                                            <i class="fa-solid fa-check mr-1"></i> Activo
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-sm text-center">
                                    @include('admin.partials.actions', [
                                        'id'         => $schedule->id,
                                        'view'       => url("view-schedule", $schedule->id),
                                        'activate'   => url("activate-schedule", $schedule->id),
                                        'softdelete' => url("delete-schedule", $schedule->id),
                                        'realdelete' => url("realdelete-schedule", $schedule->id),
                                        'formAction' => url("update-schedule", $schedule->id),
                                        'tableM'     => $schedule,
                                        'sectionType' => 'schedule',
                                        'sectionTitle' => 'Programa',
                                        'daysSelected' => $daysSelected,
                                    ])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                                    <i class="fa-solid fa-calendar-xmark text-4xl mb-3 opacity-50 block"></i>
                                    <p class="font-medium">No se encontraron programas para los filtros seleccionados</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if ($schedules->hasPages())
        <div class="p-4 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 rounded-xl shadow-xs mt-4">
            {{ $schedules->links() }}
        </div>
    @endif
@endsection
