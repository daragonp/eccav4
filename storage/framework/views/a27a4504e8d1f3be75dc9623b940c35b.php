<?php $__env->startSection('title', 'Registrarse'); ?>


<?php $__env->startPush('head'); ?>
<style>
  /* Variables específicas para esta página */
  :root {
    --register-glow: rgba(138, 164, 70, 0.15);
    --register-glow-dark: rgba(138, 164, 70, 0.25);
  }

  /* Contenedor principal con fondo degradado */
  .register-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    position: relative;
    overflow: hidden;
  }

  .dark .register-container {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  }

  /* Efecto de brillo sutil en el fondo */
  .register-glow {
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    filter: blur(100px);
    opacity: 0.5;
    animation: float 20s ease-in-out infinite;
  }

  .glow-1 {
    background-color: var(--register-glow);
    top: -100px;
    left: -100px;
  }

  .glow-2 {
    background-color: var(--register-glow);
    bottom: -100px;
    right: -100px;
    animation-delay: -10s;
  }

  .dark .glow-1,
  .dark .glow-2 {
    background-color: var(--register-glow-dark);
  }

  @keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(30px, -30px) scale(1.1); }
  }

  /* Tarjeta de registro */
  .register-card {
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

  .dark .register-card {
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
    z-index: 1;
  }

  .dark .input-icon {
    color: #6b7280;
  }

  .form-input {
    width: 100%;
    padding: 1rem 3rem 1rem 3rem;
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

  .form-input:focus ~ .input-icon {
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
    z-index: 2;
  }

  .password-toggle:hover {
    color: var(--green-light);
  }

  /* Indicador de fortaleza de contraseña */
  .password-strength {
    height: 4px;
    border-radius: 2px;
    margin-top: 0.5rem;
    background-color: #e5e7eb;
    overflow: hidden;
  }

  .dark .password-strength {
    background-color: #374151;
  }

  .password-strength-bar {
    height: 100%;
    width: 0;
    transition: width 0.3s, background-color 0.3s;
  }

  .strength-weak {
    background-color: #ef4444;
    width: 33%;
  }

  .strength-medium {
    background-color: #f59e0b;
    width: 66%;
  }

  .strength-strong {
    background-color: #10b981;
    width: 100%;
  }

  .password-strength-text {
    font-size: 0.75rem;
    margin-top: 0.25rem;
    color: #6b7280;
  }

  .dark .password-strength-text {
    color: #9ca3af;
  }

  /* Requisitos de contraseña */
  .password-requirements {
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: #6b7280;
  }

  .dark .password-requirements {
    color: #9ca3af;
  }

  .requirement {
    display: flex;
    align-items: center;
    margin-bottom: 0.25rem;
  }

  .requirement-icon {
    margin-right: 0.5rem;
    color: #9ca3af;
    transition: color 0.2s;
  }

  .requirement.met .requirement-icon {
    color: #10b981;
  }

  /* Botón de envío */
  .register-button {
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

  .register-button:hover {
    background-color: var(--green-light);
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }

  .register-button:active {
    transform: translateY(0);
  }

  .register-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }

  /* Mensaje de login */
  .login-message {
    text-align: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
  }

  .dark .login-message {
    border-top-color: #374151;
  }

  .login-message p {
    color: #6b7280;
    font-size: 0.95rem;
  }

  .dark .login-message p {
    color: #9ca3af;
  }

  .login-message a {
    color: var(--green-dark);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
  }

  .dark .login-message a {
    color: var(--green-light);
  }

  .login-message a:hover {
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
    .register-card {
      margin: 1rem;
      padding: 2rem 1.5rem;
    }
  }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="register-container">
  <!-- Efectos de brillo en el fondo -->
  <div class="register-glow glow-1"></div>
  <div class="register-glow glow-2"></div>

  <!-- Tarjeta de registro -->
  <div class="register-card">
    <!-- Logo y título -->
    <div class="text-center mb-8">
      <a href="<?php echo e(url('/')); ?>" class="inline-block mb-6 transition-transform hover:scale-105">
        <img src="<?php echo e(asset('images/logo/logo.png')); ?>" alt="ECCA_LOGO" class="h-20 w-auto mx-auto">
      </a>
      <h1 class="text-3xl font-bold text-brand-dark dark:text-brand-light mb-2">
        Crear una Cuenta
      </h1>
      <p class="text-gray-600 dark:text-gray-400">
        Únete a nuestra comunidad
      </p>
    </div>

    <!-- Formulario de Registro -->
    <form method="POST" action="<?php echo e(route('register')); ?>" id="registerForm">
      <?php echo csrf_field(); ?>

      <!-- Alerta de errores -->
      <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
        <div class="alert-error">
          <div class="flex items-start">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div>
              <p class="font-medium">Error de registro</p>
              <p class="mt-1"><?php echo e($errors->first('name') ?: $errors->first('email') ?: $errors->first('password') ?: 'Por favor, corrige los errores del formulario'); ?></p>
            </div>
          </div>
        </div>
      <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

      <!-- Campo: Nombre -->
      <div class="input-group">
        <input 
          id="name" 
          name="name" 
          type="text" 
          value="<?php echo e(old('name')); ?>" 
          required 
          autofocus
          autocomplete="name"
          class="form-input"
          placeholder="Nombre completo">
        <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
        </svg>
      </div>

      <!-- Campo: Email -->
      <div class="input-group">
        <input 
          id="email" 
          name="email" 
          type="email" 
          value="<?php echo e(old('email')); ?>" 
          required 
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
          autocomplete="new-password"
          class="form-input"
          placeholder="Contraseña">
        <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
        <button type="button" class="password-toggle" data-target="password">
          <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
          <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
          </svg>
        </button>
      </div>

      <!-- Indicador de fortaleza de contraseña -->
      <div class="password-strength">
        <div id="passwordStrengthBar" class="password-strength-bar"></div>
      </div>
      <p id="passwordStrengthText" class="password-strength-text">Fortaleza de la contraseña</p>

      <!-- Requisitos de contraseña -->
      <div class="password-requirements">
        <div id="length" class="requirement">
          <svg class="requirement-icon w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span>Al menos 8 caracteres</span>
        </div>
        <div id="uppercase" class="requirement">
          <svg class="requirement-icon w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span>Una letra mayúscula</span>
        </div>
        <div id="lowercase" class="requirement">
          <svg class="requirement-icon w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span>Una letra minúscula</span>
        </div>
        <div id="number" class="requirement">
          <svg class="requirement-icon w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span>Un número</span>
        </div>
      </div>

      <!-- Campo: Confirmar Contraseña -->
      <div class="input-group mt-4">
        <input 
          id="password_confirmation" 
          name="password_confirmation" 
          type="password" 
          required
          autocomplete="new-password"
          class="form-input"
          placeholder="Confirmar contraseña">
        <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
        </svg>
        <button type="button" class="password-toggle" data-target="password_confirmation">
          <svg class="eye-open w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
          </svg>
          <svg class="eye-closed w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
          </svg>
        </button>
      </div>

      <!-- Mensaje de coincidencia de contraseñas -->
      <p id="passwordMatch" class="password-strength-text mt-2">Las contraseñas coinciden</p>

      <!-- Botón de envío -->
      <button 
        type="submit" 
        id="registerButton"
        class="register-button mt-6">
        <span id="buttonText">Registrarse</span>
        <div id="buttonSpinner" class="spinner hidden"></div>
      </button>
    </form>

    <!-- Enlace a Login -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('login')): ?>
      <div class="login-message">
        <p>¿Ya tienes una cuenta? <a href="<?php echo e(route('login')); ?>">Inicia sesión</a></p>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
  </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad para mostrar/ocultar contraseña
    const passwordToggles = document.querySelectorAll('.password-toggle');
    
    passwordToggles.forEach(toggle => {
      toggle.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const targetInput = document.getElementById(targetId);
        const eyeOpen = this.querySelector('.eye-open');
        const eyeClosed = this.querySelector('.eye-closed');
        
        if (targetInput) {
          const type = targetInput.getAttribute('type') === 'password' ? 'text' : 'password';
          targetInput.setAttribute('type', type);
          
          // Cambiar el icono
          eyeOpen.classList.toggle('hidden');
          eyeClosed.classList.toggle('hidden');
        }
      });
    });

    // Indicador de carga al enviar el formulario
    const registerForm = document.getElementById('registerForm');
    const registerButton = document.getElementById('registerButton');
    const buttonText = document.getElementById('buttonText');
    const buttonSpinner = document.getElementById('buttonSpinner');

    if (registerForm) {
      registerForm.addEventListener('submit', function() {
        registerButton.disabled = true;
        buttonText.textContent = 'Registrando...';
        buttonSpinner.classList.remove('hidden');
      });
    }

    // Validación de fortaleza de contraseña
    const passwordInput = document.getElementById('password');
    const passwordStrengthBar = document.getElementById('passwordStrengthBar');
    const passwordStrengthText = document.getElementById('passwordStrengthText');
    const passwordMatch = document.getElementById('passwordMatch');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    
    // Requisitos de contraseña
    const lengthReq = document.getElementById('length');
    const uppercaseReq = document.getElementById('uppercase');
    const lowercaseReq = document.getElementById('lowercase');
    const numberReq = document.getElementById('number');

    if (passwordInput) {
      passwordInput.addEventListener('input', function() {
        const password = this.value;
        
        // Resetear clases
        passwordStrengthBar.className = 'password-strength-bar';
        
        // Verificar requisitos
        const hasLength = password.length >= 8;
        const hasUppercase = /[A-Z]/.test(password);
        const hasLowercase = /[a-z]/.test(password);
        const hasNumber = /[0-9]/.test(password);
        
        // Actualizar indicadores visuales
        lengthReq.classList.toggle('met', hasLength);
        uppercaseReq.classList.toggle('met', hasUppercase);
        lowercaseReq.classList.toggle('met', hasLowercase);
        numberReq.classList.toggle('met', hasNumber);
        
        // Calcular fortaleza
        let strength = 0;
        if (hasLength) strength++;
        if (hasUppercase) strength++;
        if (hasLowercase) strength++;
        if (hasNumber) strength++;
        
        // Actualizar barra de fortaleza
        if (password.length > 0) {
          if (strength <= 2) {
            passwordStrengthBar.classList.add('strength-weak');
            passwordStrengthText.textContent = 'Contraseña débil';
          } else if (strength === 3) {
            passwordStrengthBar.classList.add('strength-medium');
            passwordStrengthText.textContent = 'Contraseña media';
          } else {
            passwordStrengthBar.classList.add('strength-strong');
            passwordStrengthText.textContent = 'Contraseña fuerte';
          }
        } else {
          passwordStrengthText.textContent = 'Fortaleza de la contraseña';
        }
        
        // Verificar coincidencia con confirmación
        checkPasswordMatch();
      });
    }

    // Verificar coincidencia de contraseñas
    function checkPasswordMatch() {
      if (passwordInput.value && confirmPasswordInput.value) {
        if (passwordInput.value === confirmPasswordInput.value) {
          passwordMatch.textContent = 'Las contraseñas coinciden';
          passwordMatch.style.color = '#10b981';
        } else {
          passwordMatch.textContent = 'Las contraseñas no coinciden';
          passwordMatch.style.color = '#ef4444';
        }
      } else {
        passwordMatch.textContent = 'Las contraseñas coinciden';
        passwordMatch.style.color = '';
      }
    }

    if (confirmPasswordInput) {
      confirmPasswordInput.addEventListener('input', checkPasswordMatch);
    }

    // Validación de correo electrónico en tiempo real
    const emailInput = document.getElementById('email');
    
    if (emailInput) {
      emailInput.addEventListener('blur', function() {
        const email = this.value.trim();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
          this.style.borderColor = '#ef4444';
        } else {
          this.style.borderColor = '';
        }
      });
    }
  });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Library/Proyectos/eccav4/resources/views/auth/register.blade.php ENDPATH**/ ?>