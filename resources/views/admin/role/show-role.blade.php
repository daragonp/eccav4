@extends('layouts.panel')

@section('title', 'Roles de usuarios')
@section('pageheading', 'Roles')

{{-- Botón crear (abre el modal base del layout) --}}
@section('addbutton')
  <i class="fa-solid fa-plus-circle"></i> Nuevo rol
@endsection

{{-- Modal de creación --}}
@section('modalTitle','Crear nuevo rol')
@section('formaction', url('/addrole'))
@section('modalFields')
  <div class="grid grid-cols-1 gap-4">
    <div>
      <label for="name" class="block text-sm font-medium mb-1">Nombre del rol</label>
      <input id="name" name="name" type="text" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" placeholder="Por ejemplo, Administrador" required>
    </div>
  </div>
@endsection

@section('datatable')
  {{-- Tarjeta de información --}}
  <div class="card mb-6">
    <div class="card-body p-4">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center">
          <i class="fas fa-user-shield text-brand-green"></i>
        </div>
        <div>
          <h3 class="font-semibold text-slate-900 dark:text-white">Roles de Usuario</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
            Administra los roles del sistema y sus permisos asociados. Usa el botón "Nuevo rol" para crear nuevos perfiles de acceso o las acciones por fila para editar o eliminar roles existentes.
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
        placeholder="Buscar roles por nombre..." 
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
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">ID</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Nombre del rol</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Usuarios asignados</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($roles as $role)
              @php
                $isActive = true;
                if (isset($role->active)) {
                    $isActive = (bool)$role->active;
                } elseif (isset($role->deleted_at)) {
                    $isActive = is_null($role->deleted_at);
                }
              @endphp
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $role->id }}</td>
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">{{ $role->name }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                  {{ $role->users_count ?? 0 }}
                </td>
                <td class="px-4 py-3 text-sm">
                  @if ($isActive)
                    <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fas fa-check-circle mr-1"></i> Activo</span>
                  @else
                    <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fas fa-ban mr-1"></i> Inactivo</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm text-center">
                  @include('admin.partials.actions', [
                      'id'           => $role->id,
                      'view'         => url("view-role/{$role->id}"),
                      'activate'     => url("activate-role/{$role->id}"),
                      'softdelete'   => url("delete-role/{$role->id}"),
                      'realdelete'   => url("realdelete-role/{$role->id}"),
                      'formAction'   => url("update-role/{$role->id}"),
                      'tableM'       => $role,
                      'sectionType'  => 'role',
                      'sectionTitle' => 'Rol',
                  ])
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                  <i class="fas fa-user-shield text-5xl mb-4 opacity-50 block"></i>
                  <p class="font-medium">No se encontraron roles</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($roles->hasPages())
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      {{ $roles->links() }}
    </div>
  @endif
@endsection