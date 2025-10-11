@extends('layouts.panel')

@section('title', 'Perfil de usuario')
@section('pageheading', 'Mi perfil')

@section('datatable')
  {{-- Tarjeta principal del perfil --}}
  <div class="card">
    <div class="card-body p-0">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
        {{-- Sección de perfil (izquierda) --}}
        <div class="lg:col-span-1 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 p-6 border-r border-slate-200 dark:border-slate-700">
          <div class="flex flex-col items-center">
            {{-- Avatar del usuario --}}
            <div class="relative mb-4">
              @if($user->image)
                <img src="{{ asset('images/logo/logo.png') }}" 
                     alt="{{ $user->name }}" 
                     class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-slate-800 shadow-lg">
              @else
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-4xl font-bold shadow-lg">
                  {{ substr($user->name, 0, 1) }}
                </div>
              @endif
              
              {{-- Indicador de estado --}}
              <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white dark:border-slate-800"></div>
            </div>
            
            {{-- Información básica --}}
            <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $user->name }}</h2>
            <p class="text-slate-600 dark:text-slate-400 mb-4">{{ $user->email }}</p>
            
            {{-- Rol del usuario --}}
            <div class="chip-brand bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-300 dark:border-blue-700 mb-6">
              <i class="fas fa-user-tag mr-1"></i> {{ $user->roles->first()?->name ?? 'Sin rol' }}
            </div>
            
            {{-- Estadísticas --}}
            <div class="w-full space-y-3">
              <div class="flex justify-between items-center">
                <span class="text-sm text-slate-600 dark:text-slate-400">Miembro desde</span>
                <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->created_at->format('d/m/Y') }}</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-sm text-slate-600 dark:text-slate-400">Última actualización</span>
                <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->updated_at->format('d/m/Y') }}</span>
              </div>
            </div>
          </div>
        </div>
        
        {{-- Sección de formulario (derecha) --}}
        <div class="lg:col-span-2 p-6">
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Configuración del perfil</h3>
            <p class="text-sm text-slate-600 dark:text-slate-400">Actualiza tu información personal y preferencias de seguridad</p>
          </div>
          
          {{-- Mensajes de error --}}
          @if ($errors->any())
            <div class="alert alert-danger mb-6">
              <div class="flex items-start">
                <i class="fas fa-exclamation-triangle mt-0.5 mr-2"></i>
                <div>
                  <p class="font-medium">Por favor corrige los siguientes errores:</p>
                  <ul class="list-disc list-inside mt-1">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>
          @endif
          
          {{-- Mensaje de éxito --}}
          @if (session('profile_updated'))
            <div class="alert alert-success mb-6">
              <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ session('profile_updated') }}</span>
              </div>
            </div>
          @endif
          
          {{-- Formulario de edición --}}
          <form action="{{ $user->id }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            
            {{-- Sección: Información personal --}}
            <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
              <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-lg bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center mr-3">
                  <i class="fas fa-user text-blue-600"></i>
                </div>
                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Información personal</h4>
              </div>
              
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label for="name" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Nombre completo</label>
                  <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" 
                         class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                         required>
                </div>
                
                <div>
                  <label for="phone" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Teléfono</label>
                  <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" 
                         class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                         placeholder="Ingrese un número de teléfono válido">
                </div>
                
                <div>
                  <label for="email" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Correo electrónico</label>
                  <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" 
                         class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                         placeholder="Ingrese una dirección de email válida" required>
                </div>
                
                <div>
                  <label for="birthdate" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Fecha de nacimiento</label>
                  <input id="birthdate" name="birthdate" type="date" value="{{ old('birthdate', $user->birthdate) }}" 
                         class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
              </div>
            </div>
            
            {{-- Sección: Foto de perfil --}}
            <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
              <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-lg bg-purple-100 dark:bg-purple-900/20 flex items-center justify-center mr-3">
                  <i class="fas fa-camera text-purple-600"></i>
                </div>
                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Foto de perfil</h4>
              </div>
              
              <div class="space-y-4">
                <div class="flex items-center space-x-6">
                  {{-- Vista previa de la imagen actual --}}
                  <div class="shrink-0">
                    @if($user->image)
                      <img id="currentImagePreview" src="{{ asset('images/logo/logo.png')}}" 
                           alt="Foto de perfil actual" 
                           class="h-20 w-20 object-cover rounded-full border-2 border-slate-200 dark:border-slate-700">
                    @else
                      <div id="currentImagePreview" class="h-20 w-20 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ substr($user->name, 0, 1) }}
                      </div>
                    @endif
                  </div>
                  
                  <div class="flex-1">
                    <div class="relative">
                      <input id="image" name="image" type="file" accept="image/*" 
                             class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-50 dark:file:bg-slate-800 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-100 dark:hover:file:bg-slate-700 cursor-pointer">
                      <label class="absolute inset-0 w-full h-full cursor-pointer" for="image"></label>
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                      Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 2MB.
                    </p>
                  </div>
                </div>
              </div>
            </div>
            
            {{-- Sección: Seguridad --}}
            <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
              <div class="flex items-center mb-4">
                <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/20 flex items-center justify-center mr-3">
                  <i class="fas fa-lock text-amber-600"></i>
                </div>
                <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Seguridad</h4>
              </div>
              
              <div class="space-y-4">
                <p class="text-sm text-slate-600 dark:text-slate-400">
                  Deja los campos en blanco si no deseas cambiar la contraseña actual.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label for="password" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Nueva contraseña</label>
                    <div class="relative">
                      <input id="password" name="password" type="password" 
                             class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 pr-10 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                             placeholder="*********************">
                      <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i id="passwordIcon" class="fas fa-eye-slash text-slate-400"></i>
                      </button>
                    </div>
                  </div>
                  
                  <div>
                    <label for="password_confirmation" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Confirmar contraseña</label>
                    <div class="relative">
                      <input id="password_confirmation" name="password_confirmation" type="password" 
                             class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 pr-10 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                             placeholder="*********************">
                      <button type="button" id="togglePasswordConfirmation" class="absolute inset-y-0 right-0 pr-3 flex items-center">
                        <i id="passwordConfirmationIcon" class="fas fa-eye-slash text-slate-400"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            {{-- Botones de acción --}}
            <div class="flex justify-end space-x-3">
              <a href="{{ url('dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i> Cancelar
              </a>
              <button type="submit" class="btn btn-primary">
                <i class="fas fa-save mr-2"></i> Guardar cambios
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
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
  
  // Toggle para mostrar/ocultar confirmación de contraseña
  const togglePasswordConfirmation = document.getElementById('togglePasswordConfirmation');
  const passwordConfirmationInput = document.getElementById('password_confirmation');
  const passwordConfirmationIcon = document.getElementById('passwordConfirmationIcon');
  
  if (togglePasswordConfirmation) {
    togglePasswordConfirmation.addEventListener('click', function() {
      const type = passwordConfirmationInput.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordConfirmationInput.setAttribute('type', type);
      
      // Cambiar el icono
      if (type === 'text') {
        passwordConfirmationIcon.classList.remove('fa-eye-slash');
        passwordConfirmationIcon.classList.add('fa-eye');
      } else {
        passwordConfirmationIcon.classList.remove('fa-eye');
        passwordConfirmationIcon.classList.add('fa-eye-slash');
      }
    });
  }
  
  // Vista previa de imagen
  const imageInput = document.getElementById('image');
  const currentImagePreview = document.getElementById('currentImagePreview');
  
  if (imageInput) {
    imageInput.addEventListener('change', function(e) {
      const file = e.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          currentImagePreview.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="h-20 w-20 object-cover rounded-full border-2 border-slate-200 dark:border-slate-700">`;
        };
        reader.readAsDataURL(file);
      }
    });
  }
});
</script>
@endpush