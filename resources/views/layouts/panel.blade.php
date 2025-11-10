<!DOCTYPE html>
<html lang="es" class="scroll-smooth">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <!-- Meta tags para el tema -->
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
    <meta name="color-scheme" content="light dark">
    {{-- Favicons / manifest --}}
    <link rel="icon" href="{{ asset('images/fav/favicon.svg') }}" type="image/svg+xml" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}" />
    <title>@yield('title', 'Panel') — Emancipación Cristiana Afro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    @vite(['resources/css/dashboard.css', 'resources/js/dashboard.js'])
    <script src="https://kit.fontawesome.com/71f1c28685.js" crossorigin="anonymous"></script>
</head>

<body class="antialiased">
    <div class="panel-shell">
        {{-- Sidebar --}}
        <aside id="sidebar" class="panel-sidebar transition-all duration-300 ease-in-out">
            {{-- Logo y Perfil --}}
            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-lg bg-linear-to-br from-green-600 to-green-700 flex items-center justify-center text-white font-bold shadow-lg">
                        ECA
                    </div>
                    <div>
                        <div class="text-lg font-bold text-slate-900 dark:text-white">Dashboard</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400">Panel de Administración</div>
                    </div>
                </div>
            </div>

            {{-- Información del Usuario --}}
            <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-slate-900"></div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="font-semibold text-slate-900 dark:text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-slate-500 dark:text-slate-400 truncate">{{ auth()->user()->roles->first()?->name ?? 'Sin rol' }}</div>
                    </div>
                </div>
            </div>

            {{-- Mini reproductor --}}
            <div class="p-4">
                @includeWhen(View::exists('admin.player-mini'), 'admin.player-mini')
            </div>

            {{-- Navegación --}}
            <nav class="flex-1 overflow-y-auto px-2 pb-4">
                <div class="space-y-1">
                    {{-- Panel principal --}}
                    <a href="{{ url('dashboard') }}" class="nav-item group {{ request()->is('dashboard') ? 'active' : '' }}">
                        <div class="flex items-center gap-3">
                            <div class="nav-icon">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                            <span class="nav-text">Panel principal</span>
                        </div>
                    </a>

                    {{-- Administración --}}
                    <div class="nav-section">
                        <div class="nav-section-title">Administración</div>
                        <a href="{{ url('show-roles') }}" class="nav-item group {{ request()->is('show-roles*') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="nav-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <span class="nav-text">Roles</span>
                            </div>
                        </a>
                        <a href="{{ url('show-users') }}" class="nav-item group {{ request()->is('show-users*') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="nav-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <span class="nav-text">Usuarios</span>
                            </div>
                        </a>
                    </div>

                    {{-- Contenido --}}
                    <div class="nav-section">
                        <div class="nav-section-title">Contenido</div>
                        <a href="{{ url('show-quote') }}" class="nav-item group {{ request()->is('show-quote*') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="nav-icon">
                                    <i class="fas fa-book-bible"></i>
                                </div>
                                <span class="nav-text">Palabra de vida</span>
                            </div>
                        </a>
                        <a href="{{ url('show-slider') }}" class="nav-item group {{ request()->is('show-slider*') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="nav-icon">
                                    <i class="fas fa-sliders"></i>
                                </div>
                                <span class="nav-text">Banner Carrusel</span>
                            </div>
                        </a>
                        <a href="{{ url('show-categories') }}" class="nav-item group {{ request()->is('show-categories*') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="nav-icon">
                                    <i class="fas fa-podcast"></i>
                                </div>
                                <span class="nav-text">PodCast</span>
                            </div>
                        </a>
                        <a href="{{ url('show-schedule') }}" class="nav-item group {{ request()->is('show-schedule*') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="nav-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <span class="nav-text">Programación</span>
                            </div>
                        </a>
                        {{-- Después del enlace de Programación existente --}}
                        <a href="{{ url('/holiday-schedule') }}"
                            class="nav-item group {{ request()->is('holiday-schedule') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="nav-icon">
                                    <i class="fa-solid fa-calendar-days"></i>
                                </div>
                                <span class="nav-text">Días Festivos</span>
                            </div>
                        </a>

                        <a href="{{ url('show-news') }}" class="nav-item group {{ request()->is('show-news*') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="nav-icon">
                                    <i class="fas fa-newspaper"></i>
                                </div>
                                <span class="nav-text">Mensaje de la semana</span>
                            </div>
                        </a>
                        <a href="{{ url('show-looks') }}" class="nav-item group {{ request()->is('show-looks*') ? 'active' : '' }}">
                            <div class="flex items-center gap-3">
                                <div class="nav-icon">
                                    <i class="fas fa-earth-africa"></i>
                                </div>
                                <span class="nav-text">Mirada Afro</span>
                            </div>
                        </a>
                    </div>
                </div>
            </nav>
        </aside>

        {{-- Sidebar colapsado (iconos) --}}
        <aside id="sidebar-collapsed" class="fixed left-0 top-0 h-full w-16 bg-white dark:bg-slate-900 shadow-lg z-30">
            <div class="flex flex-col h-full">
                {{-- Logo --}}
                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                    <div class="w-8 h-8 rounded-lg bg-linear-to-br from-green-600 to-green-700 flex items-center justify-center text-white font-bold text-xs shadow-lg">
                        ECA
                    </div>
                </div>

                {{-- Navegación --}}
                <nav class="flex-1 overflow-y-auto px-2 py-4">
                    <div class="space-y-2">
                        {{-- Panel principal --}}
                        <a href="{{ url('dashboard') }}" class="nav-item-collapsed group {{ request()->is('dashboard') ? 'active' : '' }}" title="Panel principal">
                            <div class="nav-icon-collapsed">
                                <i class="fas fa-tachometer-alt"></i>
                            </div>
                        </a>

                        {{-- Separador --}}
                        <div class="border-t border-slate-200 dark:border-slate-700 my-2"></div>

                        {{-- Administración --}}
                        <a href="{{ url('show-roles') }}" class="nav-item-collapsed group {{ request()->is('show-roles*') ? 'active' : '' }}" title="Roles">
                            <div class="nav-icon-collapsed">
                                <i class="fas fa-user-tie"></i>
                            </div>
                        </a>
                        <a href="{{ url('show-users') }}" class="nav-item-collapsed group {{ request()->is('show-users*') ? 'active' : '' }}" title="Usuarios">
                            <div class="nav-icon-collapsed">
                                <i class="fas fa-users"></i>
                            </div>
                        </a>

                        {{-- Separador --}}
                        <div class="border-t border-slate-200 dark:border-slate-700 my-2"></div>

                        {{-- Contenido --}}
                        <a href="{{ url('show-quote') }}" class="nav-item-collapsed group {{ request()->is('show-quote*') ? 'active' : '' }}" title="Palabra de vida">
                            <div class="nav-icon-collapsed">
                                <i class="fas fa-book-bible"></i>
                            </div>
                        </a>
                        <a href="{{ url('show-slider') }}" class="nav-item-collapsed group {{ request()->is('show-slider*') ? 'active' : '' }}" title="Banner Carrusel">
                            <div class="nav-icon-collapsed">
                                <i class="fas fa-sliders"></i>
                            </div>
                        </a>
                        <a href="{{ url('show-categories') }}" class="nav-item-collapsed group {{ request()->is('show-categories*') ? 'active' : '' }}" title="PodCast">
                            <div class="nav-icon-collapsed">
                                <i class="fas fa-podcast"></i>
                            </div>
                        </a>
                        <a href="{{ url('show-schedule') }}" class="nav-item-collapsed group {{ request()->is('show-schedule*') ? 'active' : '' }}" title="Programación">
                            <div class="nav-icon-collapsed">
                                <i class="fas fa-clock"></i>
                            </div>
                        </a>
                        <a href="{{ url('show-news') }}" class="nav-item-collapsed group {{ request()->is('show-news*') ? 'active' : '' }}" title="Mensaje de la semana">
                            <div class="nav-icon-collapsed">
                                <i class="fas fa-newspaper"></i>
                            </div>
                        </a>
                        <a href="{{ url('show-looks') }}" class="nav-item-collapsed group {{ request()->is('show-looks*') ? 'active' : '' }}" title="Mirada Afro">
                            <div class="nav-icon-collapsed">
                                <i class="fas fa-earth-africa"></i>
                            </div>
                        </a>
                    </div>
                </nav>

                {{-- Usuario --}}
                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-slate-900"></div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main --}}
        <main class="panel-main">
            {{-- Topbar --}}
            <header class="panel-topbar">
                <div class="flex items-center gap-2">
                    <button id="toggleSidebar" class="btn btn-ghost" aria-label="Abrir/cerrar menú">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title font-semibold">@yield('pageheading', 'Panel')</h1>
                </div>

                <div class="flex items-center gap-2">
                    {{-- Toggle dark/light --}}
                    <button id="themeToggle" class="btn btn-ghost" aria-label="Cambiar tema">
                        <i id="themeIcon"></i>
                    </button>

                    {{-- Menú de usuario --}}
                    <div class="relative">
                        <button id="userMenuBtn" class="btn btn-ghost flex items-center gap-2" aria-haspopup="true" aria-expanded="false">
                            <div class="relative">
                                <div class="w-8 h-8 rounded-full bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold text-sm shadow-md">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div class="absolute bottom-0 right-0 w-2 h-2 bg-green-500 rounded-full border border-white dark:border-slate-900"></div>
                            </div>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>

                        <div id="userMenu" class="absolute right-0 mt-2 w-64 card hidden" role="menu" aria-label="Menú de usuario">
                            <div class="card-body p-0">
                                {{-- Cabecera del menú --}}
                                <div class="p-4 border-b border-slate-200 dark:border-slate-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-linear-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-semibold shadow-md">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-900 dark:text-white">{{ auth()->user()->name }}</div>
                                            <div class="text-xs text-slate-500 dark:text-slate-400">{{ auth()->user()->roles->first()?->name ?? 'Sin rol' }}</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Opciones del menú --}}
                                <div class="py-2 max-h-96 overflow-y-auto">
                                    <a href="{{ url('profile') }}" class="menu-item" role="menuitem">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                                <i class="fas fa-user text-slate-600 dark:text-slate-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-slate-900 dark:text-white">Mi perfil</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">Administra tu información personal</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ url('settings') }}" class="menu-item" role="menuitem">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                                <i class="fas fa-cog text-slate-600 dark:text-slate-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-slate-900 dark:text-white">Configuración</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">Ajustes del sistema</div>
                                            </div>
                                        </div>
                                    </a>

                                    <a href="{{ url('suggestions') }}" class="menu-item" role="menuitem">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                                <i class="fas fa-comment text-slate-600 dark:text-slate-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-slate-900 dark:text-white">Sugerencias</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">Envía tus comentarios</div>
                                            </div>
                                        </div>
                                    </a>

                                    {{-- Enlace al dashboard de la emisora --}}
                                    <a href="https://a12.asurahosting.com/station/199/" target="_blank" class="menu-item" role="menuitem">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                                                <i class="fas fa-broadcast-tower text-slate-600 dark:text-slate-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-slate-900 dark:text-white">Dashboard Emisora</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">Administración de la emisora</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                {{-- Pie del menú --}}
                                <div class="p-4 border-t border-slate-200 dark:border-slate-700">
                                    <button id="logout-button" class="menu-item w-full text-left" role="menuitem">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                                <i class="fas fa-sign-out-alt text-red-600 dark:text-red-400"></i>
                                            </div>
                                            <div>
                                                <div class="font-medium text-red-600 dark:text-red-400">Cerrar sesión</div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400">Salir del sistema</div>
                                            </div>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Contenido --}}
            <div class="p-4 lg:p-6 space-y-4">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <button class="close-btn" data-close aria-label="Cerrar">✕</button>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
                @endif

                @if (session('success'))
                <div class="alert alert-success">
                    <button class="close-btn" data-close aria-label="Cerrar">✕</button>
                    {{ session('success') }}
                </div>
                @endif

                @hasSection('addbutton')
                <div class="flex justify-end">
                    <button data-modal-open="addModal" class="btn btn-primary">
                        <i class="fa-solid fa-plus-circle"></i> @yield('addbutton')
                    </button>
                </div>
                @endif

                <!-- Tarjeta mejorada con mejor tipografía y diseño -->
                <div class="card">
                    @hasSection('cardTitle')
                    <div class="card-header bg-linear-to-r from-blue-500 to-indigo-600 text-white">
                        <h2 class="text-xl font-semibold">@yield('cardTitle')</h2>
                    </div>
                    @endif

                    <div class="card-body">
                        @hasSection('cardDescription')
                        <div class="card-text">
                            @yield('cardDescription')
                        </div>
                        @endif

                        <div class="mt-4">
                            @yield('datatable')
                        </div>
                    </div>

                    @hasSection('cardFooter')
                    <div class="card-footer">
                        @yield('cardFooter')
                    </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
    {{-- Backdrop global siempre disponible --}}
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

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>

    @stack('scripts')

    <!-- ✅ Modal Logout Corregido -->
    <section id="logoutModal" class="tw-modal hidden" role="dialog" aria-modal="true" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="tw-modal-panel transform scale-95 opacity-0 transition-all duration-200">
            <header class="tw-modal-header">
                <h3 id="logoutModalLabel" class="text-lg font-semibold">Confirmar cierre de sesión</h3>
                <button id="logoutModalClose" class="btn btn-ghost" aria-label="Cerrar modal">
                    <i class="fa-solid fa-x"></i>
                </button>
            </header>
            <div class="tw-modal-body">
                ¿Está seguro que desea cerrar sesión?
            </div>
            <footer class="tw-modal-footer">
                <button id="cancelLogout" type="button" class="btn btn-secondary">Cancelar</button>
                <button id="confirmLogout" type="button" class="btn btn-primary">Cerrar sesión</button>
            </footer>
        </div>
    </section>
    <!-- ✅ Formulario oculto para el logout -->
    <form id="logoutForm" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</body>
</html>