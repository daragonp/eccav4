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

        {{-- Tarjeta 3: Streaming de Radio WideStream --}}
        <div class="card">
            <div class="card-header p-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                <h4 class="font-bold text-sm text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-tower-broadcast text-emerald-500"></i>
                    <span>Servidor de Streaming</span>
                </h4>
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                </span>
            </div>
            <div class="card-body p-4 space-y-3 text-sm">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Transmisión de audio 24/7 en alta fidelidad mediante WideStream:
                </p>
                <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/70 border border-slate-200 dark:border-slate-700 space-y-2">
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Proveedor:</span>
                        <span class="font-bold text-slate-800 dark:text-slate-200">{{ $systemInfo['provider'] ?? 'WideStream' }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Audio Directo (AAC):</span>
                        <span class="font-mono text-[11px] text-emerald-600 dark:text-emerald-400 font-semibold truncate max-w-[170px]" title="{{ $systemInfo['stream_url'] ?? '' }}">
                            radio.aac
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Protocolo HLS:</span>
                        <span class="font-mono text-[11px] text-teal-600 dark:text-teal-400 font-semibold truncate max-w-[170px]" title="{{ $systemInfo['stream_hls'] ?? '' }}">
                            live.m3u8
                        </span>
                    </div>
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-500">Estado de emisión:</span>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-400">
                            <i class="fa-solid fa-circle text-[6px] animate-pulse"></i> Online 24/7
                        </span>
                    </div>
                </div>
                <div class="pt-1 space-y-2">
                    <a href="{{ $systemInfo['station_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary w-full text-xs py-2 inline-flex items-center justify-center gap-2 shadow-xs">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i>
                        <span>Abrir Consola WideStream</span>
                    </a>
                </div>
            </div>
        </div>

    </div>

    {{-- Visor y Explorador de Widgets WideStream --}}
    <div class="card" x-data="{
        layout: 'main',
        theme: 'dark',
        heights: { main: 80, card: 190, full: 380, button: 48 },
        get iframeSrc() {
            let url = 'https://widestream.app/embed/main';
            let params = [];
            if (this.layout !== 'main') params.push('layout=' + this.layout);
            if (this.theme !== 'dark') params.push('theme=' + this.theme);
            return params.length ? url + '?' + params.join('&') : url;
        },
        get iframeCode() {
            let h = this.heights[this.layout] || 80;
            return `<iframe src=\x22${this.iframeSrc}\x22 width=\x22100%\x22 height=\x22${h}\x22 frameborder=\x220\x22 allow=\x22autoplay\x22 style=\x22border-radius: 16px; overflow: hidden; border: none;\x22></iframe>`;
        },
        copied: false,
        copyCode() {
            navigator.clipboard.writeText(this.iframeCode);
            this.copied = true;
            setTimeout(() => this.copied = false, 2500);
        }
    }">
        <div class="card-header p-5 border-b border-slate-200 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h4 class="font-bold text-base text-slate-900 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-play text-emerald-500"></i>
                    <span>Explorador de Widgets WideStream Radio</span>
                </h4>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                    Previsualiza y obtén el código HTML de integración para cualquier variante del reproductor.
                </p>
            </div>

            <div class="flex items-center gap-2 flex-wrap">
                {{-- Selector de Layout --}}
                <div class="inline-flex rounded-lg p-1 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs">
                    <button type="button" @click="layout = 'main'" :class="layout === 'main' ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-md transition-all">Principal (80px)</button>
                    <button type="button" @click="layout = 'card'" :class="layout === 'card' ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-md transition-all">Tarjeta (190px)</button>
                    <button type="button" @click="layout = 'button'" :class="layout === 'button' ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-md transition-all">Botón (48px)</button>
                    <button type="button" @click="layout = 'full'" :class="layout === 'full' ? 'bg-white dark:bg-slate-700 text-emerald-600 dark:text-emerald-400 shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400'" class="px-2.5 py-1 rounded-md transition-all">Full (380px)</button>
                </div>

                {{-- Selector de Tema --}}
                <div class="inline-flex rounded-lg p-1 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-xs">
                    <button type="button" @click="theme = 'dark'" :class="theme === 'dark' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400'" class="px-2 py-1 rounded-md transition-all">Oscuro</button>
                    <button type="button" @click="theme = 'light'" :class="theme === 'light' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400'" class="px-2 py-1 rounded-md transition-all">Claro</button>
                    <button type="button" @click="theme = 'transparent'" :class="theme === 'transparent' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-600 dark:text-slate-400'" class="px-2 py-1 rounded-md transition-all">Transparente</button>
                </div>
            </div>
        </div>

        <div class="card-body p-5 space-y-4">
            {{-- Contenedor de Previsualización en Vivo --}}
            <div class="p-4 rounded-2xl bg-slate-900/90 border border-slate-700/80 shadow-inner flex items-center justify-center min-h-[100px]">
                <div class="w-full max-w-2xl">
                    <iframe
                        :src="iframeSrc"
                        width="100%"
                        :height="heights[layout]"
                        frameborder="0"
                        allow="autoplay"
                        style="border-radius: 16px; overflow: hidden; border: none; display: block;"
                    ></iframe>
                </div>
            </div>

            {{-- Bloque de Código HTML con Botón Copiar --}}
            <div class="relative">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-bold text-slate-600 dark:text-slate-400">Código HTML generado:</span>
                    <button type="button" @click="copyCode()" class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline flex items-center gap-1 font-semibold">
                        <i :class="copied ? 'fa-solid fa-check text-green-500' : 'fa-regular fa-copy'"></i>
                        <span x-text="copied ? '¡Copiado al portapapeles!' : 'Copiar código iframe'"></span>
                    </button>
                </div>
                <div class="p-3 rounded-xl bg-slate-950 font-mono text-xs text-emerald-300 break-all select-all border border-slate-800" x-text="iframeCode"></div>
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
