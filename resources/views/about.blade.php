@extends('layouts.main')

@section('title', 'Acerca del Sistema & Versión')

@section('page-hero')
  <div class="max-w-5xl mx-auto py-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
      <div>
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/70 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800 mb-2">
          <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
          Sistema en Línea &bull; SemVer 2.0.0
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white tracking-tight">
          Acerca de <span class="text-emerald-600 dark:text-emerald-400">ECCA v4</span>
        </h1>
        <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300 mt-1">
          Plataforma web de radiodifusión, estudio bíblico y contenidos de Emancipación Cristiana Afro.
        </p>
      </div>

      <div class="flex items-center gap-2">
        @auth
          <a href="{{ url('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 dark:bg-emerald-600 dark:hover:bg-emerald-500 shadow-md transition">
            <i class="fa-solid fa-gauge-high"></i>
            <span>Ir al Panel</span>
          </a>
        @else
          <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold bg-emerald-700 text-white hover:bg-emerald-800 shadow-md transition">
            <i class="fa-solid fa-house"></i>
            <span>Inicio</span>
          </a>
        @endauth
        <a href="{{ url('/about?format=json') }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-medium border border-slate-300 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-300 transition" title="Ver metadatos crudos en JSON">
          <i class="fa-solid fa-code"></i>
          <span>JSON API</span>
        </a>
      </div>
    </div>
  </div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

  {{-- Banner principal de versión --}}
  <div class="relative overflow-hidden rounded-3xl bg-linear-to-br from-emerald-900 via-slate-900 to-slate-950 p-6 sm:p-8 text-white shadow-2xl border border-emerald-800/40">
    <div class="absolute -right-12 -top-12 w-64 h-64 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -left-12 -bottom-12 w-64 h-64 rounded-full bg-yellow-500/10 blur-3xl pointer-events-none"></div>

    <div class="relative z-10 grid grid-cols-1 lg:grid-cols-3 gap-6 items-center">
      <div class="lg:col-span-2 space-y-3">
        <div class="flex flex-wrap items-center gap-3">
          <span class="text-3xl sm:text-5xl font-black tracking-tight text-white">
            {{ $version }}
          </span>
          <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
            Producción Estable
          </span>
          @if(!empty($meta['prerelease']))
            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/30">
              {{ $meta['prerelease'] }}
            </span>
          @endif
        </div>
        <p class="text-slate-300 text-sm sm:text-base leading-relaxed">
          Compilación oficial sincronizada con el repositorio central de GitHub. Gestionada mediante el estándar de versión semántica (Semantic Versioning 2.0.0).
        </p>

        <div class="pt-2 flex flex-wrap gap-4 text-xs text-slate-300">
          <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-xs">
            <i class="fa-solid fa-code-branch text-yellow-400"></i>
            <span>Rama: <strong>{{ $branch }}</strong></span>
          </div>
          <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-xs">
            <i class="fa-solid fa-calendar-check text-emerald-400"></i>
            <span>Lanzamiento: <strong>{{ $commitDate }}</strong></span>
          </div>
          @if($gitHubRun)
          <div class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg backdrop-blur-xs">
            <i class="fa-solid fa-rocket text-sky-400"></i>
            <span>Build Run: <strong>#{{ $gitHubRun }}</strong></span>
          </div>
          @endif
        </div>
      </div>

      {{-- Tarjeta de Commit GitHub --}}
      <div class="bg-white/5 border border-white/10 rounded-2xl p-5 backdrop-blur-md flex flex-col justify-between">
        <div>
          <div class="flex items-center justify-between text-xs text-slate-400 mb-2">
            <span class="uppercase tracking-wider font-semibold">GitHub Commit</span>
            <i class="fa-brands fa-github text-lg text-white"></i>
          </div>
          <div class="font-mono text-sm font-bold text-yellow-300 break-all">
            {{ $shortCommit }}
          </div>
          <p class="text-[11px] font-mono text-slate-400 mt-1 truncate" title="{{ $fullCommit }}">
            {{ $fullCommit }}
          </p>
        </div>

        <div class="mt-4 pt-3 border-t border-white/10">
          @if($commitUrl)
            <a href="{{ $commitUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-2 w-full px-3 py-2 rounded-xl text-xs font-semibold bg-emerald-500 hover:bg-emerald-400 text-slate-950 transition shadow">
              <i class="fa-solid fa-arrow-up-right-from-square"></i>
              <span>Ver commit en GitHub</span>
            </a>
          @else
            <span class="text-xs text-slate-400 italic">Compilación local</span>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Fila de Arquitectura y Stack Tecnológico --}}
  <div>
    <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 mb-4">
      <i class="fa-solid fa-layer-group text-emerald-600"></i>
      <span>Stack Tecnológico & Entorno</span>
    </h2>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
      {{-- PHP --}}
      <div class="bg-white dark:bg-slate-800/80 rounded-2xl p-4 border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col items-center text-center">
        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-lg mb-2">
          <i class="fa-brands fa-php"></i>
        </div>
        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">PHP Engine</div>
        <div class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">{{ $systemInfo['php_version'] }}</div>
      </div>

      {{-- Laravel --}}
      <div class="bg-white dark:bg-slate-800/80 rounded-2xl p-4 border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col items-center text-center">
        <div class="w-10 h-10 rounded-xl bg-red-50 dark:bg-red-950/60 text-red-600 dark:text-red-400 flex items-center justify-center text-lg mb-2">
          <i class="fa-brands fa-laravel"></i>
        </div>
        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Laravel Framework</div>
        <div class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">v{{ $systemInfo['laravel_version'] }}</div>
      </div>

      {{-- Base de Datos --}}
      <div class="bg-white dark:bg-slate-800/80 rounded-2xl p-4 border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col items-center text-center">
        <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center text-lg mb-2">
          <i class="fa-solid fa-database"></i>
        </div>
        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Base de Datos</div>
        <div class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">MySQL 8.0</div>
      </div>

      {{-- Redis --}}
      <div class="bg-white dark:bg-slate-800/80 rounded-2xl p-4 border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col items-center text-center">
        <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400 flex items-center justify-center text-lg mb-2">
          <i class="fa-solid fa-bolt"></i>
        </div>
        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Caché & Sesión</div>
        <div class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">Redis 7 / {{ strtoupper($systemInfo['cache']) }}</div>
      </div>

      {{-- Docker --}}
      <div class="bg-white dark:bg-slate-800/80 rounded-2xl p-4 border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col items-center text-center">
        <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400 flex items-center justify-center text-lg mb-2">
          <i class="fa-brands fa-docker"></i>
        </div>
        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Contenedores</div>
        <div class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">Docker Compose</div>
      </div>

      {{-- Frontend --}}
      <div class="bg-white dark:bg-slate-800/80 rounded-2xl p-4 border border-slate-200 dark:border-slate-700/80 shadow-xs flex flex-col items-center text-center">
        <div class="w-10 h-10 rounded-xl bg-teal-50 dark:bg-teal-950/60 text-teal-600 dark:text-teal-400 flex items-center justify-center text-lg mb-2">
          <i class="fa-brands fa-css3-alt"></i>
        </div>
        <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">Diseño UI</div>
        <div class="text-sm font-bold text-slate-900 dark:text-white mt-0.5">Tailwind v4</div>
      </div>
    </div>
  </div>

  {{-- Explicación del sistema de SemVer --}}
  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-xs">
      <div class="flex items-center gap-3 mb-2">
        <span class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-400 font-mono font-black flex items-center justify-center text-sm">
          M
        </span>
        <h3 class="font-bold text-slate-900 dark:text-white text-base">MAJOR ({{ $meta['major'] ?? 4 }})</h3>
      </div>
      <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
        Incrementa cuando se introducen modificaciones incompatibles en la API, esquemas fundamentales de base de datos o rediseños arquitecturales globales.
      </p>
      <div class="mt-3 font-mono text-[11px] text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-900 p-2 rounded-lg">
        php artisan app:version major
      </div>
    </div>

    <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-xs">
      <div class="flex items-center gap-3 mb-2">
        <span class="w-7 h-7 rounded-lg bg-blue-100 dark:bg-blue-950/70 text-blue-700 dark:text-blue-400 font-mono font-black flex items-center justify-center text-sm">
          m
        </span>
        <h3 class="font-bold text-slate-900 dark:text-white text-base">MINOR ({{ $meta['minor'] ?? 8 }})</h3>
      </div>
      <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
        Incrementa al añadir nuevas funcionalidades retrocompatibles (como nuevos módulos de culto, IA, herramientas de predicación o paneles).
      </p>
      <div class="mt-3 font-mono text-[11px] text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-900 p-2 rounded-lg">
        php artisan app:version minor
      </div>
    </div>

    <div class="bg-white dark:bg-slate-800/90 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-xs">
      <div class="flex items-center gap-3 mb-2">
        <span class="w-7 h-7 rounded-lg bg-amber-100 dark:bg-amber-950/70 text-amber-700 dark:text-amber-400 font-mono font-black flex items-center justify-center text-sm">
          p
        </span>
        <h3 class="font-bold text-slate-900 dark:text-white text-base">PATCH ({{ $meta['patch'] ?? 2 }})</h3>
      </div>
      <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
        Incrementa al aplicar parches de corrección de fallos (bugfixes), optimizaciones de rendimiento y ajustes de seguridad retrocompatibles.
      </p>
      <div class="mt-3 font-mono text-[11px] text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-900 p-2 rounded-lg">
        php artisan app:version patch
      </div>
    </div>
  </div>

  {{-- Historial de Versiones (Changelog) --}}
  <div class="bg-white dark:bg-slate-800/90 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 shadow-xs space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-200 dark:border-slate-700/80 pb-4">
      <div>
        <h2 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2">
          <i class="fa-solid fa-timeline text-emerald-600"></i>
          <span>Historial de Versiones & Registro de Cambios</span>
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-0.5">
          Evolución continua del sistema, mejoras aplicadas y notas de entrega oficiales.
        </p>
      </div>
      <span class="text-xs font-semibold px-3 py-1 rounded-full bg-slate-100 dark:bg-slate-900 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 w-fit">
        {{ count($changelog) }} versiones registradas
      </span>
    </div>

    <div class="space-y-6 relative before:absolute before:inset-0 before:left-3 before:w-0.5 before:bg-slate-200 dark:before:bg-slate-700 pl-8">
      @foreach($changelog as $item)
        <div class="relative group">
          {{-- Nodo del timeline --}}
          <div class="absolute -left-8 top-1.5 w-6 h-6 rounded-full border-2 border-emerald-500 bg-white dark:bg-slate-900 flex items-center justify-center">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
          </div>

          <div class="bg-slate-50 dark:bg-slate-900/60 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-800 space-y-2 hover:border-emerald-500/40 transition">
            <div class="flex flex-wrap items-center justify-between gap-2">
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold font-mono bg-emerald-600 text-white shadow-xs">
                  {{ $item['version'] }}
                </span>
                <h3 class="font-bold text-slate-900 dark:text-white text-base">
                  {{ $item['title'] }}
                </h3>
              </div>
              <time class="text-xs text-slate-500 dark:text-slate-400 font-medium">
                {{ \Carbon\Carbon::parse($item['date'])->locale('es')->isoFormat('D [de] MMMM [de] YYYY') }}
              </time>
            </div>

            <ul class="mt-2 space-y-1.5 text-xs sm:text-sm text-slate-600 dark:text-slate-300">
              @foreach($item['highlights'] as $highlight)
                <li class="flex items-start gap-2">
                  <i class="fa-solid fa-check text-emerald-500 text-xs mt-1"></i>
                  <span>{{ $highlight }}</span>
                </li>
              @endforeach
            </ul>
          </div>
        </div>
      @endforeach
    </div>
  </div>

</div>
@endsection
