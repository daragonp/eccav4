<!-- resources/views/layouts/app.blade.php (o el layout que uses) -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Emancipación Colombiana Cristiana Afro') }}</title>

    {{-- Carga con Vite en este orden: Bootstrap+tu CSS, luego Tailwind, luego JS --}}
    @vite([
      'resources/css/app.css',   {{-- Bootstrap + tu CSS actual --}}
      'resources/css/tw.css',    {{-- Tailwind v4 (compilado por Vite) --}}
      'resources/js/app.js',     {{-- Bootstrap JS (bundle) + tu JS --}}
      'resources/js/radio-player.js'
    ])
  </head>
  <body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 dark:text-gray-100">
      @include('layouts.navigation')

      <header class="bg-white dark:bg-gray-800 shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          {{ $header ?? '' }}
        </div>
      </header>

      <main>
        {{ $slot ?? '' }}
      </main>
    </div>
  </body>
</html>
