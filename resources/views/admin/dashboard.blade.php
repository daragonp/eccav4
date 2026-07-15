@extends('layouts.panel')

@section('title', 'Panel principal')
@section('pageheading', 'Panel principal')
@section('noCard', true)

@section('datatable')
<!-- Panel de control moderno -->
<div class="space-y-6">

    {{-- Widget de Programa en Emisión (On-Air) --}}
    @if($currentProgram)
    <div class="relative overflow-hidden bg-linear-to-r from-emerald-500 to-teal-600 text-white rounded-2xl p-4 sm:p-6 shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex items-start sm:items-center gap-4 z-10">
            <div class="p-3 bg-white/20 rounded-xl animate-pulse shrink-0">
                <i class="fas fa-radio text-xl sm:text-2xl text-white"></i>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-red-500 text-white text-[10px] font-bold uppercase tracking-wider mb-1">
                    <span class="w-1.5 h-1.5 rounded-full bg-white animate-ping"></span> Al Aire
                </span>
                <h2 class="text-lg sm:text-xl font-bold">{{ $currentProgram->name }}</h2>
                <p class="text-xs sm:text-sm text-emerald-100 mt-0.5">Con <strong>{{ $currentProgram->host ?? 'Música Continua' }}</strong> — Hoy de {{ $currentProgram->start }} a {{ $currentProgram->end }}</p>
            </div>
        </div>
        <div class="flex items-center justify-between sm:justify-start gap-2 z-10 w-full sm:w-auto pt-2 sm:pt-0 border-t border-white/10 sm:border-0">
            <span class="text-xs px-2.5 py-1.5 bg-white/25 rounded-lg font-medium">Duración: {{ $currentProgram->duration }} min</span>
            <a href="{{ url('show-schedule') }}" class="px-4 py-1.5 bg-white text-emerald-700 hover:bg-emerald-50 rounded-lg text-xs font-semibold shadow-sm transition-all">Ver Programación</a>
        </div>
    </div>
    @else
    <div class="relative overflow-hidden bg-linear-to-r from-slate-700 to-slate-800 text-white rounded-2xl p-4 sm:p-6 shadow-md flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-2xl pointer-events-none"></div>
        <div class="flex items-start sm:items-center gap-4 z-10">
            <div class="p-3 bg-white/15 rounded-xl shrink-0">
                <i class="fas fa-music text-xl sm:text-2xl text-slate-300"></i>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-slate-600 text-slate-300 text-[10px] font-bold uppercase tracking-wider mb-1">
                    Radio Online
                </span>
                <h2 class="text-lg sm:text-xl font-bold text-slate-100">Transmisión Continua</h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-0.5">No hay programas en emisión en este momento. Disfruta de nuestra música continua 24/7.</p>
            </div>
        </div>
        <div class="z-10 w-full sm:w-auto pt-2 sm:pt-0 border-t border-slate-700 sm:border-0 flex justify-end">
            <a href="{{ url('show-schedule') }}" class="px-4 py-1.5 bg-slate-600 hover:bg-slate-500 text-white rounded-lg text-xs font-semibold transition-all w-full sm:w-auto text-center">Ver Programación completa</a>
        </div>
    </div>
    @endif

    <!-- Resumen de estadísticas -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- Tarjeta de Usuarios -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 left-0 w-2 h-full bg-blue-500"></div>
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <div class="p-2 sm:p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <i class="fas fa-users text-sm sm:text-lg text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Usuarios</h3>
                            <div class="flex items-baseline space-x-1 sm:space-x-2">
                                <span class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['users'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('show-users') }}" class="p-1 sm:p-2 text-slate-400 hover:text-blue-600 dark:text-slate-500 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                        <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                    </a>
                </div>
                <div class="mt-4 hidden sm:block">
                    <div class="h-2 bg-blue-50 dark:bg-blue-900/20 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: 75%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $stats['users_change'] ?? 'Usuarios en el sistema' }}</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Versículos -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 left-0 w-2 h-full bg-green-500"></div>
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <div class="p-2 sm:p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                            <i class="fas fa-book-bible text-sm sm:text-lg text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Versículos</h3>
                            <div class="flex items-baseline space-x-1 sm:space-x-2">
                                <span class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['verses'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('show-quote') }}" class="p-1 sm:p-2 text-slate-400 hover:text-green-600 dark:text-slate-500 dark:hover:text-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                        <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                    </a>
                </div>
                <div class="mt-4 hidden sm:block">
                    <div class="h-2 bg-green-50 dark:bg-green-900/20 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: 65%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">{{ $stats['verses_change'] ?? 'Versículos publicados' }}</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Cultos Dominicales -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 left-0 w-2 h-full bg-amber-500"></div>
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <div class="p-2 sm:p-3 bg-amber-100 dark:bg-amber-900/30 rounded-xl">
                            <i class="fas fa-church text-sm sm:text-lg text-amber-600 dark:text-amber-400"></i>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Cultos</h3>
                            <div class="flex items-baseline space-x-1 sm:space-x-2">
                                <span class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['worships'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('show-worship') }}" class="p-1 sm:p-2 text-slate-400 hover:text-amber-600 dark:text-slate-500 dark:hover:text-amber-400 rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900/20 transition-colors">
                        <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                    </a>
                </div>
                <div class="mt-4 hidden sm:block">
                    <div class="h-2 bg-amber-50 dark:bg-amber-900/20 rounded-full overflow-hidden">
                        <div class="h-full bg-amber-500 rounded-full" style="width: 80%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Mensajes del culto grabados</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Programación -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 left-0 w-2 h-full bg-purple-500"></div>
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <div class="p-2 sm:p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                            <i class="fas fa-clock text-sm sm:text-lg text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Programas</h3>
                            <div class="flex items-baseline space-x-1 sm:space-x-2">
                                <span class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['schedules'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('show-schedule') }}" class="p-1 sm:p-2 text-slate-400 hover:text-purple-600 dark:text-slate-500 dark:hover:text-purple-400 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors">
                        <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                    </a>
                </div>
                <div class="mt-4 hidden sm:block">
                    <div class="h-2 bg-purple-50 dark:bg-purple-900/20 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: 85%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Programas radiales activos</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Noticias -->
        <div class="stat-card col-span-2 sm:col-span-1 relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 left-0 w-2 h-full bg-indigo-500"></div>
            <div class="p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <div class="p-2 sm:p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                            <i class="fas fa-newspaper text-sm sm:text-lg text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div>
                            <h3 class="text-xs sm:text-sm font-medium text-slate-500 dark:text-slate-400">Noticias</h3>
                            <div class="flex items-baseline space-x-1 sm:space-x-2">
                                <span class="text-lg sm:text-2xl font-bold text-slate-900 dark:text-white">{{ $stats['news'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ url('show-news') }}" class="p-1 sm:p-2 text-slate-400 hover:text-indigo-600 dark:text-slate-500 dark:hover:text-indigo-400 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                        <i class="fas fa-chevron-right text-xs sm:text-sm"></i>
                    </a>
                </div>
                <div class="mt-4 hidden sm:block">
                    <div class="h-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full" style="width: 92%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Noticias y boletines</p>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Sección de contenido principal -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Últimos versículos mejorados -->
    <div class="card group hover:shadow-lg transition-all duration-300">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <i class="fas fa-book-bible text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Últimos versículos</h3>
                </div>
                <a href="{{ url('show-quote') }}" class="chip-brand group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-book-bible mr-1"></i> Ver todo
                </a>
            </div>

            <!-- Vista de Escritorio -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm border-b border-slate-200 dark:border-slate-700">
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Fecha</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Imagen</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">PDF</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($latestVerses as $v)
                        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group/row">
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-900 dark:text-slate-200">{{ \Carbon\Carbon::parse($v->date)->format('d/m') }}</span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($v->date)->format('Y') }}</span>
                                </div>
                            </td>
                            <td class="py-3">
                                @if($v->image)
                                    <div class="relative group/image">
                                        <img src="{{ asset('images/bible/'.$v->image) }}" alt="" class="w-10 h-10 rounded object-cover group-hover/image:scale-110 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/50 rounded opacity-0 group-hover/image:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <i class="fas fa-search-plus text-white text-xs"></i>
                                        </div>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded bg-slate-200 dark:bg-slate-700 flex items-center justify-center">
                                        <i class="fas fa-image text-slate-400 text-xs"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="py-3">
                                @if($v->video)
                                    <a href="{{ asset('documents/quote/'.$v->video) }}" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded text-xs hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>PDF</span>
                                    </a>
                                @else
                                    <span class="text-slate-400 text-xs">—</span>
                                @endif
                            </td>
                            <td class="py-3">
                                <span class="chip-success">
                                    <i class="fas fa-check-circle text-[10px]"></i>
                                    Activo
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-bible text-slate-300 dark:text-slate-600 text-2xl"></i>
                                    <span class="text-slate-500 dark:text-slate-400">Sin versículos registrados</span>
                                    <a href="{{ url('show-quote') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Añadir primer versículo</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Vista Móvil -->
            <div class="block sm:hidden space-y-3">
                @forelse ($latestVerses as $v)
                <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/10 shadow-sm flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        @if($v->image)
                            <div class="relative w-12 h-12 rounded overflow-hidden shadow-sm">
                                <img src="{{ asset('images/bible/'.$v->image) }}" alt="" class="w-full h-full object-cover">
                            </div>
                        @else
                            <div class="w-12 h-12 rounded bg-slate-200 dark:bg-slate-700 flex items-center justify-center shadow-inner">
                                <i class="fas fa-image text-slate-400 text-sm"></i>
                            </div>
                        @endif
                        <div>
                            <div class="text-sm font-semibold text-slate-900 dark:text-white">
                                {{ \Carbon\Carbon::parse($v->date)->format('d/m/Y') }}
                            </div>
                            <div class="mt-1 flex items-center">
                                <span class="chip-success py-0.5 px-2 text-[10px] scale-90 -ml-1">
                                    <i class="fas fa-check-circle text-[8px] mr-1"></i>Activo
                                </span>
                            </div>
                        </div>
                    </div>
                    <div>
                        @if($v->video)
                            <a href="{{ asset('documents/quote/'.$v->video) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-lg text-xs font-semibold hover:bg-blue-200 transition-colors">
                                <i class="fas fa-file-pdf"></i>
                                <span>PDF</span>
                            </a>
                        @endif
                    </div>
                </div>
                @empty
                <div class="py-8 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-bible text-slate-300 dark:text-slate-600 text-2xl"></i>
                        <span class="text-slate-500 dark:text-slate-400">Sin versículos registrados</span>
                        <a href="{{ url('show-quote') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Añadir primer versículo</a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Últimas noticias mejoradas -->
    <div class="card group hover:shadow-lg transition-all duration-300">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <i class="fas fa-newspaper text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Últimas noticias</h3>
                </div>
                <a href="{{ url('show-news') }}" class="chip-brand group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-newspaper mr-1"></i> Ver todo
                </a>
            </div>

            <div class="space-y-3">
                @forelse ($latestNews as $n)
                <div class="group/item p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300 cursor-pointer">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <h4 class="font-medium text-slate-900 dark:text-white truncate group-hover/item:text-blue-600 dark:group-hover/item:text-blue-400 transition-colors">
                                {{ $n->title }}
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                <i class="far fa-clock mr-1"></i>
                                {{ $n->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <a href="{{ url('view-news/'.$n->id) }}" class="btn-action btn-action-info" title="Ver noticia">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="{{ url('edit-news/'.$n->id) }}" class="btn-action btn-action-secondary" title="Editar noticia">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-newspaper text-slate-300 dark:text-slate-600 text-2xl"></i>
                        <span class="text-slate-500 dark:text-slate-400">Sin noticias publicadas</span>
                        <a href="{{ url('show-news') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Publicar primera noticia</a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Últimos cultos dominicales -->
    <div class="card col-span-1 lg:col-span-2 group hover:shadow-lg transition-all duration-300 mt-6">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-amber-100 dark:bg-amber-900/30 rounded-lg">
                        <i class="fas fa-church text-amber-600 dark:text-amber-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Últimos cultos dominicales</h3>
                </div>
                <a href="{{ url('show-worship') }}" class="chip-brand group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-church mr-1"></i> Ver todo
                </a>
            </div>

            <!-- Vista de Escritorio -->
            <div class="hidden sm:block overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm border-b border-slate-200 dark:border-slate-700">
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Culto / Título</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Fecha</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Autor</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Recursos</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Procesado IA</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse ($latestWorships as $w)
                        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group/row">
                            <td class="py-3 font-medium text-slate-900 dark:text-slate-200">
                                {{ $w->title }}
                            </td>
                            <td class="py-3 text-slate-600 dark:text-slate-400">
                                {{ $w->broadcast ? $w->broadcast->format('d/m/Y') : '—' }}
                            </td>
                            <td class="py-3 text-slate-600 dark:text-slate-400">
                                {{ $w->autor ?? '—' }}
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-1.5">
                                    @if($w->audio)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400" title="Tiene Audio">
                                            <i class="fas fa-volume-up text-xs"></i>
                                        </span>
                                    @endif
                                    @if($w->video || $w->urlyt)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400" title="Tiene Video">
                                            <i class="fab fa-youtube text-xs"></i>
                                        </span>
                                    @endif
                                    @if($w->pdfdoc)
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400" title="Tiene PDF">
                                            <i class="fas fa-file-pdf text-xs"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                @if($w->ai_processed)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-350 font-medium">
                                        <i class="fas fa-brain text-[10px]"></i>
                                        <span>Procesado</span>
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 font-medium">
                                        <i class="fas fa-minus text-[10px]"></i>
                                        <span>No</span>
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-church text-slate-300 dark:text-slate-600 text-2xl"></i>
                                    <span class="text-slate-500 dark:text-slate-400">Sin cultos registrados</span>
                                    <a href="{{ url('show-worship') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Añadir primer culto</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Vista Móvil -->
            <div class="block sm:hidden space-y-3">
                @forelse ($latestWorships as $w)
                <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/10 shadow-sm space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h4 class="font-semibold text-sm text-slate-900 dark:text-white truncate">
                                {{ $w->title }}
                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                <i class="far fa-calendar-alt mr-1"></i>
                                {{ $w->broadcast ? $w->broadcast->format('d/m/Y') : '—' }} • {{ $w->autor ?? 'Sin autor' }}
                            </p>
                        </div>
                        <div class="shrink-0">
                            @if($w->ai_processed)
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-350 font-medium">
                                    <i class="fas fa-brain text-[8px]"></i>
                                    <span>IA</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400 font-medium">
                                    <i class="fas fa-minus text-[8px]"></i>
                                    <span>No IA</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between border-t border-slate-200/50 dark:border-slate-700/50 pt-2.5 mt-1">
                        <span class="text-xs font-medium text-slate-500 dark:text-slate-400">Recursos:</span>
                        <div class="flex items-center gap-2">
                            @if($w->audio)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400" title="Audio">
                                    <i class="fas fa-volume-up text-xs"></i>
                                </span>
                            @endif
                            @if($w->video || $w->urlyt)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400" title="Video">
                                    <i class="fab fa-youtube text-xs"></i>
                                </span>
                            @endif
                            @if($w->pdfdoc)
                                <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400" title="PDF">
                                    <i class="fas fa-file-pdf text-xs"></i>
                                </span>
                            @endif
                            @if(!$w->audio && !$w->video && !$w->urlyt && !$w->pdfdoc)
                                <span class="text-xs text-slate-400 dark:text-slate-500">—</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-8 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-church text-slate-300 dark:text-slate-600 text-2xl"></i>
                        <span class="text-slate-500 dark:text-slate-400">Sin cultos registrados</span>
                        <a href="{{ url('show-worship') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Añadir primer culto</a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Manejador para botones de acciones rápidas
    // ========================================
    document.querySelectorAll('.quick-action-btn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    });

    // ========================================
    // Animación de entrada para las tarjetas
    // ========================================
    const cards = document.querySelectorAll('.stat-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';

        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // ========================================
    // Efecto de actualización automática de estadísticas
    // ========================================
    const updateStats = () => {
        const statNumbers = document.querySelectorAll('.text-2xl');
        statNumbers.forEach(stat => {
            const finalValue = stat.textContent;
            const numValue = parseInt(finalValue.replace(/[^0-9]/g, '')) || 0;
            let currentValue = 0;
            const increment = numValue / 30;

            const updateNumber = () => {
                if (currentValue < numValue) {
                    currentValue += increment;
                    stat.textContent = Math.floor(currentValue).toLocaleString();
                    requestAnimationFrame(updateNumber);
                } else {
                    stat.textContent = finalValue;
                }
            };

            updateNumber();
        });
    };

    // Iniciar animación de números
    setTimeout(updateStats, 500);

    // ========================================
    // Efecto hover en las filas de las tablas
    // ========================================
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
            this.style.transition = 'transform 0.2s ease';
        });

        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
});
</script>
@endpush
