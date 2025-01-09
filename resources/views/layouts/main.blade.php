<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="{{ asset('images/fav/favicon.svg') }}" type="image/svg+xml" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}" />
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/menu.js'])


    <script src="https://kit.fontawesome.com/71f1c28685.js" crossorigin="anonymous"></script>
    <title>@yield('title') - Emancipación Cristiana Afro</title>
</head>

<body>
    @include('layouts.menu')

    <main class="py-4">
        @yield('content')
    </main>

    @include('footer')
</body>

<script>
    const audioPlayer = document.getElementById('audioPlayer');
    const toggleButton = document.getElementById('toggleButton');
    const playIcon = document.getElementById('playIcon');
    const pauseIcon = document.getElementById('pauseIcon');

    toggleButton.addEventListener('click', () => {
        if (audioPlayer.paused) {
            audioPlayer.play();
            playIcon.style.display = 'none';
            pauseIcon.style.display = 'block';
        } else {
            audioPlayer.pause();
            playIcon.style.display = 'block';
            pauseIcon.style.display = 'none';
        }
    });

    audioPlayer.addEventListener('error', (error) => {
        console.error("Error al reproducir el audio:", error);
        alert("Hubo un error al reproducir la transmisión. Por favor, inténtalo de nuevo más tarde.");
    });
</script>
</html>
