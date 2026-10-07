<!DOCTYPE html>
<html lang="es" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
    <meta name="color-scheme" content="light dark">

    {{-- Favicons / manifest --}}
    <link rel="icon" href="{{ asset('images/fav/favicon.svg') }}" type="image/svg+xml" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}" />
    <title>@yield('title', 'Panel') — Emancipación Cristiana Afro</title>

    {{-- Pre-set dark & sidebar collapsed antes del render para evitar FOUC --}}
    <script>
      (function() {
        try {
          const theme = localStorage.getItem('theme') || 'light';
          if (theme === 'dark' || (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
          }
          const sidebarCollapsed = localStorage.getItem('sidebar_collapsed');
          if (sidebarCollapsed === '1' && window.innerWidth >= 1024) {
            document.documentElement.classList.add('sidebar-collapsed');
          }
        } catch(e) {}
      })();
    </script>

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
</head>

<body class="antialiased font-sans bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <div class="panel-shell">
        {{-- Backdrop para mobile drawer --}}
        <div id="sidebar-backdrop" class="sidebar-backdrop" aria-hidden="true"></div>

        {{-- Sidebar Principal Unificado --}}
        <aside id="sidebar" class="panel-sidebar transition-all duration-300 ease-in-out" aria-label="Navegación principal">
            @php
                $panelUser = auth()->user();
                $panelUserName = $panelUser?->name ?? 'Usuario';
                $panelUserEmail = $panelUser?->email ?? '';
                $panelAvatar = $panelUser?->avatar_url ?? asset('images/logo/logo.png');
                $panelRoleName = $panelUser?->roles->first()?->name ?? 'Administrador';
                $isSuperAdmin = (int) ($panelUser->role_id ?? 0) === 1;
                if (!$isSuperAdmin) {
                    $roleNames = collect(optional($panelUser)->roles ?? [])
                        ->pluck('name')
                        ->map(fn ($name) => mb_strtolower((string) $name));
                    $isSuperAdmin = $roleNames->contains('superadministrador') || $roleNames->contains('super-admin') || $roleNames->contains('super admin');
                }
            @endphp

            {{-- 1. Cabecera del Sidebar (Logo y Marca) --}}
            <div class="h-16 px-4 flex items-center justify-between border-b border-slate-200 dark:border-slate-800">
                <a href="{{ url('dashboard') }}" class="flex items-center gap-3 overflow-hidden group">
                    <div class="w-10 h-10 shrink-0 rounded-xl bg-linear-to-br from-emerald-600 to-green-700 flex items-center justify-center text-white font-extrabold text-sm shadow-md shadow-emerald-600/20 group-hover:scale-105 transition-transform">
                        ECA
                    </div>
                    <div class="sidebar-full flex flex-col min-w-0 transition-opacity duration-200">
                        <span class="font-extrabold text-base tracking-tight text-slate-900 dark:text-white leading-tight">ECCA v4</span>
                        <span class="text-[11px] font-medium text-emerald-600 dark:text-emerald-400 leading-tight">Radio & Contenidos</span>
                    </div>
                </a>
                <button id="closeSidebar" class="btn btn-ghost lg:hidden p-2 text-slate-500 hover:text-slate-800 dark:hover:text-white" aria-label="Cerrar menú">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            {{-- 2. Tarjeta Rápida de Usuario --}}
            <div class="p-3 border-b border-slate-200/80 dark:border-slate-800/80">
                {{-- Versión expandida --}}
                <div class="sidebar-full flex items-center gap-3 p-2 rounded-xl bg-slate-100/70 dark:bg-slate-900/60 border border-slate-200/60 dark:border-slate-800/60">
                    <div class="relative shrink-0">
                        <img src="{{ $panelAvatar }}" alt="{{ $panelUserName }}" class="w-10 h-10 rounded-lg object-cover shadow-xs">
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white dark:border-slate-900 rounded-full" title="Conectado"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $panelUserName }}</div>
                        <div class="text-[10px] font-semibold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider truncate">{{ $panelRoleName }}</div>
                    </div>
                </div>

                {{-- Versión compacta (solo icono/avatar centrado) --}}
                <div class="sidebar-compact hidden justify-center py-1">
                    <div class="relative" title="{{ $panelUserName }} ({{ $panelRoleName }})">
                        <img src="{{ $panelAvatar }}" alt="{{ $panelUserName }}" class="w-10 h-10 rounded-xl object-cover shadow-xs ring-2 ring-emerald-500/40">
                        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white dark:border-slate-900 rounded-full"></span>
                    </div>
                </div>
            </div>

            {{-- 3. Navegación Principal Organizada --}}
            <nav class="flex-1 overflow-y-auto px-2 py-3 space-y-4" aria-label="Menú lateral">

                {{-- SECCIÓN: GENERAL --}}
                <div class="nav-group">
                    <div class="nav-section-title">General</div>
                    <div class="nav-section-divider"></div>

                    <a href="{{ url('dashboard') }}" class="nav-item group {{ request()->is('dashboard') ? 'active' : '' }}" title="Panel Principal">
                        <div class="nav-icon">
                            <i class="fa-solid fa-gauge-high"></i>
                        </div>
                        <span class="nav-text">Panel Principal</span>
                    </a>

                    <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="nav-item group" title="Ver Sitio Web Público">
                        <div class="nav-icon">
                            <i class="fa-solid fa-globe"></i>
                        </div>
                        <span class="nav-text">Ver Sitio Web</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400 ml-auto sidebar-full"></i>
                    </a>
                </div>

                {{-- SECCIÓN: RADIO & EMISIÓN --}}
                <div class="nav-group">
                    <div class="nav-section-title">Radio & Emisión</div>
                    <div class="nav-section-divider"></div>

                    <a href="{{ url('show-schedule') }}" class="nav-item group {{ request()->is('show-schedule*') || request()->is('schedule*') ? 'active' : '' }}" title="Programación de Radio">
                        <div class="nav-icon">
                            <i class="fa-solid fa-calendar-week"></i>
                        </div>
                        <span class="nav-text">Programación</span>
                    </a>

                    <a href="{{ url('/holiday-schedule') }}" class="nav-item group {{ request()->is('holiday-schedule*') ? 'active' : '' }}" title="Parrilla Días Festivos">
                        <div class="nav-icon">
                            <i class="fa-solid fa-calendar-day"></i>
                        </div>
                        <span class="nav-text">Días Festivos</span>
                    </a>

                    <a href="{{ url('show-worship') }}" class="nav-item group {{ request()->is('show-worship*') ? 'active' : '' }}" title="Cultos Dominicales con IA">
                        <div class="nav-icon">
                            <i class="fa-solid fa-church"></i>
                        </div>
                        <span class="nav-text">Cultos Dominicales</span>
                    </a>

                    <a href="{{ url('show-categories') }}" class="nav-item group {{ request()->is('show-categories*') || request()->is('*-podcast*') ? 'active' : '' }}" title="Podcasts & Audios">
                        <div class="nav-icon">
                            <i class="fa-solid fa-podcast"></i>
                        </div>
                        <span class="nav-text">Podcasts</span>
                    </a>

                    <a href="{{ config('app.stream_station_url', 'https://widestream.app/') }}" target="_blank" rel="noopener noreferrer" class="nav-item group" title="Consola WideStream (Transmisión 24/7)">
                        <div class="nav-icon">
                            <i class="fa-solid fa-tower-broadcast"></i>
                        </div>
                        <span class="nav-text">Consola Streaming</span>
                        <span class="sidebar-full ml-auto flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                    </a>
                </div>

                {{-- SECCIÓN: CONTENIDO & PRENSA --}}
                <div class="nav-group">
                    <div class="nav-section-title">Contenido & Medios</div>
                    <div class="nav-section-divider"></div>

                    <a href="{{ url('show-quote') }}" class="nav-item group {{ request()->is('show-quote*') ? 'active' : '' }}" title="Palabra de Vida (Versículo Diario)">
                        <div class="nav-icon">
                            <i class="fa-solid fa-book-bible"></i>
                        </div>
                        <span class="nav-text">Palabra de Vida</span>
                    </a>

                    <a href="{{ url('show-news') }}" class="nav-item group {{ request()->is('show-news*') ? 'active' : '' }}" title="Mensaje de la Semana / Noticias">
                        <div class="nav-icon">
                            <i class="fa-solid fa-newspaper"></i>
                        </div>
                        <span class="nav-text">Mensaje de la Semana</span>
                    </a>

                    <a href="{{ url('show-looks') }}" class="nav-item group {{ request()->is('show-looks*') ? 'active' : '' }}" title="Mirada Afro (Artículos)">
                        <div class="nav-icon">
                            <i class="fa-solid fa-earth-africa"></i>
                        </div>
                        <span class="nav-text">Mirada Afro</span>
                    </a>

                    <a href="{{ url('show-slider') }}" class="nav-item group {{ request()->is('show-slider*') ? 'active' : '' }}" title="Banner Carrusel de Portada">
                        <div class="nav-icon">
                            <i class="fa-solid fa-images"></i>
                        </div>
                        <span class="nav-text">Banner Carrusel</span>
                    </a>
                </div>

                {{-- SECCIÓN: ADMINISTRACIÓN --}}
                <div class="nav-group">
                    <div class="nav-section-title">Administración</div>
                    <div class="nav-section-divider"></div>

                    <a href="{{ url('show-users') }}" class="nav-item group {{ request()->is('show-users*') || request()->is('*-user*') ? 'active' : '' }}" title="Gestión de Usuarios">
                        <div class="nav-icon">
                            <i class="fa-solid fa-users"></i>
                        </div>
                        <span class="nav-text">Usuarios</span>
                    </a>

                    <a href="{{ url('show-roles') }}" class="nav-item group {{ request()->is('show-roles*') || request()->is('*-role*') ? 'active' : '' }}" title="Roles y Permisos">
                        <div class="nav-icon">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <span class="nav-text">Roles & Permisos</span>
                    </a>

                    @if($isSuperAdmin)
                    <a href="{{ route('access.control') }}" class="nav-item group {{ request()->is('access-control*') ? 'active' : '' }}" title="Control de Accesos Avanzado">
                        <div class="nav-icon">
                            <i class="fa-solid fa-key"></i>
                        </div>
                        <span class="nav-text">Control de Accesos</span>
                    </a>
                    @endif
                </div>

                {{-- SECCIÓN: SISTEMA & SOPORTE --}}
                <div class="nav-group">
                    <div class="nav-section-title">Sistema & Soporte</div>
                    <div class="nav-section-divider"></div>

                    <a href="{{ route('about') }}" class="nav-item group {{ request()->is('about*') ? 'active' : '' }}" title="Acerca del Sistema & Versión SemVer">
                        <div class="nav-icon">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>
                        <span class="nav-text">Acerca del Sistema</span>
                        <span class="sidebar-full ml-auto px-1.5 py-0.5 rounded text-[10px] font-mono bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-bold">
                            {{ app(\App\Services\VersionService::class)->getVersion() }}
                        </span>
                    </a>

                    <a href="{{ url('suggestions') }}" class="nav-item group {{ request()->is('suggestions*') ? 'active' : '' }}" title="Buzón de Sugerencias">
                        <div class="nav-icon">
                            <i class="fa-solid fa-comment-dots"></i>
                        </div>
                        <span class="nav-text">Sugerencias</span>
                    </a>

                    <a href="{{ url('settings') }}" class="nav-item group {{ request()->is('settings*') ? 'active' : '' }}" title="Configuración del Sistema">
                        <div class="nav-icon">
                            <i class="fa-solid fa-gear"></i>
                        </div>
                        <span class="nav-text">Configuración</span>
                    </a>
                </div>
            </nav>
        </aside>

        {{-- 4. Área Principal (Main + Topbar + Contenido + Footer) --}}
        <main class="panel-main flex flex-col min-h-screen">
            {{-- Topbar Superior --}}
            <header class="panel-topbar">
                {{-- Sección Izquierda Topbar --}}
                <div class="flex items-center gap-3">
                    <button id="toggleSidebar" class="btn-topbar-toggle" aria-label="Colapsar o expandir menú" title="Alternar menú (Ctrl+B)">
                        <i class="fa-solid fa-bars-staggered text-slate-700 dark:text-slate-300"></i>
                    </button>

                    {{-- Migas de pan / Título --}}
                    <div class="flex items-center gap-2 text-xs sm:text-sm">
                        <span class="font-extrabold text-slate-900 dark:text-white flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            ECCA v4
                        </span>
                        <span class="text-slate-400">/</span>
                        <h1 class="font-semibold text-slate-700 dark:text-slate-200 truncate max-w-[150px] sm:max-w-xs m-0 text-xs sm:text-sm">
                            @yield('pageheading', 'Panel')
                        </h1>
                    </div>

                    {{-- Indicador Radio En Vivo --}}
                    <a href="{{ config('app.stream_station_url', 'https://widestream.app/') }}" target="_blank" rel="noopener noreferrer" class="hidden md:inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition shadow-2xs" title="Emisora en vivo (Clic para abrir consola de streaming WideStream)">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span>RADIO EN VIVO</span>
                    </a>
                </div>

                {{-- Sección Derecha Topbar --}}
                <div class="flex items-center gap-2 sm:gap-3">
                    {{-- Acceso rápido al sitio público --}}
                    <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer" class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-200 dark:border-slate-700/80 transition shadow-2xs" title="Ver sitio web público">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[11px] text-slate-500"></i>
                        <span>Ver Web</span>
                    </a>

                    {{-- Insignia SemVer --}}
                    <a href="{{ route('about') }}" class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-xl text-xs font-mono font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition" title="Versión del sistema y build de GitHub">
                        <i class="fa-solid fa-code-branch text-[11px]"></i>
                        <span>{{ app(\App\Services\VersionService::class)->getVersion() }}</span>
                    </a>

                    {{-- Selector de tema Claro / Oscuro --}}
                    <button id="themeToggle" class="btn btn-ghost w-9 h-9 p-0 flex items-center justify-center rounded-xl" aria-label="Cambiar tema" title="Cambiar tema claro/oscuro">
                        <i id="themeIcon" class="fas fa-moon"></i>
                    </button>

                    {{-- Menú de usuario desplegable --}}
                    <div class="relative">
                        <button id="userMenuBtn" class="flex items-center gap-2 p-1.5 pr-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800/80 border border-transparent hover:border-slate-200 dark:hover:border-slate-700 transition" aria-haspopup="true" aria-expanded="false" title="Menú de usuario">
                            <div class="relative">
                                <img src="{{ $panelAvatar }}" alt="{{ $panelUserName }}" class="w-8 h-8 rounded-lg object-cover shadow-xs">
                                <div class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 bg-emerald-500 rounded-full border-2 border-white dark:border-slate-900"></div>
                            </div>
                            <div class="hidden md:flex flex-col items-start text-left">
                                <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 leading-tight max-w-[120px] truncate">{{ $panelUserName }}</span>
                                <span class="text-[10px] text-slate-500 dark:text-slate-400 leading-tight">{{ $panelRoleName }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-[10px] text-slate-400 ml-0.5"></i>
                        </button>

                        <div id="userMenu" class="absolute right-0 mt-2 w-64 card hidden shadow-2xl z-50 rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700" role="menu" aria-label="Menú de usuario">
                            {{-- Cabecera del desplegable --}}
                            <div class="p-4 border-b border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $panelAvatar }}" alt="{{ $panelUserName }}" class="w-10 h-10 rounded-xl object-cover shadow-sm">
                                    <div class="min-w-0">
                                        <div class="font-bold text-sm text-slate-900 dark:text-white truncate">{{ $panelUserName }}</div>
                                        <div class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ $panelUserEmail }}</div>
                                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                            {{ $panelRoleName }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Enlaces del menú --}}
                            <div class="py-2 text-xs">
                                <a href="{{ url('profile') }}" class="menu-item" role="menuitem">
                                    <i class="fa-solid fa-user w-5 text-slate-500 text-center"></i>
                                    <div>
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">Mi perfil</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400">Datos personales y contraseña</div>
                                    </div>
                                </a>

                                <a href="{{ url('settings') }}" class="menu-item" role="menuitem">
                                    <i class="fa-solid fa-gear w-5 text-slate-500 text-center"></i>
                                    <div>
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">Configuración</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400">Preferencias del sistema</div>
                                    </div>
                                </a>

                                <a href="{{ url('suggestions') }}" class="menu-item" role="menuitem">
                                    <i class="fa-solid fa-comment-dots w-5 text-slate-500 text-center"></i>
                                    <div>
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">Sugerencias</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400">Comentarios del equipo</div>
                                    </div>
                                </a>

                                <a href="{{ route('about') }}" class="menu-item" role="menuitem">
                                    <i class="fa-solid fa-circle-info w-5 text-emerald-600 text-center"></i>
                                    <div>
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">Acerca del sistema</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400">Versión {{ app(\App\Services\VersionService::class)->getVersion() }} &bull; SemVer</div>
                                    </div>
                                </a>

                                <a href="{{ config('app.stream_station_url', 'https://widestream.app/') }}" target="_blank" rel="noopener noreferrer" class="menu-item" role="menuitem">
                                    <i class="fa-solid fa-tower-broadcast w-5 text-indigo-500 text-center"></i>
                                    <div>
                                        <div class="font-semibold text-slate-800 dark:text-slate-200">Dashboard Emisora</div>
                                        <div class="text-[11px] text-slate-500 dark:text-slate-400">Servidor streaming WideStream</div>
                                    </div>
                                </a>
                            </div>

                            {{-- Botón de Cierre de Sesión --}}
                            <div class="p-2 border-t border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                                <button id="logout-button" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 transition" role="menuitem">
                                    <i class="fa-solid fa-right-from-bracket"></i>
                                    <span>Cerrar sesión</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- 5. Contenido Dinámico de la Vista --}}
            <div class="p-4 lg:p-6 space-y-4 flex-1">
                @if (isset($errors) && $errors->any())
                <div class="alert alert-danger shadow-xs">
                    <button class="close-btn" data-close aria-label="Cerrar">✕</button>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
                @endif

                @if (session('success') || session('mensaje'))
                <div class="alert alert-success shadow-xs">
                    <button class="close-btn" data-close aria-label="Cerrar">✕</button>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-600"></i>
                        <span>{{ session('success') ?? session('mensaje') }}</span>
                    </div>
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger shadow-xs">
                    <button class="close-btn" data-close aria-label="Cerrar">✕</button>
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
                @endif

                @hasSection('addbutton')
                <div class="flex justify-end">
                    <button data-modal-open="addModal" class="btn btn-primary shadow-sm hover:shadow-md transition">
                        <i class="fa-solid fa-plus-circle"></i> @yield('addbutton')
                    </button>
                </div>
                @endif

                @yield('datatable')
            </div>

            {{-- 6. Footer del Panel con SemVer y Build Info --}}
            @php
                $panelVersionService = app(\App\Services\VersionService::class);
                $panelVersionStr = $panelVersionService->getVersion();
                $panelCommitShort = $panelVersionService->getShortCommit();
                $panelCommitUrl = $panelVersionService->getCommitUrl();
            @endphp
            <footer class="mt-auto px-4 lg:px-6 py-4 border-t border-slate-200 dark:border-slate-800 bg-white/50 dark:bg-slate-900/50 backdrop-blur-xs text-xs text-slate-500 dark:text-slate-400">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 text-center sm:text-left">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                        <span class="font-bold text-slate-800 dark:text-slate-200">ECCA v4</span>
                        <span>&bull;</span>
                        <a href="{{ route('about') }}" class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md font-mono text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 dark:hover:bg-emerald-900/80 transition" title="Ver información del sistema y notas de entrega">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                            {{ $panelVersionStr }}
                        </a>
                        @if($panelCommitUrl)
                            <a href="{{ $panelCommitUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1 font-mono text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition" title="Ver commit en GitHub">
                                <i class="fa-brands fa-github text-xs"></i>
                                <span>{{ $panelCommitShort }}</span>
                            </a>
                        @else
                            <span class="font-mono text-xs text-slate-400">{{ $panelCommitShort }}</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-xs">
                        <a href="{{ route('about') }}" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition font-medium">Acerca de &bull; SemVer</a>
                        <span>&bull;</span>
                        <span>&copy; {{ date('Y') }} Emancipación Cristiana Afro</span>
                    </div>
                </div>
            </footer>
        </main>
    </div>

    {{-- Backdrop global para modales --}}
    <div id="backdrop" class="tw-modal-backdrop" aria-hidden="true"></div>

    @hasSection('modalFields')
    <section id="addModal" class="tw-modal" aria-modal="true" role="dialog" aria-hidden="true">
        <div class="tw-modal-panel">
            <div class="tw-modal-header">
                <h3 class="text-lg font-semibold">@yield('modalTitle','Crear registro')</h3>
                <button data-modal-close class="btn btn-ghost" aria-label="Cerrar"><i class="fa-solid fa-xmark"></i></button>
            </div>
            <form action="@yield('formaction')" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="tw-modal-body">@yield('modalFields')</div>
                <div class="tw-modal-footer">
                    <button type="button" data-modal-close class="btn">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </section>
    @endif

    @stack('scripts')

    <!-- Modal Logout Confirmación -->
    <section id="logoutModal" class="tw-modal hidden" role="dialog" aria-modal="true" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="tw-modal-panel transform scale-95 opacity-0 transition-all duration-200">
            <header class="tw-modal-header">
                <h3 id="logoutModalLabel" class="text-lg font-semibold">Confirmar cierre de sesión</h3>
                <button id="logoutModalClose" class="btn btn-ghost" aria-label="Cerrar modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </header>
            <div class="tw-modal-body">
                ¿Está seguro que desea cerrar sesión en el sistema ECCA v4?
            </div>
            <footer class="tw-modal-footer">
                <button id="cancelLogout" type="button" class="btn btn-secondary">Cancelar</button>
                <button id="confirmLogout" type="button" class="btn btn-primary bg-red-600 hover:bg-red-700 border-red-600">Cerrar sesión</button>
            </footer>
        </div>
    </section>

    <!-- Formulario oculto para el logout -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    {{-- Contenedor para inyección dinámica de modales de edición --}}
    <div id="dynamic-modal-container"></div>

    {{-- Toggle de tema claro/oscuro: binding robusto e independiente de dashboard.js.
         Para evitar un doble toggle (dashboard.js liga un listener directo al mismo
         botón), al preparar la vista se CLONA el botón #themeToggle para descartar
         cualquier listener previo y se liga aquí el único handler. Funciona aunque el
         bundle falle y se re-aplica en cada navegación de Turbo. --}}
    <script>
      (function () {
        function applyTheme(theme) {
          const root = document.documentElement;
          root.classList.toggle('dark', theme === 'dark');
          try { localStorage.setItem('theme', theme); } catch (e) {}
          const icon = document.getElementById('themeIcon');
          if (icon) icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
        }

        function bindThemeToggle() {
          const btn = document.getElementById('themeToggle');
          if (!btn) return;

          // Clona el botón para eliminar listeners previos (incluido el de dashboard.js)
          // y garantizar un único handler que alterne el tema una sola vez por clic.
          const fresh = btn.cloneNode(true);
          btn.parentNode.replaceChild(fresh, btn);
          fresh.dataset.bound = '1';

          fresh.addEventListener('click', function (e) {
            e.preventDefault();
            const isDark = document.documentElement.classList.contains('dark');
            applyTheme(isDark ? 'light' : 'dark');
          });

          // Sincroniza el ícono con el estado actual (aplicado por el pre-set del head).
          const icon = document.getElementById('themeIcon');
          if (icon) {
            icon.className = document.documentElement.classList.contains('dark') ? 'fas fa-sun' : 'fas fa-moon';
          }
        }

        // Se ejecuta después de dashboard.js (que corre en DOMContentLoaded/turbo:load):
        // al clonar el botón, el listener directo que dashboard.js pudo haber añadido
        // queda descartado, dejando este como único.
        if (document.readyState !== 'loading') {
          setTimeout(bindThemeToggle, 0);
        } else {
          document.addEventListener('DOMContentLoaded', function () { setTimeout(bindThemeToggle, 0); });
        }
        document.addEventListener('turbo:load', function () { setTimeout(bindThemeToggle, 0); });
      })();
    </script>
</body>
</html>
