@extends('layouts.panel')

@section('title', 'Panel principal')
@section('pageheading', 'Panel principal')
@section('noCard', true)

@section('datatable')
<div class="space-y-6">

    {{-- ========================================================================= --}}
    {{-- 1. COCKPIT ON-AIR: REPRODUCTOR EN VIVO Y ESTADO DE EMISIÓN               --}}
    {{-- ========================================================================= --}}
    @if($currentProgram)
    <div class="relative overflow-hidden rounded-3xl bg-linear-to-r from-emerald-600 via-teal-600 to-cyan-700 text-white shadow-xl p-5 sm:p-7 border border-emerald-400/30">
        {{-- Elementos decorativos de fondo --}}
        <div class="absolute -right-16 -top-16 w-56 h-56 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-1/3 -bottom-10 w-48 h-48 bg-teal-400/10 rounded-full blur-2xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            {{-- Info Programa Actual --}}
            <div class="flex items-start sm:items-center gap-4 sm:gap-5 flex-1 min-w-0">
                <div class="relative shrink-0">
                    @if($currentProgram->image_url)
                        <img src="{{ $currentProgram->image_url }}" alt="{{ $currentProgram->name }}" class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover shadow-lg border-2 border-white/20">
                    @else
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-white shadow-lg border border-white/20">
                            <i class="fas fa-radio text-2xl sm:text-3xl text-emerald-100"></i>
                        </div>
                    @endif
                    <span class="absolute -bottom-1 -right-1 flex h-4 w-4">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-white"></span>
                    </span>
                </div>

                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-red-500/90 text-white text-[11px] font-bold uppercase tracking-wider shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span> Al Aire
                        </span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-white/20 backdrop-blur-xs text-white font-medium">
                            <i class="far fa-clock mr-1"></i> {{ $currentProgram->start_formatted ?? $currentProgram->start }} — {{ $currentProgram->end_formatted ?? $currentProgram->end }}
                        </span>
                        <span class="text-xs px-2.5 py-0.5 rounded-full bg-emerald-900/40 text-emerald-100 font-medium">
                            Duración: {{ $currentProgram->duration }} min
                        </span>
                    </div>

                    <h2 class="text-xl sm:text-2xl font-black tracking-tight text-white truncate drop-shadow-xs">
                        {{ $currentProgram->name }}
                    </h2>
                    <p class="text-xs sm:text-sm text-emerald-100 mt-1 truncate">
                        Con <strong class="text-white">{{ $currentProgram->host ?? 'Equipo ECCA' }}</strong>
                        @if($currentProgram->about)
                            — <span class="opacity-90">{{ $currentProgram->about }}</span>
                        @endif
                    </p>
                </div>
            </div>

            {{-- Reproductor Integrado y Siguiente Programa --}}
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 shrink-0 lg:border-l lg:border-white/20 lg:pl-6">
                {{-- Botón de Play Streaming en Vivo --}}
                <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md rounded-2xl p-2 sm:px-4 sm:py-2.5 border border-white/20 shadow-inner">
                    <button id="dashboardLivePlayBtn" class="w-12 h-12 rounded-xl bg-white text-emerald-700 hover:bg-emerald-50 active:scale-95 flex items-center justify-center shadow-md transition-all group cursor-pointer" title="Escuchar emisión en vivo">
                        <i id="dashboardPlayIcon" class="fas fa-play text-lg ml-0.5 transition-transform group-hover:scale-110"></i>
                    </button>
                    <div>
                        <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-100 flex items-center gap-1.5">
                            <span>Radio ECCA</span>
                            <div class="live-equalizer flex items-end gap-0.5 h-3">
                                <span class="eq-bar w-0.5 bg-emerald-200 rounded-full h-1"></span>
                                <span class="eq-bar w-0.5 bg-emerald-200 rounded-full h-3"></span>
                                <span class="eq-bar w-0.5 bg-emerald-200 rounded-full h-2"></span>
                            </div>
                        </div>
                        <span id="dashboardLiveStatusText" class="text-xs font-semibold text-white">Streaming en vivo</span>
                    </div>
                </div>

                {{-- Tarjeta Siguiente Programa --}}
                @if($nextProgram)
                <div class="bg-black/20 backdrop-blur-sm rounded-2xl px-4 py-2.5 border border-white/10 text-left min-w-[180px]">
                    <div class="text-[10px] uppercase font-bold tracking-wider text-teal-200 flex items-center gap-1">
                        <i class="fas fa-forward-step text-[9px]"></i> A continuación:
                    </div>
                    <div class="text-xs font-bold text-white truncate max-w-[190px] mt-0.5">{{ $nextProgram->name }}</div>
                    <div class="text-[11px] text-teal-100">Hoy a las {{ $nextProgram->start_formatted ?? $nextProgram->start }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Barra de Progreso en Vivo --}}
        @if(isset($programProgress))
        <div class="mt-4 pt-3 border-t border-white/15">
            <div class="flex items-center justify-between text-xs text-emerald-100 mb-1.5 font-medium">
                <span>Progreso emisión: {{ $programProgress['elapsed'] }} min transcurridos</span>
                <span>{{ $programProgress['remaining'] }} min restantes ({{ $programProgress['percent'] }}%)</span>
            </div>
            <div class="w-full h-2 bg-black/20 rounded-full overflow-hidden p-0.5">
                <div class="h-full bg-linear-to-r from-amber-300 to-yellow-400 rounded-full transition-all duration-1000 shadow-sm" style="width: {{ $programProgress['percent'] }}%"></div>
            </div>
        </div>
        @endif
    </div>
    @else
    {{-- Estado cuando no hay programa agendado en el minuto exacto --}}
    <div class="relative overflow-hidden rounded-3xl bg-linear-to-r from-slate-800 via-indigo-950 to-slate-900 text-white shadow-xl p-5 sm:p-7 border border-slate-700">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-slate-300 border border-white/10">
                    <i class="fas fa-music text-2xl"></i>
                </div>
                <div>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full bg-indigo-500/80 text-white text-[10px] font-bold uppercase tracking-wider mb-1">
                        Transmisión Continua 24/7
                    </span>
                    <h2 class="text-xl font-bold text-white">Música y Alabanza Continua</h2>
                    <p class="text-xs sm:text-sm text-slate-400 mt-0.5">No hay un programa asignado en este bloque horario. Nuestra señal radial sigue al aire.</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button id="dashboardLivePlayBtn" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-semibold shadow-sm flex items-center gap-2 transition-all cursor-pointer">
                    <i id="dashboardPlayIcon" class="fas fa-play"></i>
                    <span>Escuchar radio</span>
                </button>
                <a href="{{ url('show-schedule') }}" class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-xl text-xs font-semibold transition-all">
                    Ver parrilla completa
                </a>
            </div>
        </div>
    </div>
    @endif

    {{-- Audio nativo invisible para el botón On-Air --}}
    <audio id="dashboardAudioStream" preload="none" src="{{ $streamUrl }}"></audio>

    {{-- ========================================================================= --}}
    {{-- 2. DOCK DE ACCIONES RÁPIDAS (CENTRO DE CONTROL OPERATIVO)                --}}
    {{-- ========================================================================= --}}
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-5 shadow-xs border border-slate-200/80 dark:border-slate-700/80">
        <div class="flex items-center justify-between mb-3.5">
            <h3 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 flex items-center gap-2">
                <i class="fas fa-bolt text-amber-500"></i> Acciones Rápidas
            </h3>
            <span class="text-xs text-slate-400">Publicación directa de contenido</span>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            {{-- Crear Versículo --}}
            <a href="{{ url('show-quote') }}" class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-green-50 dark:bg-slate-700/40 dark:hover:bg-green-950/30 border border-slate-200/70 hover:border-green-300 dark:border-slate-700 dark:hover:border-green-800 transition-all">
                <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/40 text-green-700 dark:text-green-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-book-bible"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-green-700 dark:group-hover:text-green-400 truncate">Palabra de Vida</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate">+ Nuevo versículo</div>
                </div>
            </a>

            {{-- Subir Culto con IA --}}
            <a href="{{ url('show-worship') }}" class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-amber-50 dark:bg-slate-700/40 dark:hover:bg-amber-950/30 border border-slate-200/70 hover:border-amber-300 dark:border-slate-700 dark:hover:border-amber-800 transition-all">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-church"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-amber-700 dark:group-hover:text-amber-400 truncate">Culto Dominical</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate">+ Audio con IA</div>
                </div>
            </a>

            {{-- Redactar Noticia --}}
            <a href="{{ url('show-news') }}" class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-indigo-50 dark:bg-slate-700/40 dark:hover:bg-indigo-950/30 border border-slate-200/70 hover:border-indigo-300 dark:border-slate-700 dark:hover:border-indigo-800 transition-all">
                <div class="w-10 h-10 rounded-lg bg-indigo-100 dark:bg-indigo-900/40 text-indigo-700 dark:text-indigo-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-newspaper"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 truncate">Noticia / Boletín</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate">+ Mensaje semanal</div>
                </div>
            </a>

            {{-- Configurar Festivos / Parrilla --}}
            <a href="{{ url('holiday-schedule') }}" class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-purple-50 dark:bg-slate-700/40 dark:hover:bg-purple-950/30 border border-slate-200/70 hover:border-purple-300 dark:border-slate-700 dark:hover:border-purple-800 transition-all">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <i class="fas fa-calendar-days"></i>
                </div>
                <div class="min-w-0">
                    <div class="text-xs font-bold text-slate-800 dark:text-slate-100 group-hover:text-purple-700 dark:group-hover:text-purple-400 truncate">Días Festivos</div>
                    <div class="text-[11px] text-slate-500 dark:text-slate-400 truncate">Override de parrilla</div>
                </div>
            </a>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- 3. TARJETAS DE ESTADÍSTICAS INTELIGENTES (SMART KPIS)                    --}}
    {{-- ========================================================================= --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <!-- Usuarios -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-xs hover:shadow-md border border-slate-200/80 dark:border-slate-700/80 transition-all duration-300">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>
            <div class="p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="p-2.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-xl">
                        <i class="fas fa-users text-lg"></i>
                    </div>
                    <a href="{{ url('show-users') }}" class="text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 text-xs">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $stats['users'] ?? 0 }}</span>
                    <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Usuarios del sistema</h4>
                </div>
                <div class="mt-2.5 pt-2.5 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                    <span>Cuentas activas</span>
                    <span class="font-semibold text-blue-600 dark:text-blue-400">100%</span>
                </div>
            </div>
        </div>

        <!-- Versículos -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-xs hover:shadow-md border border-slate-200/80 dark:border-slate-700/80 transition-all duration-300">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-green-500"></div>
            <div class="p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="p-2.5 bg-green-50 dark:bg-green-900/30 text-green-600 dark:text-green-400 rounded-xl">
                        <i class="fas fa-book-bible text-lg"></i>
                    </div>
                    <a href="{{ url('show-quote') }}" class="text-slate-400 hover:text-green-600 dark:hover:text-green-400 text-xs">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $stats['verses'] ?? 0 }}</span>
                    <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Palabra de vida</h4>
                </div>
                <div class="mt-2.5 pt-2.5 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                    <span>Publicados este mes</span>
                    <span class="font-semibold text-green-600 dark:text-green-400">+{{ $smartMetrics['verses_this_month'] ?? 0 }}</span>
                </div>
            </div>
        </div>

        <!-- Cultos Dominicales -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-xs hover:shadow-md border border-slate-200/80 dark:border-slate-700/80 transition-all duration-300">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-amber-500"></div>
            <div class="p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="p-2.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl">
                        <i class="fas fa-church text-lg"></i>
                    </div>
                    <a href="{{ url('show-worship') }}" class="text-slate-400 hover:text-amber-600 dark:hover:text-amber-400 text-xs">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $stats['worships'] ?? 0 }}</span>
                    <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Cultos grabados</h4>
                </div>
                <div class="mt-2.5 pt-2.5 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-[11px]">
                    <span class="text-slate-500 dark:text-slate-400">Procesados con IA</span>
                    <span class="inline-flex items-center gap-1 font-semibold text-amber-600 dark:text-amber-400">
                        <i class="fas fa-brain text-[10px]"></i> {{ $smartMetrics['worships_processed_ai'] ?? 0 }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Programación Radial -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-xs hover:shadow-md border border-slate-200/80 dark:border-slate-700/80 transition-all duration-300">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-purple-500"></div>
            <div class="p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="p-2.5 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-xl">
                        <i class="fas fa-clock text-lg"></i>
                    </div>
                    <a href="{{ url('show-schedule') }}" class="text-slate-400 hover:text-purple-600 dark:hover:text-purple-400 text-xs">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ $stats['schedules'] ?? 0 }}</span>
                    <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Bloques radiales</h4>
                </div>
                <div class="mt-2.5 pt-2.5 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                    <span>Asignados hoy</span>
                    <span class="font-semibold text-purple-600 dark:text-purple-400">{{ $smartMetrics['schedules_today'] ?? 0 }} programas</span>
                </div>
            </div>
        </div>

        <!-- Noticias & Podcasts -->
        <div class="stat-card col-span-2 sm:col-span-1 relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-xs hover:shadow-md border border-slate-200/80 dark:border-slate-700/80 transition-all duration-300">
            <div class="absolute top-0 left-0 w-1.5 h-full bg-indigo-500"></div>
            <div class="p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div class="p-2.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-xl">
                        <i class="fas fa-podcast text-lg"></i>
                    </div>
                    <a href="{{ url('show-news') }}" class="text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="mt-3">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">{{ ($stats['news'] ?? 0) + ($stats['podcasts'] ?? 0) }}</span>
                    <h4 class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Noticias y Podcasts</h4>
                </div>
                <div class="mt-2.5 pt-2.5 border-t border-slate-100 dark:border-slate-700/60 flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400">
                    <span>Podcasts: {{ $stats['podcasts'] ?? 0 }}</span>
                    <span>Noticias: {{ $stats['news'] ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Barra de Telemetría y Salud del Sistema --}}
    <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-2.5 bg-slate-100 dark:bg-slate-800/60 rounded-xl text-xs text-slate-600 dark:text-slate-400 border border-slate-200/60 dark:border-slate-700/60">
        <div class="flex items-center gap-3 flex-wrap">
            <span class="inline-flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <strong>Estado:</strong> Sistema Operativo
            </span>
            <span class="hidden sm:inline text-slate-300 dark:text-slate-600">•</span>
            <span><strong>PHP:</strong> {{ $smartMetrics['php_version'] ?? '8.4' }}</span>
            <span class="hidden sm:inline text-slate-300 dark:text-slate-600">•</span>
            <span><strong>Laravel:</strong> v{{ $smartMetrics['laravel_version'] ?? '13' }}</span>
            <span class="hidden sm:inline text-slate-300 dark:text-slate-600">•</span>
            <span><strong>Caché:</strong> Redis</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center gap-1 text-[11px] px-2 py-0.5 rounded-md bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 font-medium">
                <i class="fas fa-shield-halved"></i> Modo Seguro
            </span>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- 4. PARRILLA DEL DÍA: LÍNEA DE TIEMPO INTERACTIVA                         --}}
    {{-- ========================================================================= --}}
    @if(isset($todaySchedule) && $todaySchedule->count() > 0)
    <div class="card overflow-hidden">
        <div class="card-body p-4 sm:p-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-xl text-purple-700 dark:text-purple-300">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 dark:text-white text-base">Parrilla Radial de Hoy</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Programación completa sincronizada para el día en curso</p>
                    </div>
                </div>

                {{-- Filtro de la parrilla --}}
                <div class="flex items-center gap-1 bg-slate-100 dark:bg-slate-800 p-1 rounded-xl text-xs">
                    <button type="button" class="schedule-filter-btn px-3 py-1 rounded-lg font-medium bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs cursor-pointer" data-filter="all">Todos ({{ $todaySchedule->count() }})</button>
                    <button type="button" class="schedule-filter-btn px-3 py-1 rounded-lg font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white cursor-pointer" data-filter="upcoming">En antena / Siguientes</button>
                    <button type="button" class="schedule-filter-btn px-3 py-1 rounded-lg font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white cursor-pointer" data-filter="past">Finalizados</button>
                </div>
            </div>

            {{-- Grid scrollable de programas de hoy --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 max-h-[360px] overflow-y-auto pr-1">
                @foreach($todaySchedule as $prog)
                <div class="schedule-card p-3 rounded-xl border transition-all duration-200 {{ $prog->is_current ? 'bg-emerald-50 dark:bg-emerald-950/30 border-emerald-400 ring-2 ring-emerald-500/20 shadow-sm' : ($prog->is_past ? 'bg-slate-50/60 dark:bg-slate-800/30 border-slate-200 dark:border-slate-800 opacity-60' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:border-purple-300') }}"
                     data-status="{{ $prog->is_current ? 'current' : ($prog->is_past ? 'past' : 'upcoming') }}">
                    <div class="flex items-start justify-between gap-2">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold {{ $prog->is_current ? 'bg-emerald-600 text-white animate-pulse' : ($prog->is_past ? 'bg-slate-200 dark:bg-slate-700 text-slate-600 dark:text-slate-300' : 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300') }}">
                            <i class="far fa-clock"></i> {{ $prog->start }} - {{ $prog->end }}
                        </span>
                        @if($prog->is_current)
                            <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400">● Al Aire</span>
                        @else
                            <span class="text-[10px] text-slate-400">{{ $prog->duration }}</span>
                        @endif
                    </div>
                    <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white truncate mt-2">{{ $prog->name }}</h4>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5">
                        <i class="fas fa-microphone-lines text-[9px] mr-1"></i> {{ $prog->host ?: 'Música Continua' }}
                    </p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- ========================================================================= --}}
    {{-- 5. CENTRO DE CONTENIDO RECIENTE (TABS Y GESTIÓN)                          --}}
    {{-- ========================================================================= --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- COLUMNA 1: ÚLTIMOS CULTOS DOMINICALES (CON IA Y MULTIMEDIA) --}}
        <div class="card group hover:shadow-md transition-all duration-300">
            <div class="card-body p-4 sm:p-5">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="p-2 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 rounded-xl">
                            <i class="fas fa-church"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 dark:text-white text-base">Cultos Dominicales</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400">Mensajes bíblicos y estado de síntesis IA</p>
                        </div>
                    </div>
                    <a href="{{ url('show-worship') }}" class="chip-brand text-xs font-semibold">
                        Ver todos <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($latestWorships as $w)
                    <div class="p-3.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-white dark:hover:bg-slate-800 transition-all shadow-2xs">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0 flex-1">
                                <h4 class="font-bold text-sm text-slate-900 dark:text-white truncate">
                                    {{ $w->title }}
                                </h4>
                                <div class="flex items-center gap-2 mt-1 text-xs text-slate-500 dark:text-slate-400 flex-wrap">
                                    <span><i class="far fa-calendar mr-1"></i> {{ $w->broadcast ? $w->broadcast->format('d/m/Y') : 'Sin fecha' }}</span>
                                    <span>•</span>
                                    <span><i class="fas fa-user-pen mr-1"></i> {{ $w->autor ?: 'ECCA' }}</span>
                                </div>
                            </div>

                            {{-- Badge IA --}}
                            <div class="shrink-0">
                                @if($w->ai_processed)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300">
                                        <i class="fas fa-brain text-[10px]"></i> IA Lista
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-300">
                                        <i class="fas fa-hourglass-half text-[10px]"></i> Sin procesar
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Recursos multimedia --}}
                        <div class="flex items-center justify-between border-t border-slate-200/60 dark:border-slate-700/60 pt-2.5 mt-2.5">
                            <div class="flex items-center gap-1.5 text-xs text-slate-500 dark:text-slate-400">
                                <span class="text-[11px] font-medium mr-1">Recursos:</span>
                                @if($w->audio)
                                    <span class="w-6 h-6 rounded-md bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 flex items-center justify-center" title="Audio"><i class="fas fa-volume-up text-[10px]"></i></span>
                                @endif
                                @if($w->video || $w->urlyt)
                                    <span class="w-6 h-6 rounded-md bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 flex items-center justify-center" title="Video"><i class="fab fa-youtube text-[10px]"></i></span>
                                @endif
                                @if($w->pdfdoc)
                                    <span class="w-6 h-6 rounded-md bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 flex items-center justify-center" title="PDF"><i class="fas fa-file-pdf text-[10px]"></i></span>
                                @endif
                                @if(!$w->audio && !$w->video && !$w->urlyt && !$w->pdfdoc)
                                    <span class="text-[11px] text-slate-400">—</span>
                                @endif
                            </div>

                            <div class="flex items-center gap-1">
                                <a href="{{ url('view-worship/'.$w->id) }}" class="btn-action btn-action-info text-xs" title="Ver culto">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                @if(!$w->ai_processed && $w->audio)
                                <a href="{{ url('reprocess-worship-ai/'.$w->id) }}" class="btn-action btn-action-secondary text-xs" title="Procesar audio con IA">
                                    <i class="fas fa-wand-magic-sparkles text-xs text-amber-500"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="py-10 text-center text-slate-500">
                        <i class="fas fa-church text-3xl opacity-40 mb-2"></i>
                        <p class="text-sm">Sin cultos registrados aún</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- COLUMNA 2: PALABRA DE VIDA, NOTICIAS Y PODCASTS --}}
        <div class="space-y-6">

            {{-- Últimos Versículos (Palabra de Vida) --}}
            <div class="card group hover:shadow-md transition-all duration-300">
                <div class="card-body p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-xl">
                                <i class="fas fa-book-bible"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-base">Últimos Versículos</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Devocionales diarios publicados</p>
                            </div>
                        </div>
                        <a href="{{ url('show-quote') }}" class="chip-brand text-xs font-semibold">
                            Ver todos <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                        </a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse ($latestVerses as $v)
                        <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-white dark:hover:bg-slate-800 transition-all">
                            <div class="flex items-center gap-3">
                                @if($v->image)
                                    <img src="{{ asset('images/bible/'.$v->image) }}" alt="" class="w-10 h-10 rounded-lg object-cover shadow-2xs">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-green-100 dark:bg-green-900/40 text-green-600 flex items-center justify-center">
                                        <i class="fas fa-image text-xs"></i>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white">
                                        {{ \Carbon\Carbon::parse($v->date)->format('d \d\e F, Y') }}
                                    </div>
                                    <span class="text-[11px] text-green-600 dark:text-green-400 font-medium">
                                        <i class="fas fa-check-circle text-[9px] mr-1"></i> Publicado
                                    </span>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                @if($v->video)
                                    <a href="{{ asset('documents/quote/'.$v->video) }}" target="_blank" class="px-2.5 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-xs font-semibold hover:bg-blue-200 transition-colors flex items-center gap-1">
                                        <i class="fas fa-file-pdf"></i> PDF
                                    </a>
                                @endif
                                <a href="{{ url('view-quote/'.$v->id) }}" class="btn-action btn-action-info text-xs">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="py-6 text-center text-slate-500 text-xs">Sin versículos recientes</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Últimas Noticias & Mensajes Semanales --}}
            <div class="card group hover:shadow-md transition-all duration-300">
                <div class="card-body p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 rounded-xl">
                                <i class="fas fa-newspaper"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-base">Noticias y Mensajes</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Boletines y editoriales de la semana</p>
                            </div>
                        </div>
                        <a href="{{ url('show-news') }}" class="chip-brand text-xs font-semibold">
                            Ver todas <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                        </a>
                    </div>

                    <div class="space-y-2.5">
                        @forelse ($latestNews as $n)
                        <div class="flex items-center justify-between p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-white dark:hover:bg-slate-800 transition-all">
                            <div class="min-w-0 flex-1 pr-3">
                                <h4 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white truncate">{{ $n->title }}</h4>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                    <i class="far fa-clock mr-1"></i> {{ $n->created_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 shrink-0">
                                <a href="{{ url('view-news/'.$n->id) }}" class="btn-action btn-action-info text-xs" title="Ver">
                                    <i class="fas fa-eye text-xs"></i>
                                </a>
                                <a href="{{ url('update-news/'.$n->id) }}" class="btn-action btn-action-secondary text-xs" title="Editar">
                                    <i class="fas fa-pen text-xs"></i>
                                </a>
                            </div>
                        </div>
                        @empty
                        <div class="py-6 text-center text-slate-500 text-xs">Sin noticias publicadas</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Podcasts Recientes --}}
            @if(isset($latestPodcasts) && $latestPodcasts->count() > 0)
            <div class="card group hover:shadow-md transition-all duration-300">
                <div class="card-body p-4 sm:p-5">
                    <div class="flex items-center justify-between mb-3.5">
                        <div class="flex items-center gap-2.5">
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded-xl">
                                <i class="fas fa-podcast"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-900 dark:text-white text-base">Podcasts Recientes</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Episodios y reflexiones de audio</p>
                            </div>
                        </div>
                        <a href="{{ url('show-categories') }}" class="chip-brand text-xs font-semibold">
                            Ver todos <i class="fas fa-arrow-right text-[10px] ml-1"></i>
                        </a>
                    </div>

                    <div class="space-y-2.5">
                        @foreach ($latestPodcasts as $pod)
                        <div class="p-2.5 rounded-xl border border-slate-200/80 dark:border-slate-700/80 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-white dark:hover:bg-slate-800 transition-all">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0 flex-1">
                                    <h5 class="font-bold text-xs sm:text-sm text-slate-900 dark:text-white truncate">{{ $pod->title }}</h5>
                                    <div class="flex items-center gap-2 text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        @if($pod->category)
                                            <span class="px-2 py-0.2 rounded-full bg-blue-100 dark:bg-blue-950 text-blue-700 dark:text-blue-300 font-medium">{{ $pod->category->name }}</span>
                                        @endif
                                        <span><i class="far fa-clock mr-1"></i> {{ $pod->created_at ? $pod->created_at->format('d/m/Y') : '' }}</span>
                                    </div>
                                </div>
                                @if($pod->audio_file)
                                <a href="{{ asset('audio/'.$pod->audio_file) }}" target="_blank" class="px-2.5 py-1 bg-slate-200 hover:bg-blue-100 dark:bg-slate-700 dark:hover:bg-blue-950 text-slate-700 dark:text-slate-200 rounded-lg text-xs font-semibold transition-colors flex items-center gap-1">
                                    <i class="fas fa-play text-[10px]"></i> Audio
                                </a>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // =========================================================================
    // 1. REPRODUCTOR DE STREAMING ON-AIR INTERACTIVO
    // =========================================================================
    const audio = document.getElementById('dashboardAudioStream');
    const playBtn = document.getElementById('dashboardLivePlayBtn');
    const playIcon = document.getElementById('dashboardPlayIcon');
    const statusText = document.getElementById('dashboardLiveStatusText');
    const eqBars = document.querySelectorAll('.eq-bar');

    if (audio && playBtn) {
        let isPlaying = false;

        playBtn.addEventListener('click', function() {
            if (!isPlaying) {
                playBtn.disabled = true;
                if (statusText) statusText.textContent = 'Conectando señal...';

                // Recargar el stream para evitar lag de buffer congelado
                const streamUrl = audio.src.split('?')[0] + '?t=' + new Date().getTime();
                audio.src = streamUrl;

                audio.play().then(() => {
                    isPlaying = true;
                    playBtn.disabled = false;
                    playIcon.className = 'fas fa-pause text-lg text-emerald-700';
                    if (statusText) statusText.textContent = 'En directo (Reproduciendo)';
                    startEqualizer();
                }).catch(err => {
                    console.error('Error al reproducir streaming:', err);
                    playBtn.disabled = false;
                    if (statusText) statusText.textContent = 'Error de conexión';
                    alert('No se pudo conectar con el servidor de streaming. Verifique que la emisora esté emitiendo.');
                });
            } else {
                audio.pause();
                audio.src = '';
                isPlaying = false;
                playIcon.className = 'fas fa-play text-lg ml-0.5';
                if (statusText) statusText.textContent = 'Streaming en vivo';
                stopEqualizer();
            }
        });

        function startEqualizer() {
            eqBars.forEach((bar, i) => {
                bar.classList.add('animate-pulse');
                bar.style.animationDuration = (0.4 + (i * 0.2)) + 's';
            });
        }

        function stopEqualizer() {
            eqBars.forEach(bar => {
                bar.classList.remove('animate-pulse');
                bar.style.animationDuration = '';
            });
        }
    }

    // =========================================================================
    // 2. FILTRADO INTERACTIVO DE LA PARRILLA RADIAL DE HOY
    // =========================================================================
    const filterButtons = document.querySelectorAll('.schedule-filter-btn');
    const scheduleCards = document.querySelectorAll('.schedule-card');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Estilos activo/inactivo
            filterButtons.forEach(b => {
                b.classList.remove('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-xs');
                b.classList.add('text-slate-600', 'dark:text-slate-400');
            });
            this.classList.add('bg-white', 'dark:bg-slate-700', 'text-slate-900', 'dark:text-white', 'shadow-xs');
            this.classList.remove('text-slate-600', 'dark:text-slate-400');

            const filter = this.getAttribute('data-filter');

            scheduleCards.forEach(card => {
                const status = card.getAttribute('data-status');
                if (filter === 'all') {
                    card.style.display = 'block';
                } else if (filter === 'upcoming') {
                    card.style.display = (status === 'current' || status === 'upcoming') ? 'block' : 'none';
                } else if (filter === 'past') {
                    card.style.display = (status === 'past') ? 'block' : 'none';
                }
            });
        });
    });

    // =========================================================================
    // 3. ANIMACIÓN SUAVE DE NÚMEROS KPI
    // =========================================================================
    document.querySelectorAll('.stat-card .text-2xl, .stat-card .text-3xl').forEach(stat => {
        const text = stat.textContent.trim();
        const numValue = parseInt(text.replace(/[^0-9]/g, '')) || 0;
        if (numValue > 0) {
            let current = 0;
            const step = Math.max(1, Math.floor(numValue / 20));
            const timer = setInterval(() => {
                current += step;
                if (current >= numValue) {
                    stat.textContent = numValue.toLocaleString();
                    clearInterval(timer);
                } else {
                    stat.textContent = current.toLocaleString();
                }
            }, 30);
        }
    });
});
</script>
@endpush
