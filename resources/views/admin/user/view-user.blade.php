@extends('layouts.panel')

@section('title', 'Detalle de usuario')
@section('pageheading', 'Detalle de usuario')

@section('datatable')
@php
  $avatar = $user->avatar_url;
@endphp

<div class="card">
  <div class="card-body p-6">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
      <div class="flex items-center gap-4">
        <img src="{{ $avatar }}" alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover border border-slate-200 dark:border-slate-700">
        <div>
          <h2 class="text-xl font-semibold text-slate-900 dark:text-white">{{ $user->name }}</h2>
          <p class="text-slate-600 dark:text-slate-400">{{ $user->email }}</p>
          <div class="mt-2">
            <span class="chip-brand bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-300 dark:border-blue-700">
              {{ $user->roles->first()?->name ?? 'Sin rol' }}
            </span>
          </div>
        </div>
      </div>

      <a href="{{ url('show-users') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left mr-1"></i> Regresar
      </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">ID</div>
        <div class="mt-1 font-medium text-slate-900 dark:text-white">{{ $user->id }}</div>
      </div>

      <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Estado</div>
        <div class="mt-1 font-medium text-slate-900 dark:text-white">{{ $user->deleted_at ? 'Inactivo' : 'Activo' }}</div>
      </div>

      <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Teléfono</div>
        <div class="mt-1 font-medium text-slate-900 dark:text-white">{{ $user->phone ?: 'No registrado' }}</div>
      </div>

      <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Nacimiento</div>
        <div class="mt-1 font-medium text-slate-900 dark:text-white">{{ $user->birthdate ?: 'No registrado' }}</div>
      </div>

      <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Creado</div>
        <div class="mt-1 font-medium text-slate-900 dark:text-white">{{ $user->created_at?->format('d/m/Y H:i') }}</div>
      </div>

      <div class="rounded-lg border border-slate-200 dark:border-slate-700 p-4">
        <div class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400">Actualizado</div>
        <div class="mt-1 font-medium text-slate-900 dark:text-white">{{ $user->updated_at?->format('d/m/Y H:i') }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
