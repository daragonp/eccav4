<header
    data-turbo-permanent
    class="sticky top-0 z-50 w-full
         bg-white/90 dark:bg-gray-900/90
         supports-[backdrop-filter]:bg-white/70 supports-[backdrop-filter]:dark:bg-gray-900/60
         backdrop-blur-xl
         border-b border-gray-200/60 dark:border-gray-700/50
         transition-all duration-300 ease-in-out">
    <nav x-data="{ open: false, mobileDropdownOpen: null }" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        
        <div class="relative flex h-16 items-center justify-between">

            
            <div class="flex-shrink-0">
                <a href="/" class="flex items-center group">
                    <img src="<?php echo e(asset('images/logo/logo.png')); ?>" alt="ECCA_LOGO" class="h-10 w-auto transition-transform duration-300 ease-in-out group-hover:scale-105" />
                </a>
            </div>

            
            <div class="hidden lg:flex lg:items-center lg:space-x-1">
                
                <div class="flex items-center space-x-1">
                    <a href="/" class="nav-link <?php echo e(request()->is('/') ? 'nav-link-active' : ''); ?>">Inicio</a>

                    
                    <div x-data="{ dd: false }" @mouseenter="dd = true" @mouseleave="dd = false" class="relative">
                        <button @click="dd = !dd" @focus="dd = true" @blur="setTimeout(() => dd = false, 150)"
                            class="nav-link-dropdown <?php echo e(request()->routeIs('mision', 'objetivos', 'declaracion', 'meta', 'mensajero') ? 'nav-link-active' : ''); ?>">
                            Conócenos
                            <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': dd }" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="dd" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute top-full left-1/2 -translate-x-1/2 mt-3 w-64 rounded-xl
                              border border-gray-200/60 dark:border-gray-700/60 bg-white/95 dark:bg-gray-800/95 shadow-2xl backdrop-blur-sm p-2">
                            <a href="<?php echo e(url('mision')); ?>" class="dropdown-item">Misión</a>
                            <a href="<?php echo e(url('objetivos')); ?>" class="dropdown-item">Objetivo</a>
                            <a href="<?php echo e(url('declaracion')); ?>" class="dropdown-item">Declaración de fe</a>
                            <a href="<?php echo e(url('meta')); ?>" class="dropdown-item">Meta</a>
                            <a href="<?php echo e(url('mensajero')); ?>" class="dropdown-item">Mensajero veloz</a>
                        </div>
                    </div>

                    <a href="<?php echo e(url('worship-home')); ?>" class="nav-link <?php echo e(request()->is('worship-home') ? 'nav-link-active' : ''); ?>">Palabras de vida</a>
                    <a href="<?php echo e(url('worship')); ?>" class="nav-link <?php echo e(request()->is('worship') ? 'nav-link-active' : ''); ?>">Culto dominical</a>
                    <a href="<?php echo e(url('/biblia')); ?>" class="nav-link <?php echo e(request()->is('/biblia') ? 'nav-link-active' : ''); ?>">Biblia</a>
                    <a href="<?php echo e(url('lumbrera')); ?>" class="nav-link <?php echo e(request()->is('lumbrera') ? 'nav-link-active' : ''); ?>">Programas</a>
                </div>
            </div>

            
            <div class="flex items-center space-x-3">
                
                <button id="toggleTheme" aria-label="Cambiar tema" class="p-2 rounded-lg bg-gray-100/80 dark:bg-gray-800/80 text-gray-600 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors duration-200">
                    <span id="themeIcon" class="text-xl">🌙</span>
                </button>

                
                <button @click="open = !open" aria-label="Abrir menú" aria-expanded="false" :aria-expanded="open.toString()"
                    class="lg:hidden inline-flex items-center justify-center rounded-md p-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors duration-200">
                    <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        
        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-2"
            class="lg:hidden border-t border-gray-200 dark:border-gray-700 mt-2 pt-4 pb-6">
            <div class="space-y-1">
                <a href="/" class="mobile-nav-link <?php echo e(request()->is('/') ? 'mobile-nav-link-active' : ''); ?>">Inicio</a>

                
                <div>
                    <button @click="mobileDropdownOpen = mobileDropdownOpen === 'conocenos' ? null : 'conocenos'" class="mobile-nav-dropdown-button <?php echo e(request()->routeIs('mision', 'objetivos', 'declaracion', 'meta', 'mensajero') ? 'mobile-nav-link-active' : ''); ?>">
                        Conócenos
                        <svg class="h-4 w-4 transition-transform duration-200" :class="{ 'rotate-180': mobileDropdownOpen === 'conocenos' }" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    <div x-show="mobileDropdownOpen === 'conocenos'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 max-h-0" x-transition:enter-end="opacity-100 max-h-60" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 max-h-60" x-transition:leave-end="opacity-0 max-h-0"
                         class="overflow-hidden">
                        <div class="mt-2 ml-4 space-y-1">
                            <a href="<?php echo e(url('mision')); ?>" class="mobile-sub-link">Misión</a>
                            <a href="<?php echo e(url('objetivos')); ?>" class="mobile-sub-link">Objetivo</a>
                            <a href="<?php echo e(url('declaracion')); ?>" class="mobile-sub-link">Declaración de fe</a>
                            <a href="<?php echo e(url('meta')); ?>" class="mobile-sub-link">Meta</a>
                            <a href="<?php echo e(url('mensajero')); ?>" class="mobile-sub-link">Mensajero veloz</a>
                        </div>
                    </div>
                </div>

                <a href="<?php echo e(url('worship-home')); ?>" class="mobile-nav-link <?php echo e(request()->is('worship-home') ? 'mobile-nav-link-active' : ''); ?>">Palabras de vida</a>
                <a href="<?php echo e(url('worship')); ?>" class="mobile-nav-link <?php echo e(request()->is('worship') ? 'mobile-nav-link-active' : ''); ?>">Culto dominical</a>
                <a href="<?php echo e(url('/biblia')); ?>" class="mobile-nav-link <?php echo e(request()->is('/biblia') ? 'mobile-nav-link-active' : ''); ?>">Biblia</a>
                <a href="<?php echo e(url('lumbrera')); ?>" class="mobile-nav-link <?php echo e(request()->is('lumbrera') ? 'mobile-nav-link-active' : ''); ?>">Programas</a>
            </div>
        </div>
    </nav>

    
    <script data-turbo-eval="true">
        (function() {
            const root = document.documentElement;
            const icon = document.getElementById('themeIcon');

            function syncIcon() {
                if (icon) icon.textContent = root.classList.contains('dark') ? '☀️' : '🌙';
            }

            // Aplica preferencia guardada
            try {
                const saved = localStorage.getItem('theme');
                if (saved === 'dark') root.classList.add('dark');
                if (saved === 'light') root.classList.remove('dark');
            } catch (_) {}
            syncIcon();

            // Toggle
            const btn = document.getElementById('toggleTheme');
            if (btn) {
                btn.onclick = () => {
                    root.classList.toggle('dark');
                    localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
                    syncIcon();
                };
            }

            // Sombra más pronunciada al hacer scroll
            const headerEl = document.currentScript.closest('header');
            const onScroll = () => {
                const y = window.scrollY || document.documentElement.scrollTop;
                // Añadimos una sombra más elegante y un ligero cambio de fondo
                if (y > 10) {
                    headerEl.classList.add('shadow-lg', 'bg-white/95', 'dark:bg-gray-900/95');
                    headerEl.classList.remove('shadow-sm', 'bg-white/90', 'dark:bg-gray-900/90');
                } else {
                    headerEl.classList.remove('shadow-lg', 'bg-white/95', 'dark:bg-gray-900/95');
                    headerEl.classList.add('shadow-sm', 'bg-white/90', 'dark:bg-gray-900/90');
                }
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });
        })();
    </script>
</header><?php /**PATH /Library/Proyectos/eccav4/resources/views/layouts/menu.blade.php ENDPATH**/ ?>