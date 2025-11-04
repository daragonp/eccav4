{{-- Heredamos la plantilla principal --}}
@extends('layouts.main')

{{-- Definimos el título --}}
@section('title', 'Iniciar Sesión')

{{-- Inyectamos CSS personalizado --}}
@push('head')
<style>
  /* Variables específicas para esta página */
  :root {
    --login-glow: rgba(138, 164, 70, 0.15);
    --login-glow-dark: rgba(138, 164, 70, 0.25);
  }

  /* Contenedor principal con fondo degradado */
  .login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    position: relative;
    overflow: hidden;
  }

  .dark .login-container {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  }

  /* Efecto de brillo sutil en el fondo */
  .login-glow {
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    filter: blur(100px);
    opacity: 0.5;
    animation: float 20s ease-in-out infinite;
  }

  .glow-1 {
    background-color: var(--login-glow);
    top: -100px;
    left: -100px;
  }

  .glow-2 {
    background-color: var(--login-glow);
    bottom: -100px;
    right: -100px;
    animation-delay: -10s;
  }

  .dark .glow-1,
  .dark .glow-2 {
    background-color: var(--login-glow-dark);
  }

  @keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(30px, -30px) scale(1.1); }
  }

  /* Tarjeta de login */
  .login-card {
    position: relative;
    z-index: 10;
    width: 100%;
    max-width: 450px;
    padding: 2.5rem;
    border-radius: 1rem;
    background-color: white;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(229, 231, 235, 0.5);
    animation: slideUp 0.5s ease-out;
  }

  .dark .login-card {
    background-color: #1e293b;
    border-color: rgba(51, 65, 85, 0.5);
  }

  @keyframes slideUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  /* Campos de formulario con iconos */
  .input-group {
    position: relative;
    margin-bottom: 1.5rem;
  }

  .input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
    transition: color 0.2s;
  }

  .dark .input-icon {
    color: #6b7280;
  }

  .form-input {
    width: 100%;
    padding: 1rem 1rem 1rem 3rem;
    font-size: 1rem;
    border: 1px solid #d1d5db;
    border-radius: 0.75rem;
    background-color: #f9fafb;
    color: #1f2937;
    transition: all 0.2s;
  }

  .dark .form-input {
    background-color: #334155;
    border-color: #475569;
    color: #f1f5f9;
  }

  .form-input:focus {
    outline: none;
    border-color: var(--green-light);
    background-color: white;
    box-shadow: 0 0 0 3px rgba(138, 164, 70, 0.1);
  }

  .dark .form-input:focus {
    background-color: #1e293b;
    box-shadow: 0 0 0 3px rgba(138, 164, 70, 0.2);
  }

  .form-input:focus + .input-icon {
    color: var(--green-light);
  }

  /* Botón de mostrar contraseña */
  .password-toggle {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0.25rem;
    transition: color 0.2s;
  }

  .password-toggle:hover {
    color: var(--green-light);
  }

  /* Checkbox personalizado */
  .checkbox-group {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
  }

  .checkbox-input {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.75rem;
    accent-color: var(--green-light);
    cursor: pointer;
  }

  .checkbox-label {
    font-size: 0.95rem;
    color: #4b5563;
    cursor: pointer;
  }

  .dark .checkbox-label {
    color: #d1d5db;
  }

  /* Botón de envío */
  .login-button {
    width: 100%;
    padding: 1rem;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    background-color: var(--green-dark);
    border: none;
    border-radius: 0.75rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
  }

  .login-button:hover {
    background-color: var(--green-light);
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }

  .login-button:active {
    transform: translateY(0);
  }

  .login-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }

  /* Enlaces */
  .link-forgot {
    display: block;
    text-align: right;
    margin-bottom: 1.5rem;
  }

  .link-forgot a {
    color: var(--green-dark);
    font-size: 0.9rem;
    font-weight: 500;
    text-decoration: none;
    transition: color 0.2s;
  }

  .dark .link-forgot a {
    color: var(--green-light);
  }

  .link-forgot a:hover {
    text-decoration: underline;
  }

  /* Mensaje de registro */
  .register-message {
    text-align: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
  }

  .dark .register-message {
    border-top-color: #374151;
  }

  .register-message p {
    color: #6b7280;
    font-size: 0.95rem;
  }

  .dark .register-message p {
    color: #9ca3af;
  }

  .register-message a {
    color: var(--green-dark);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
  }

  .dark .register-message a {
    color: var(--green-light);
  }

  .register-message a:hover {
    text-decoration: underline;
  }

  /* Alertas de error */
  .alert-error {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.75rem;
    background-color: rgba(239, 68, 68, 0.1);
    border-left: 4px solid #ef4444;
    color: #b91c1c;
    font-size: 0.9rem;
  }

  .dark .alert-error {
    background-color: rgba(239, 68, 68, 0.2);
    color: #f87171;
  }

  /* Indicador de carga */
  .spinner {
    width: 1.25rem;
    height: 1.25rem;
    border: 2px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: white;
    animation: spin 0.8s linear infinite;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  /* Responsividad */
  @media (max-width: 640px) {
    .login-card {
      margin: 1rem;
      padding: 2rem 1.5rem;
    }
  }
</style>
@endpush

{{-- Contenido principal --}}
@section('content')
<div class="login-container">
  <!-- Efectos de brillo en el fondo -->
  <div class="login-glow glow-1"></div>
  <div class="login-glow glow-2"></div>

  <!-- Tarjeta de login -->
  <div class="login-card">
    <!-- Logo y título -->
    <div class="text-center mb-8">
      <a href="{{ url('/') }}" class="inline-block mb-6 transition-transform hover:scale-105">
        <img src="{{ asset('images/logo/logo.png') }}" alt="ECCA_LOGO" class="h-20 w-auto mx-auto">
      </a>
      <h1 class="text-3xl font-bold text-brand-dark dark:text-brand-light mb-2">
        Iniciar Sesión
      </h1>
      <p class="text-gray-600 dark:text-gray-400">
        Bienvenido de nuevo
      </p>
    </div>

    <!-- Formulario de Login -->
    <form method="POST" action="{{ route('login') }}" id="loginForm">
      @csrf

      <!-- Alerta de errores -->
      @if ($errors->any())
        <div class="alert-error">
          <div class="flex items-start">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div>
              <p class="font-medium">Error de inicio de sesión</p>
              <p class="mt-1">{{ $errors->first('email') ?: $errors->first('password') ?: 'Credenciales incorrectas' }}</p>
            </div>
          </div>
        </div>
      @endif

      <!-- Campo: Email -->
      <div class="input-group">
        <input 
          id="email" 
          name="email" 
          type="email" 
          value="{{ old('email') }}" 
          required 
          autofocus
          autocomplete="email"
          class="form-input"
          placeholder="Correo electrónico">
        <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
      </div>

      <!-- Campo: Contraseña -->
      <div class="input-group">
        <input 
          id="password" 
          name="password" 
          type="password" 
          required
          autocomplete="current-password"
          class="form-input"
          placeholder="Contraseña">
        <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
        <button type="button" id="togglePassword" class="password-toggle">
          <svg id="eyeOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
          <svg id="eyeClosed" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
          </svg>
        </button>
      </div>

      <!-- Opciones adicionales -->
      <div class="flex items-center justify-between mb-6">
        <div class="checkbox-group">
          <input 
            id="remember" 
            name="remember" 
            type="checkbox"
            class="checkbox-input">
          <label for="remember" class="checkbox-label">Recordarme</label>
        </div>

        <div class="link-forgot">
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
          @endif
        </div>
      </div>

      <!-- Botón de envío -->
      <button 
        type="submit" 
        id="loginButton"
        class="login-button">
        <span id="buttonText">Iniciar Sesión</span>
        <div id="buttonSpinner" class="spinner hidden"></div>
      </button>
    </form>

    <!-- Enlace a Registro -->
    @if (Route::has('register'))
      <div class="register-message">
        <p>¿No tienes una cuenta? <a href="{{ route('register') }}">Regístrate aquí</a></p>
      </div>
    @endif
  </div>
</div>
@endsection

{{-- Scripts específicos para esta página --}}
@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad para mostrar/ocultar contraseña
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeClosed = document.getElementById('eyeClosed');

    if (togglePassword) {
      togglePassword.addEventListener('click', function() {
        // Cambiar el tipo de input
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        
        // Cambiar el icono
        eyeOpen.classList.toggle('hidden');
        eyeClosed.classList.toggle('hidden');
      });
    }

    // Indicador de carga al enviar el formulario
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const buttonText = document.getElementById('buttonText');
    const buttonSpinner = document.getElementById('buttonSpinner');

    if (loginForm) {
      loginForm.addEventListener('submit', function() {
        // Deshabilitar el botón y mostrar el spinner
        loginButton.disabled = true;
        buttonText.textContent = 'Iniciando sesión...';
        buttonSpinner.classList.remove('hidden');
      });
    }
  });
</script>
@endpush