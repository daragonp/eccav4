<header class="bg-white/90 dark:bg-gray-900/90 backdrop-blur sticky top-0 border-b border-gray-200 dark:border-gray-700">
  <nav x-data="{ open:false }" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

    {{-- Barra principal (una sola franja) --}}
    <div class="relative flex h-20 items-center justify-between">

      {{-- Izquierda: botón móvil --}}
      <div class="flex items-center lg:hidden">
        <button @click="open=!open" aria-label="Abrir menú"
                class="inline-flex items-center justify-center rounded-md p-2 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-800">
          <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
               viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M4 6h16M4 12h16M4 18h16" /></svg>
          <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
               viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
               d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>

      {{-- Centro-izquierda: logo (crece un poco) --}}
      <div class="flex flex-1 justify-center lg:justify-start">
        <a href="/" class="flex items-center">
          <img src="{{ asset('images/logo/logo.png') }}" alt="ECCA_LOGO" class="h-12 sm:h-16 w-auto" />
        </a>
      </div>

      {{-- *** Menú de escritorio centrado (misma franja, centrado vertical y horizontal) *** --}}
      <div
        class="hidden lg:flex absolute inset-y-0 left-1/2 -translate-x-1/2 z-10
               items-center justify-center gap-6">

        <a href="/" class="text-gray-800 dark:text-gray-100 hover:text-brand-light">Inicio</a>

        {{-- Dropdown "Conócenos" --}}
        <div x-data="{dd:false}" class="relative">
          <button @click="dd=!dd"
                  class="inline-flex items-center gap-1 text-gray-800 dark:text-gray-100 hover:text-brand-light">
            Conócenos
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.044l3.71-3.813a.75.75 0 111.08 1.04l-4.24 4.36a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
          </button>

          {{-- El dropdown se abre justo debajo, centrado al botón --}}
          <div x-show="dd" @click.outside="dd=false" x-transition
               class="absolute top-full left-1/2 -translate-x-1/2 mt-2 w-56 rounded-md
                      border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-lg p-2">
            <a class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700" href="{{ url('mision') }}">Misión</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700" href="{{ url('objetivos') }}">Objetivo</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700" href="{{ url('declaracion') }}">Declaración de fe</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700" href="{{ url('meta') }}">Meta</a>
            <a class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-700" href="{{ url('mensajero') }}">Mensajero veloz</a>
          </div>
        </div>

        <a href="{{ url('worship-home') }}" class="text-gray-800 dark:text-gray-100 hover:text-brand-light">Palabras de vida</a>
        <a href="{{ url('lumbrera') }}" class="text-gray-800 dark:text-gray-100 hover:text-brand-light">Programas</a>
      </div>

      {{-- Derecha: toggle tema --}}
      <div class="flex items-center gap-2">
        <button id="toggleTheme"
                class="px-3 py-1 rounded bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
          <span id="themeIcon">🌙</span>
        </button>
      </div>
    </div>

    {{-- Menú móvil (colapsable) --}}
    <div x-show="open" x-transition class="lg:hidden py-2 space-y-1">
      <a href="/" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800">Inicio</a>

      <details class="px-3 py-2">
        <summary class="cursor-pointer select-none text-gray-800 dark:text-gray-100">Conócenos</summary>
        <div class="mt-2 ml-3 space-y-1">
          <a class="block px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800" href="{{ url('mision') }}">Misión</a>
          <a class="block px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800" href="{{ url('objetivos') }}">Objetivo</a>
          <a class="block px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800" href="{{ url('declaracion') }}">Declaración de fe</a>
          <a class="block px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800" href="{{ url('meta') }}">Meta</a>
          <a class="block px-2 py-1 rounded hover:bg-gray-100 dark:hover:bg-gray-800" href="{{ url('mensajero') }}">Mensajero veloz</a>
        </div>
      </details>

      <a href="{{ url('worship-home') }}" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800">Palabras de vida</a>
      <a href="{{ url('lumbrera') }}" class="block px-3 py-2 rounded hover:bg-gray-100 dark:hover:bg-gray-800">Programas</a>
    </div>
  </nav>

  {{-- Toggle dark --}}
  <script>
    const root = document.documentElement;
    const icon = document.getElementById('themeIcon');
    function syncIcon(){ if(icon) icon.textContent = root.classList.contains('dark') ? '☀️' : '🌙'; }
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') root.classList.add('dark');
    syncIcon();
    document.getElementById('toggleTheme')?.addEventListener('click', () => {
      root.classList.toggle('dark');
      localStorage.setItem('theme', root.classList.contains('dark') ? 'dark' : 'light');
      syncIcon();
    });
  </script>
</header>
