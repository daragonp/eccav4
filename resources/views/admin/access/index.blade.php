@extends('layouts.panel')

@section('title', 'Control de Accesos')
@section('pageheading', 'Control de Accesos')

@section('datatable')
<div class="card mb-6">
    <div class="card-body p-4">
        <div class="flex items-start gap-3">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center">
                <i class="fas fa-shield-alt text-brand-green"></i>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white">Asignación de roles y permisos</h3>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
                    Esta pantalla es exclusiva para superadministrador. Aquí puede asignar roles y permisos directos por usuario.
                </p>
            </div>
        </div>
    </div>
</div>

@if($errors->has('access_control'))
<div class="alert alert-danger mb-6">
    {{ $errors->first('access_control') }}
</div>
@endif

<div class="card">
    <div class="card-body p-0">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[980px]">
                <thead class="bg-slate-50 dark:bg-slate-800/60">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Usuario</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Roles</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Permisos directos</th>
                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-600 dark:text-slate-300">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    @php
                        $formId = 'access-form-' . $user->id;
                        $testPermission = session('permission_test');
                        $testForThisUser = is_array($testPermission) && (($testPermission['user_id'] ?? null) === $user->id);
                    @endphp
                    <tr class="border-t border-slate-200 dark:border-slate-700">
                        <td class="px-4 py-4 align-top">
                            <div class="font-medium text-slate-900 dark:text-white">{{ $user->name }}</div>
                            <div class="text-sm text-slate-500 dark:text-slate-400">{{ $user->email }}</div>
                            @if($testForThisUser)
                            <div class="mt-3 text-sm">
                                @if(!empty($testPermission['granted']))
                                <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700">
                                    Permiso "{{ $testPermission['permission_name'] }}" concedido
                                </span>
                                @else
                                <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700">
                                    Permiso "{{ $testPermission['permission_name'] }}" NO concedido
                                </span>
                                @endif
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-4 align-top">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($roles as $role)
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                    <input
                                        form="{{ $formId }}"
                                        type="checkbox"
                                        name="roles[]"
                                        value="{{ $role->id }}"
                                        class="rounded border-slate-300 dark:border-slate-600"
                                        @checked($user->roles->contains('id', $role->id))>
                                    <span>{{ $role->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-4 align-top">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach($permissions as $permission)
                                <label class="inline-flex items-center gap-2 text-sm text-slate-700 dark:text-slate-300">
                                    <input
                                        form="{{ $formId }}"
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->id }}"
                                        class="rounded border-slate-300 dark:border-slate-600"
                                        @checked($user->permissions->contains('id', $permission->id))>
                                    <span>{{ $permission->name }}</span>
                                </label>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-4 align-top text-right">
                            <form id="{{ $formId }}" action="{{ route('access.control.update', $user->id) }}" method="POST" class="mb-3">
                                @csrf
                            </form>
                            <button
                                type="submit"
                                form="{{ $formId }}"
                                class="btn btn-primary"
                                onclick="return confirm('¿Guardar cambios de acceso para {{ $user->name }}?');">
                                <i class="fas fa-save mr-1"></i> Guardar
                            </button>
                            <form action="{{ route('access.control.test', $user->id) }}" method="POST" class="mt-3">
                                @csrf
                                <div class="flex items-center justify-end gap-2">
                                    <select name="permission_id" class="rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 py-1 text-sm max-w-[220px]" required>
                                        <option value="">Probar permiso...</option>
                                        @foreach($permissions as $permission)
                                        <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-secondary text-sm">
                                        Test
                                    </button>
                                </div>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-10 text-center text-slate-500 dark:text-slate-400">
                            No hay usuarios para administrar.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
