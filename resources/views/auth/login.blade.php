<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/fav/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/fav/favicon-16x16.png') }}">
    <link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}">
    <title>Inicio de sesión | Emancipación Cristiana Afro</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <div class="container">
        <input type="checkbox" id="check">
        <div class="login form">
            <header>
                <a href="/">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="Emancipación Cristiana Afro"
                        style="width:150px;">
                </a>
            </header>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div>
                    <x-label for="email" :value="__('Correo electrónico')" />

                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                        required autofocus />
                </div>
                <div class="mt-4">
                    <x-label for="password" :value="__('Contraseña')" />

                    <x-input id="password" class="block mt-1 w-full" type="password" name="password" required
                        autocomplete="current-password" />
                </div>
                <div>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                            {{ __('¿Olvidó su contraseña?') }}
                        </a>
                    @endif
                    <input type="submit" class="button" value="{{ __('Iniciar sesión') }}">
                </div>
            </form>
            {{-- <div class="signup">
                <span class="signup">¿No se ha registrado?
                    <label for="check">Regístrese</label>
                </span>
            </div> --}}
        </div>
        {{-- <div class="registration form">
            <header><a href="/">
                    <img src="{{ asset('images/logo/logo.png') }}" alt="Emancipación Cristiana Afro"
                        style="width:150px;"></a>
            </header>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div>
                    <x-label for="name" :value="__('Nombre')" />

                    <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                </div>
                <div class="mt-4">
                    <x-label for="email" :value="__('Correo')" />

                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>
                <div class="mt-4">
                    <x-label for="password" :value="__('Contraseña')" />
                    <x-input id="password" class="block mt-1 w-full"
                                    type="password"
                                    name="password"
                                    required autocomplete="new-password" />
                </div>
                <div class="mt-4">
                    <x-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                    <x-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required />
                </div>
                <input type="submit" class="button" value="Crear cuenta">
            </form>
            <div class="signup">
                <span class="signup">¿Ya está registrado?
                    <label for="check">Iniciar sesión</label>
                </span>
            </div>
        </div> --}}
    </div>
</body>

</html>
