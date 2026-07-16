@extends('layouts.panel')

@section('title', 'Usuarios')
@section('pageheading', 'Usuarios')

{{-- Botón crear (abre el modal base del layout) --}}
@section('addbutton')
  <i class="fa-solid fa-plus-circle"></i> Nuevo usuario
@endsection

{{-- Modal de creación --}}
@section('modalTitle','Crear nuevo usuario')
@section('formaction', url('/adduser'))
@section('modalFields')
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="name" class="block text-sm font-medium mb-1">Nombre completo</label>
      <input id="name" name="name" type="text" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" placeholder="Juan Pérez" required>
    </div>
    <div>
      <label for="email" class="block text-sm font-medium mb-1">Correo electrónico</label>
      <input id="email" name="email" type="email" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" placeholder="correo@ejemplo.com" required>
    </div>
    <div>
      <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
      <input id="password" name="password" type="password" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmar contraseña</label>
      <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="role_id" class="block text-sm font-medium mb-1">Rol</label>
      <select id="role_id" name="role_id" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
        <option value="">Seleccionar rol</option>
        @if(isset($roles))
          @foreach($roles as $role)
            <option value="{{ $role->id }}">{{ $role->name }}</option>
          @endforeach
        @endif
      </select>
    </div>
    <div>
      <label for="status" class="block text-sm font-medium mb-1">Estado</label>
      <select id="status" name="status" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
      </select>
    </div>
  </div>
@endsection

@section('datatable')
  {{-- Tarjeta de información --}}
  <div class="card mb-6">
    <div class="card-body p-4">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center">
          <i class="fas fa-users text-brand-green"></i>
        </div>
        <div>
          <h3 class="font-semibold text-slate-900 dark:text-white">Usuarios del Sistema</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
            Administra las cuentas de usuario del sistema. Usa el botón "Nuevo usuario" para crear nuevas cuentas o las acciones por fila para ver, editar o eliminar usuarios existentes.
          </p>
        </div>
      </div>
    </div>
  </div>

  {{-- Buscador --}}
  <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row gap-2 mb-4">
    <div class="relative flex-1">
      <input 
        type="text" 
        name="search" 
        value="{{ request('search') }}" 
        placeholder="Buscar usuarios por nombre o correo electrónico..." 
        class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-10 py-2 text-sm text-slate-900 dark:text-white"
      >
      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <i class="fa-solid fa-magnifying-glass text-slate-400"></i>
      </div>
    </div>
    <button type="submit" class="btn btn-secondary">Buscar</button>
    @if(request('search'))
      <a href="{{ url()->current() }}" class="btn btn-ghost">Limpiar</a>
    @endif
  </form>

  {{-- Tarjeta de la tabla --}}
  <div class="card">
    <div class="card-body p-0">
      <div class="table-wrap">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Avatar</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Nombre completo</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Correo electrónico</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Rol</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($users as $user)
              @php
                // Initials for avatar
                $initials = '';
                if (!empty($user->name)) {
                    $nameParts = explode(' ', $user->name);
                    foreach ($nameParts as $part) {
                        if (!empty($part)) {
                            $initials .= strtoupper(substr($part, 0, 1));
                            if (strlen($initials) >= 2) break;
                        }
                    }
                }
                if (empty($initials)) {
                    $initials = 'U';
                }

                // Roles
                $role = $user->roles->first();
              @endphp
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-sm">
                  @if (empty($user->image))
                    <div class="w-10 h-10 rounded-full bg-slate-200 dark:bg-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-300 font-semibold">
                      {{ $initials }}
                    </div>
                  @else
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover ring-1 ring-slate-200 dark:ring-slate-800">
                  @endif
                </td>
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">{{ $user->name }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $user->email }}</td>
                <td class="px-4 py-3 text-sm">
                  @if ($role)
                    <span class="chip-brand bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-300 dark:border-blue-700">
                      {{ $role->name }}
                    </span>
                  @else
                    <span class="text-slate-500 dark:text-slate-400">Sin rol</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($user->deleted_at)
                    <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-ban mr-1"></i> Inactivo</span>
                  @else
                    <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm text-center">
                  @include('admin.partials.actions', [
                      'id'           => $user->id,
                      'view'         => url("view-user/{$user->id}"),
                      'activate'     => url("activate-user/{$user->id}"),
                      'softdelete'   => url("delete-user/{$user->id}"),
                      'realdelete'   => url("realdelete-user/{$user->id}"),
                      'formAction'   => url("update-user/{$user->id}"),
                      'tableM'       => $user,
                      'sectionType'  => 'user',
                      'sectionTitle' => 'Usuario',
                  ])
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                  <i class="fas fa-users text-5xl mb-4 opacity-50 block"></i>
                  <p class="font-medium">No se encontraron usuarios</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($users->hasPages())
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      {{ $users->links() }}
    </div>
  @endif
@endsection