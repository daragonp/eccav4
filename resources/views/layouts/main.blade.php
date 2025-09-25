<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  {{-- Favicons / manifest --}}
  <link rel="icon" href="{{ asset('images/fav/favicon.svg') }}" type="image/svg+xml" />
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}" />
  <link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}" />

  {{-- Lightbox CSS --}}
  <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.min.css"
        integrity="sha512-56FZ3rC7u0qKp0Yu4wQp0FzY6dQn0tqGQ6HqK3X5n7b1o1sYQZfXpXbA2bq3qz8o5p4u/A1QN6wC1cZC4wQZxg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

  {{-- Pre-set dark antes del CSS (evita FOUC) --}}
  <script>
    (function () {
      try { if (localStorage.getItem('theme') === 'dark') document.documentElement.classList.add('dark'); } catch(e) {}
    })();
  </script>

  {{-- CSS --}}
  @vite([
    'resources/css/app.css',
    'resources/css/tw.css',
    'resources/css/radio-player.css'
  ])

  {{-- Hook para estilos por-vista (ej. fixes de z-index) --}}
  @stack('head')

  {{-- Iconos --}}
  <script src="https://kit.fontawesome.com/71f1c28685.js" crossorigin="anonymous"></script>

  <title>@yield('title') - Emancipación Cristiana Afro</title>
</head>
<body class="font-sans antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
  {{-- Header/Menu --}}
  <header class="relative z-50">
    @include('layouts.menu')
  </header>

  {{-- Cabecera de página opcional --}}
  @hasSection('page-hero')
    <div class="page-container page-hero">
      @yield('page-hero')
    </div>
  @endif

  {{-- Contenido principal --}}
  <main class="page-container py-6 sm:py-10">
    @yield('content')
  </main>

  {{-- Aquí inyectaremos modales globales para que queden fuera del flujo del main --}}
  @stack('modals')

  {{-- Footer + Player --}}
  @include('footer')
  @include('player')

  <script>
    const defaultDirectorPhotoUrl = @json(asset('images/genericprogramimage.png'));
    const radioStreamUrl = @json(env('RADIO_STREAM_URL', 'https://example.com/your-radio-stream'));
  </script>

  {{-- JS --}}
  @vite([
    'resources/js/app.js',
    'resources/js/menu.js',
    'resources/js/radio-player.js'
  ])

  {{-- Lightbox JS (sin jQuery extra) --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js"
          integrity="sha512-6eWQ7+H9x9S5mrtLrj6Q2d8d8KJQWzq3H6t8w8qf1x2JH0m5h5h3fS0pM1iy5k9q1mC0u3mP7wQ2JrQwE3m5xQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  {{-- Fix global de z-index para Lightbox (por encima del reproductor) --}}
  <style>
    #lightboxOverlay{ z-index:2147483646 !important; position:fixed !important; inset:0 !important; }
    .lightbox, .lb-outerContainer, .lb-dataContainer{ z-index:2147483647 !important; }
  </style>

  {{-- Hook para scripts por-vista --}}
  @stack('scripts')
</body>
</html>
