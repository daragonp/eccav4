<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="icon" href="{{ asset('images/fav/favicon.svg') }}" type="image/svg+xml" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.css"
        integrity="sha512-DKdRaC0QGJ/kjx0U0TtJNCamKnN4l+wsMdION3GG0WVK6hIoJ1UPHRHeXNiGsXdrmq19JJxgIubb/Z7Og2qJww=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/css/radio-player.css', 'resources/js/menu.js'])
    <script src="https://kit.fontawesome.com/71f1c28685.js" crossorigin="anonymous"></script>
    <title>@yield('title') - Emancipación Cristiana Afro</title>
</head>

<body>
    @include('layouts.menu')

    <main class="py-4">
        @yield('content')
    </main>
    @include('footer')

    @include('player') {{-- Esto asumo que incluye tu reproductor --}}
</body>
<script>
    // Usar genericprogramimage.png como fallback predeterminado
    const defaultDirectorPhotoUrl = '{{ asset('images/genericprogramimage.png') }}';
    const radioStreamUrl =
        '{{ env('RADIO_STREAM_URL', 'https://example.com/your-radio-stream') }}'; // Fallback si no está en .env
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js"
    integrity="sha512-KbRFbjA5bwNan6DvPl1ODUolvTTZ/vckssnFhka5cG80JVa5zSlRPCr055xSgU/q6oMIGhZWLhcbgIC0fyw3RQ=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox-plus-jquery.js"
    integrity="sha512-C6X3/hskB+isJVpYkZ68yausQGoZnr7w41ps9fftfXSPhe51ygvgh8NW2b9XCxCpIMUku2BTUOd2JtswTkl29g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@vite(['resources/js/app.js', 'resources/js/radio-player.js'])

</html>
