<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset('images/fav/favicon.svg') }}" type="image/svg+xml" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}" />
    <title>Inicio de sesión | Emancipación Cristiana Afro</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card shadow-lg p-4 rounded w-100" style="max-width: 430px; background-color: #fff;">
            <header class="text-center mb-4">
                <a href="/">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="Emancipación Cristiana Afro" class="img-fluid" style="max-width: 150px;">
                </a>
            </header>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <x-label for="email" :value="__('Correo electrónico')" />
                    <x-input id="email" class="form-control" type="email" name="email" :value="old('email')" required autofocus />
                </div>
                <div class="mb-3">
                    <x-label for="password" :value="__('Contraseña')" />
                    <x-input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
                </div>
                <div class="d-flex justify-content-between align-items-center mb-4">
                    @if (Route::has('password.request'))
                        <a class="text-muted" href="{{ route('password.request') }}">
                            {{ __('¿Olvidó su contraseña?') }}
                        </a>
                    @endif
                    <button type="submit" class="btn btn-primary w-100">{{ __('Iniciar sesión') }}</button>
                </div>
            </form>
            {{-- <div class="signup">
                <span class="signup">¿No se ha registrado?
                    <label for="check">Regístrese</label>
                </span>
            </div> --}}
        </div>
    </div>
</body>

</html>
