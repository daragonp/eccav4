<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2633231257763494"
     crossorigin="anonymous"></script>

{{-- Favicons / manifest --}}
<link rel="icon" href="{{ asset('images/fav/favicon.svg') }}" type="image/svg+xml" />
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}" />
<link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}" />

{{-- Lightbox CSS (versión actualizada) --}}
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css"
      crossorigin="anonymous" referrerpolicy="no-referrer" />

{{-- Pre-set dark antes del CSS (evita FOUC) --}}
<script>
  (function() {
    const theme = localStorage.getItem('theme') || 'light';
    if (theme === 'dark') {
      document.documentElement.classList.add('dark');
    }
  })();
</script>

{{-- Vite --}}
@vite([
  'resources/css/app.css',
  'resources/css/tw.css',
  'resources/css/radio-player.css',
  'resources/css/privacy-notice.css',
  'resources/js/app.js',
  'resources/js/radio-player.js',
  'resources/js/privacy-notice.js',
])

@stack('head')
<script src="https://kit.fontawesome.com/71f1c28685.js" crossorigin="anonymous"></script>
<title>@yield('title') - Emancipación Cristiana Afro</title>
</head>

<body class="font-sans antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
  
  {{-- Componente Alpine de Privacidad con inicialización segura --}}
  <div x-data="privacyNoticeApp()" x-init="init()" style="display: contents;">
    
    {{-- Header/Menu --}}
    @include('layouts.menu')

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

    {{-- Modales globales --}}
    @stack('modals')

    {{-- Footer + Player --}}
    @include('footer')
    @include('player')

    {{-- Aviso de Privacidad --}}
    @include('privacy-notice')

  </div>

  {{-- Lightbox JS (versión actualizada) --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"
          crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  {{-- Fix z-index para Lightbox --}}
  <style>
    #lightboxOverlay{ z-index:2147483646 !important; position:fixed !important; inset:0 !important; }
    .lightbox, .lb-outerContainer, .lb-dataContainer{ z-index:2147483647 !important; }
  </style>

  {{-- Scripts por vista --}}
  @stack('scripts')
</body>
</html>