<?php $__env->startSection('title', 'Restablecer Contraseña'); ?>


<?php $__env->startPush('head'); ?>
<style>
  /* Variables específicas para esta página */
  :root {
    --forgot-glow: rgba(138, 164, 70, 0.15);
    --forgot-glow-dark: rgba(138, 164, 70, 0.25);
  }

  /* Contenedor principal con fondo degradado */
  .forgot-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    position: relative;
    overflow: hidden;
  }

  .dark .forgot-container {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  }

  /* Efecto de brillo sutil en el fondo */
  .forgot-glow {
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    filter: blur(100px);
    opacity: 0.5;
    animation: float 20s ease-in-out infinite;
  }

  .glow-1 {
    background-color: var(--forgot-glow);
    top: -100px;
    left: -100px;
  }

  .glow-2 {
    background-color: var(--forgot-glow);
    bottom: -100px;
    right: -100px;
    animation-delay: -10s;
  }

  .dark .glow-1,
  .dark .glow-2 {
    background-color: var(--forgot-glow-dark);
  }

  @keyframes float {
    0%, 100% { transform: translate(0, 0) scale(1); }
    50% { transform: translate(30px, -30px) scale(1.1); }
  }

  /* Tarjeta de forgot password */
  .forgot-card {
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

  .dark .forgot-card {
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

  /* Botón de envío */
  .forgot-button {
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

  .forgot-button:hover {
    background-color: var(--green-light);
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  }

  .forgot-button:active {
    transform: translateY(0);
  }

  .forgot-button:disabled {
    opacity: 0.7;
    cursor: not-allowed;
  }

  /* Enlaces */
  .back-to-login {
    text-align: center;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
  }

  .dark .back-to-login {
    border-top-color: #374151;
  }

  .back-to-login p {
    color: #6b7280;
    font-size: 0.95rem;
  }

  .dark .back-to-login p {
    color: #9ca3af;
  }

  .back-to-login a {
    color: var(--green-dark);
    font-weight: 600;
    text-decoration: none;
    transition: color 0.2s;
  }

  .dark .back-to-login a {
    color: var(--green-light);
  }

  .back-to-login a:hover {
    text-decoration: underline;
  }

  /* Alertas de éxito */
  .alert-success {
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 0.75rem;
    background-color: rgba(16, 185, 129, 0.1);
    border-left: 4px solid #10b981;
    color: #065f46;
    font-size: 0.9rem;
  }

  .dark .alert-success {
    background-color: rgba(16, 185, 129, 0.2);
    color: #34d399;
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
    .forgot-card {
      margin: 1rem;
      padding: 2rem 1.5rem;
    }
  }
</style>
<?php $__env->stopPush(); ?>


<?php $__env->startSection('content'); ?>
<div class="forgot-container">
  <!-- Efectos de brillo en el fondo -->
  <div class="forgot-glow glow-1"></div>
  <div class="forgot-glow glow-2"></div>

  <!-- Tarjeta de forgot password -->
  <div class="forgot-card">
    <!-- Logo y título -->
    <div class="text-center mb-8">
      <a href="<?php echo e(url('/')); ?>" class="inline-block mb-6 transition-transform hover:scale-105">
        <img src="<?php echo e(asset('images/logo/logo.png')); ?>" alt="ECCA_LOGO" class="h-20 w-auto mx-auto">
      </a>
      <h1 class="text-3xl font-bold text-brand-dark dark:text-brand-light mb-2">
        ¿Olvidaste tu contraseña?
      </h1>
      <p class="text-gray-600 dark:text-gray-400">
        No hay problema. Te enviaremos un enlace para que la restablezcas.
      </p>
    </div>

    <!-- Mensaje de estado de sesión -->
    <?php if(session('status')): ?>
      <div class="alert-success">
        <div class="flex items-start">
          <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <div>
            <p class="font-medium">Enlace enviado</p>
            <p class="mt-1"><?php echo e(session('status')); ?></p>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Formulario de Forgot Password -->
    <form method="POST" action="<?php echo e(route('password.email')); ?>" id="forgotForm">
      <?php echo csrf_field(); ?>

      <!-- Alerta de errores -->
      <?php if($errors->any()): ?>
        <div class="alert-error">
          <div class="flex items-start">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div>
              <p class="font-medium">Error de envío</p>
              <p class="mt-1"><?php echo e($errors->first('email') ?: 'Ocurrió un error al procesar tu solicitud.'); ?></p>
            </div>
          </div>
        </div>
      <?php endif; ?>

      <!-- Campo: Email -->
      <div class="input-group">
        <input 
          id="email" 
          name="email" 
          type="email" 
          value="<?php echo e(old('email')); ?>" 
          required 
          autofocus
          autocomplete="email"
          class="form-input"
          placeholder="Correo electrónico">
        <svg class="input-icon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
        </svg>
      </div>

      <!-- Botón de envío -->
      <button 
        type="submit" 
        id="forgotButton"
        class="forgot-button">
        <span id="buttonText">Enviar enlace de restablecimiento</span>
        <div id="buttonSpinner" class="spinner hidden"></div>
      </button>
    </form>

    <!-- Enlace a Login -->
    <div class="back-to-login">
      <p>¿Recuerdas tu contraseña? <a href="<?php echo e(route('login')); ?>">Inicia sesión</a></p>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Indicador de carga al enviar el formulario
    const forgotForm = document.getElementById('forgotForm');
    const forgotButton = document.getElementById('forgotButton');
    const buttonText = document.getElementById('buttonText');
    const buttonSpinner = document.getElementById('buttonSpinner');

    if (forgotForm) {
      forgotForm.addEventListener('submit', function() {
        // Deshabilitar el botón y mostrar el spinner
        forgotButton.disabled = true;
        buttonText.textContent = 'Enviando...';
        buttonSpinner.classList.remove('hidden');
      });
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
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/auth/forgot-password.blade.php ENDPATH**/ ?>