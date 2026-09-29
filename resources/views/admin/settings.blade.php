@extends('layouts.panel')

@section('title', 'Configuración del Sistema')
@section('pageheading', 'Configuración & Diagnóstico')

@section('datatable')
<div class="space-y-6">

    {{-- Banner Informativo --}}
    <div class="card">
        <div class="card-body p-5">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0 text-xl shadow-xs">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Configuración Global y Entorno</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">
                            Estado del runtime, dependencias del servidor, dominios de emisión y parámetros operativos de la plataforma.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                        <i class="fa-solid fa-circle text-[8px] animate-pulse"></i> Sistema Operativo
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Rejilla de Diagnóstico y Estado --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- Tarjeta 1: Entorno de Aplicación --}}
        <div class="card">
            <div class="card-header p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h4 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-brands fa-laravel text-rose-500"></i>
                    <span>Framework & Runtime</span>
                </h4>
                <span class="text-xs px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 font-mono text-slate-600 dark:text-slate-400">
                    PHP {{ $systemInfo['php_version'] }}
                </span>
            </div>
            <div class="card-body p-4 space-y-3 text-sm">
                <div class="flex justify-between items-center py-1 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 text-xs">Versión de Laravel</span>
                    <span class="font-mono text-xs font-semibold text-slate-800 dark:text-slate-200">v{{ $systemInfo['laravel_version'] }}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 text-xs">Versión de Release (SemVer)</span>
                    <span class="font-mono text-xs font-bold text-emerald-600 dark:text-emerald-400">{{ $systemInfo['app_version'] }}</span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 text-xs">Entorno (APP_ENV)</span>
                    <span class="capitalize text-xs font-semibold px-2 py-0.5 rounded {{ $systemInfo['app_env'] === 'production' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300' }}">
                        {{ $systemInfo['app_env'] }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-1 border-b border-slate-100 dark:border-slate-800/60">
                    <span class="text-slate-500 dark:text-slate-400 text-xs">Modo Depuración (DEBUG)</span>
                    <span class="text-xs font-bold {{ $systemInfo['app_debug'] ? 'text-amber-600 dark:text-amber-400' : 'text-slate-600 dark:text-slate-400' }}">
                        {{ $systemInfo['app_debug'] ? 'Habilitado (On)' : 'Desactivado (Off)' }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-1">
                    <span class="text-slate-500 dark:text-slate-400 text-xs">Motor de Base de Datos</span>
                    <span class="uppercase font-mono text-xs font-semibold text-slate-800 dark:text-slate-200">{{ $systemInfo['db_connection'] }}</span>
                </div>
            </div>
        </div>

        {{-- Tarjeta 2: Dominios y Despliegue en Servidor --}}
        <div class="card">
            <div class="card-header p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h4 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-server text-blue-500"></i>
                    <span>Dominios & Despliegue</span>
                </h4>
                <span class="text-[11px] font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                    <i class="fa-solid fa-circle-check"></i> Sincronizado
                </span>
            </div>
            <div class="card-body p-4 space-y-3 text-sm">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Despliegue automatizado activo mediante webhook en servidor de producción VPS:
                </p>
                <div class="space-y-2">
                    @foreach($systemInfo['domains'] as $domain)
                        <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700">
                            <span class="font-mono text-xs text-slate-800 dark:text-slate-200 truncate">{{ $domain }}</span>
                            <a href="https://{{ $domain }}" target="_blank" rel="noopener noreferrer" class="text-slate-400 hover:text-emerald-500 transition-colors">
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
                <div class="pt-2">
                    <a href="{{ url('/about') }}" class="btn btn-secondary w-full text-xs py-2 inline-flex items-center justify-center gap-2">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Ver Ficha Técnica /about</span>
                    </a>
                </div>
            </div>
        </div>

        {{-- Tarjeta 3: Streaming de Radio Asura --}}
        <div class="card">
            <div class="card-header p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h4 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-tower-broadcast text-emerald-500"></i>
                    <span>Consola de Streaming</span>
                </h4>
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
            </div>
            <div class="card-body p-4 space-y-3 text-sm">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Transmisión de audio 24 horas continuas a través del servidor Icecast/AzuraCast:
                </p>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 space-y-1.5">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Estación ID:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">#199</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Proveedor:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">Asura Hosting</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Parrilla en tiempo real:</span>
                        <span class="text-emerald-600 dark:text-emerald-400 font-semibold">Sincronizada</span>
                    </div>
                </div>
                <div class="pt-2">
                    <a href="{{ $systemInfo['station_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary w-full text-xs py-2 inline-flex items-center justify-center gap-2 shadow-xs">
                        <i class="fa-solid fa-sliders"></i>
                        <span>Abrir Consola Asura</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Accesos Directos de Administración --}}
    <div class="card">
        <div class="card-header p-5 border-b border-slate-200 dark:border-slate-800">
            <h4 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                <i class="fa-solid fa-compass text-slate-500"></i>
                <span>Accesos Directos y Rutas Clave</span>
            </h4>
        </div>
        <div class="card-body p-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ url('show-schedule') }}" class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 hover:shadow-xs transition-all group">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-calendar-week"></i>
                    </div>
                    <div class="font-bold text-sm text-slate-900 dark:text-white">Parrilla Semanal</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Horarios y directores de radio</div>
                </a>

                <a href="{{ url('/holiday-schedule') }}" class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 hover:shadow-xs transition-all group">
                    <div class="w-9 h-9 rounded-lg bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-calendar-day"></i>
                    </div>
                    <div class="font-bold text-sm text-slate-900 dark:text-white">Parrilla Festivos</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Programación especial y overrides</div>
                </a>

                <a href="{{ url('show-categories') }}" class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 hover:shadow-xs transition-all group">
                    <div class="w-9 h-9 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-podcast"></i>
                    </div>
                    <div class="font-bold text-sm text-slate-900 dark:text-white">Podcasts & Series</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Audio bajo demanda y categorías</div>
                </a>

                <a href="{{ url('access-control') }}" class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-emerald-500 dark:hover:border-emerald-500 hover:shadow-xs transition-all group">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 dark:bg-blue-950 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-user-shield"></i>
                    </div>
                    <div class="font-bold text-sm text-slate-900 dark:text-white">Control de Acceso</div>
                    <div class="text-xs text-slate-500 dark:text-slate-400 mt-1">Roles y permisos Spatie</div>
                </a>
            </div>
        </div>
    </div>

</div>
@endsection
