<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- Meta tags para el tema -->
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#0f172a" media="(prefers-color-scheme: dark)">
    <meta name="color-scheme" content="light dark">
    
    <!-- Favicons -->
    <link rel="icon" href="{{ asset('images/fav/favicon.svg') }}" type="image/svg+xml" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/fav/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('images/fav/site.webmanifest') }}" />
    
    <title>Inicio de sesión | Emancipación Cristiana Afro</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Configuración de Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                        },
                        brand: {
                            green: '#009579',
                            dark: '#006653',
                        }
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Script para prevenir parpadeo del tema -->
    <script>
        (function() {
            // Obtener el tema guardado o la preferencia del sistema
            const savedTheme = localStorage.getItem('theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const theme = savedTheme || (systemPrefersDark ? 'dark' : 'light');

            // Aplicar el tema inmediatamente
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            // Guardar el tema si no estaba guardado
            if (!savedTheme) {
                localStorage.setItem('theme', theme);
            }
        })();
    </script>
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        /* Animación de fondo */
        .animated-bg {
            background: linear-gradient(135deg, #0e6826 0%, #0038b8 35%, #effc74 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Efecto de vidrio esmerilado */
        .glass {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .dark .glass {
            background: rgba(15, 23, 42, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Efecto de enfoque en inputs */
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Efecto hover en botones */
        .btn-hover {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-hover::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: 0.5s;
        }
        
        .btn-hover:hover::before {
            left: 100%;
        }
        
        /* Partículas de fondo */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }
        
        .particle {
            position: absolute;
            display: block;
            pointer-events: none;
            opacity: 0.3;
            animation: float 8s linear infinite;
        }
        
        .particle:nth-child(1) {
            left: 25%;
            width: 80px;
            height: 80px;
            animation-delay: 0s;
        }
        
        .particle:nth-child(2) {
            left: 10%;
            width: 20px;
            height: 20px;
            animation-delay: 2s;
            animation-duration: 12s;
        }
        
        .particle:nth-child(3) {
            left: 70%;
            width: 120px;
            height: 120px;
            animation-delay: 4s;
        }
        
        .particle:nth-child(4) {
            left: 40%;
            width: 60px;
            height: 60px;
            animation-delay: 0s;
            animation-duration: 18s;
        }
        
        .particle:nth-child(5) {
            left: 65%;
            width: 20px;
            height: 20px;
            animation-delay: 0s;
        }
        
        .particle:nth-child(6) {
            left: 75%;
            width: 110px;
            height: 110px;
            animation-delay: 3s;
        }
        
        .particle:nth-child(7) {
            left: 35%;
            width: 150px;
            height: 150px;
            animation-delay: 7s;
        }
        
        .particle:nth-child(8) {
            left: 50%;
            width: 25px;
            height: 25px;
            animation-delay: 15s;
            animation-duration: 45s;
        }
        
        .particle:nth-child(9) {
            left: 20%;
            width: 15px;
            height: 15px;
            animation-delay: 2s;
            animation-duration: 35s;
        }
        
        .particle:nth-child(10) {
            left: 85%;
            width: 150px;
            height: 150px;
            animation-delay: 0s;
            animation-duration: 11s;
        }
    </style>
</head>

<body class="animated-bg min-h-screen flex items-center justify-center p-4 relative">
    <!-- Partículas de fondo -->
    <div class="particles">
        <span class="particle bg-white/20 rounded-full"></span>
        <span class="particle bg-white/20 rounded-full"></span>
        <span class="particle bg-white/20 rounded-full"></span>
        <span class="particle bg-white/20 rounded-full"></span>
        <span class="particle bg-white/20 rounded-full"></span>
        <span class="particle bg-white/20 rounded-full"></span>
        <span class="particle bg-white/20 rounded-full"></span>
        <span class="particle bg-white/20 rounded-full"></span>
        <span class="particle bg-white/20 rounded-full"></span>
        <span class="particle bg-white/20 rounded-full"></span>
    </div>
    
    <!-- Contenedor principal -->
    <div class="w-full max-w-md z-10">
        <!-- Tarjeta de login -->
        <div class="glass rounded-2xl shadow-2xl p-8 transform transition-all duration-500 hover:scale-[1.01]">
            <!-- Logo y título -->
            <header class="text-center mb-8">
                <a href="/" class="inline-block transform transition-all duration-300 hover:scale-110">
                    <img src="{{ asset('images/logo/logo.png') }}" 
                         alt="Emancipación Cristiana Afro" 
                         class="h-20 w-auto mx-auto mb-4 drop-shadow-lg">
                </a>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white mb-2">Bienvenido de nuevo</h1>
                <p class="text-gray-600 dark:text-gray-400">Inicia sesión para acceder a tu cuenta</p>
            </header>
            
            <!-- Formulario de login -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <!-- Mensajes de error -->
                @if ($errors->any())
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-500"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700 dark:text-red-400">
                                    Por favor, corrige los siguientes errores:
                                </p>
                                <ul class="mt-2 list-disc list-inside text-sm text-red-700 dark:text-red-400">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                
                <!-- Campo de email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Correo electrónico
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" 
                               name="email" 
                               type="email" 
                               value="{{ old('email') }}" 
                               required 
                               autofocus
                               class="input-focus w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="tu@email.com">
                    </div>
                </div>
                
                <!-- Campo de contraseña -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Contraseña
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" 
                               name="password" 
                               type="password" 
                               required 
                               autocomplete="current-password"
                               class="input-focus w-full pl-10 pr-10 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                               placeholder="••••••••">
                        <button type="button" 
                                id="togglePassword" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                            <i id="passwordIcon" class="fas fa-eye-slash"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Opciones adicionales -->
                <div class="flex items-center justify-between">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" 
                           class="text-sm text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                    
                    <!-- Recordarme -->
                    <div class="flex items-center">
                        <input id="remember" 
                               name="remember" 
                               type="checkbox" 
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded bg-white dark:bg-gray-800">
                        <label for="remember" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                            Recordarme
                        </label>
                    </div>
                </div>
                
                <!-- Botón de envío -->
                <div>
                    <button type="submit" 
                            class="btn-hover w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-all duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Iniciar sesión
                    </button>
                </div>
            </form>
            
            <!-- Pie de página -->
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    © {{ date('Y') }} Emancipación Cristiana Afro. Todos los derechos reservados.
                </p>
            </div>
        </div>
    </div>
    
    <!-- Toggle de tema -->
    <div class="fixed bottom-4 right-4 z-20">
        <button id="themeToggle" 
                class="p-3 rounded-full bg-white dark:bg-gray-800 shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-110">
            <i id="themeIcon" class="fas fa-moon text-gray-700 dark:text-yellow-300"></i>
        </button>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle para mostrar/ocultar contraseña
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Cambiar el icono
                    if (type === 'text') {
                        passwordIcon.classList.remove('fa-eye-slash');
                        passwordIcon.classList.add('fa-eye');
                    } else {
                        passwordIcon.classList.remove('fa-eye');
                        passwordIcon.classList.add('fa-eye-slash');
                    }
                });
            }
            
            // Toggle de tema claro/oscuro
            const themeToggle = document.getElementById('themeToggle');
            const themeIcon = document.getElementById('themeIcon');
            const html = document.documentElement;
            
            // Función para actualizar el icono del tema
            function updateThemeIcon() {
                if (html.classList.contains('dark')) {
                    themeIcon.classList.remove('fa-moon');
                    themeIcon.classList.add('fa-sun');
                } else {
                    themeIcon.classList.remove('fa-sun');
                    themeIcon.classList.add('fa-moon');
                }
            }
            
            // Actualizar icono al cargar la página
            updateThemeIcon();
            
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    if (html.classList.contains('dark')) {
                        html.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    } else {
                        html.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    }
                    
                    // Actualizar icono después del cambio
                    updateThemeIcon();
                });
            }
            
            // Animación de entrada para la tarjeta
            const card = document.querySelector('.glass');
            if (card) {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
                
                setTimeout(() => {
                    card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100);
            }
            
            // Efecto de partículas interactivas
            const particles = document.querySelectorAll('.particle');
            
            particles.forEach(particle => {
                particle.addEventListener('mouseover', function() {
                    this.style.opacity = '0.6';
                    this.style.transform = 'scale(1.2)';
                });
                
                particle.addEventListener('mouseout', function() {
                    this.style.opacity = '0.3';
                    this.style.transform = 'scale(1)';
                });
            });
        });
    </script>
</body>
</html>