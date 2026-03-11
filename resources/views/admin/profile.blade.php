@extends('layouts.panel')

@section('title', 'Perfil de usuario')
@section('pageheading', 'Mi perfil')

@section('datatable')
<div class="card">
  <div class="card-body p-0">
    <div class="grid grid-cols-1 lg:grid-cols-3">
      <div class="lg:col-span-1 bg-gradient-to-br from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-900 p-6 border-r border-slate-200 dark:border-slate-700">
        <div class="flex flex-col items-center">
          @php
            $avatar = $user->avatar_url;
          @endphp
          <div class="relative mb-4">
            <img src="{{ $avatar }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border-4 border-white dark:border-slate-800 shadow-lg">
            <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 rounded-full border-2 border-white dark:border-slate-800"></div>
          </div>

          <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-1">{{ $user->name }}</h2>
          <p class="text-slate-600 dark:text-slate-400 mb-4">{{ $user->email }}</p>

          <div class="chip-brand bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-300 dark:border-blue-700 mb-6">
            <i class="fas fa-user-tag mr-1"></i> {{ $user->roles->first()?->name ?? 'Sin rol' }}
          </div>

          <div class="w-full space-y-3">
            <div class="flex justify-between items-center">
              <span class="text-sm text-slate-600 dark:text-slate-400">Miembro desde</span>
              <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->created_at?->format('d/m/Y') }}</span>
            </div>
            <div class="flex justify-between items-center">
              <span class="text-sm text-slate-600 dark:text-slate-400">Última actualización</span>
              <span class="text-sm font-medium text-slate-900 dark:text-white">{{ $user->updated_at?->format('d/m/Y') }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="lg:col-span-2 p-6">
        <div class="mb-6">
          <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2">Configuración del perfil</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400">Actualiza tu información personal, foto y contraseña.</p>
        </div>

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

        @if (session('profile_updated'))
          <div class="alert alert-success mb-6">
            <div class="flex items-center">
              <i class="fas fa-check-circle mr-2"></i>
              <span>{{ session('profile_updated') }}</span>
            </div>
          </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
          @csrf
          @method('PUT')

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
                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
              </div>

              <div>
                <label for="phone" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Teléfono</label>
                <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              </div>

              <div>
                <label for="email" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Correo electrónico</label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
              </div>

              <div>
                <label for="birthdate" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Fecha de nacimiento</label>
                <input id="birthdate" name="birthdate" type="date" value="{{ old('birthdate', optional($user->birthdate)->format('Y-m-d') ?? $user->birthdate) }}" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              </div>
            </div>
          </div>

          <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 rounded-lg bg-cyan-100 dark:bg-cyan-900/20 flex items-center justify-center mr-3">
                <i class="fas fa-camera text-cyan-600"></i>
              </div>
              <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Foto de perfil</h4>
            </div>

            <div class="flex items-center gap-6">
              <img id="profileImagePreview" src="{{ $avatar }}" alt="Foto de perfil" class="h-20 w-20 rounded-full object-cover border-2 border-slate-200 dark:border-slate-700">
              <div class="flex-1">
                <input id="image" name="image" type="file" accept="image/*" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100">
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Formatos permitidos: JPG, PNG, GIF, WEBP. Tamaño máximo: 2MB.</p>
              </div>
            </div>
          </div>

          <div class="border border-slate-200 dark:border-slate-700 rounded-xl p-6">
            <div class="flex items-center mb-4">
              <div class="w-10 h-10 rounded-lg bg-amber-100 dark:bg-amber-900/20 flex items-center justify-center mr-3">
                <i class="fas fa-lock text-amber-600"></i>
              </div>
              <h4 class="text-lg font-semibold text-slate-900 dark:text-white">Seguridad</h4>
            </div>

            <p class="text-sm text-slate-600 dark:text-slate-400 mb-4">Deja estos campos vacíos si no deseas cambiar la contraseña.</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="password" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Nueva contraseña</label>
                <input id="password" name="password" type="password" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100">
              </div>

              <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-2 text-slate-700 dark:text-slate-300">Confirmar contraseña</label>
                <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-4 py-2.5 text-slate-900 dark:text-slate-100">
              </div>
            </div>
          </div>

          <div class="flex justify-end gap-3">
            <a href="{{ url('dashboard') }}" class="btn btn-secondary">Cancelar</a>
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
(function () {
  const bind = () => {
    const input = document.getElementById('image');
    const preview = document.getElementById('profileImagePreview');
    if (!input || !preview || input.dataset.bound === '1') return;

    input.dataset.bound = '1';
    input.addEventListener('change', function (event) {
      const file = event.target.files && event.target.files[0];
      if (!file) return;

      const reader = new FileReader();
      reader.onload = function (e) {
        preview.src = e.target.result;
      };
      reader.readAsDataURL(file);
    });
  };

  document.addEventListener('DOMContentLoaded', bind);
  document.addEventListener('turbo:load', bind);
})();
</script>
@endpush
