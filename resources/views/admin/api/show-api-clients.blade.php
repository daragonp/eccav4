@php $showAddButton = true; @endphp

@extends('layouts.panel')

@section('title', 'Claves de API')
@section('pageheading', 'Claves de API (emisora)')

@section('addbutton', 'Nuevo cliente')
@section('formaction', url('add-api-client'))

{{-- CAMPOS DEL MODAL PARA AGREGAR UN CLIENTE --}}
@section('modalFields')
  <div class="grid grid-cols-1 gap-4">
    <div>
      <label for="nameInput" class="block text-sm mb-1">Nombre del servicio o emisora</label>
      <input id="nameInput" type="text" name="name" required maxlength="120"
             placeholder="Ej. Emisora ECCA Radio"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        Al crear el cliente se generará un token que se mostrará <strong>una sola vez</strong>.
      </p>
    </div>
  </div>
@endsection

@section('datatable')
  {{-- Token recién generado (se muestra una sola vez) --}}
  @if(session('new_api_token'))
    <div class="card mb-4 border border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-900/20">
      <div class="card-body p-6">
        <h2 class="text-lg font-semibold mb-2 text-amber-800 dark:text-amber-300">
          <i class="fa-solid fa-key mr-2"></i> Token del nuevo cliente
        </h2>
        <p class="text-sm text-amber-800 dark:text-amber-300 mb-3">
          Copia este token ahora y guárdalo en un lugar seguro. Por seguridad, <strong>no se volverá a mostrar</strong>.
        </p>
        <div class="flex flex-col sm:flex-row gap-2">
          <input id="newApiToken" type="text" readonly value="{{ session('new_api_token') }}"
                 class="w-full rounded border border-amber-300 dark:border-amber-700 bg-white dark:bg-slate-900 px-3 py-2 font-mono text-sm text-slate-900 dark:text-white">
          <button type="button" onclick="copiarTokenApi()" class="btn btn-primary whitespace-nowrap">
            <i class="fa-solid fa-copy mr-1"></i> Copiar
          </button>
        </div>
      </div>
    </div>

    @push('scripts')
    <script>
      function copiarTokenApi() {
        const campo = document.getElementById('newApiToken');
        if (!campo) return;
        campo.select();
        campo.setSelectionRange(0, 99999);
        if (navigator.clipboard) {
          navigator.clipboard.writeText(campo.value);
        } else {
          document.execCommand('copy');
        }
      }
    </script>
    @endpush
  @endif

  {{-- Buscador --}}
  <form method="GET" action="{{ url()->current() }}" class="flex flex-col sm:flex-row gap-2 mb-4">
    <div class="relative flex-1">
      <input
        type="text"
        name="search"
        value="{{ request('search') }}"
        placeholder="Buscar cliente por nombre o prefijo del token..."
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
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Nombre</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Prefijo del token</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Último uso</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($clients as $client)
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">
                  {{ $client->name }}
                  @if($client->trashed())
                    <span class="ml-1 text-xs text-slate-400">(archivado)</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm font-mono text-slate-600 dark:text-slate-300">{{ $client->token_prefix }}…</td>
                <td class="px-4 py-3 text-sm">
                  @if($client->active && !$client->trashed())
                    <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700"><i class="fa-solid fa-check mr-1"></i> Activo</span>
                  @else
                    <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700"><i class="fa-solid fa-ban mr-1"></i> Inactivo</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                  {{ $client->last_used_at ? $client->last_used_at->format('d/m/Y H:i') : 'Nunca' }}
                </td>
                <td class="px-4 py-3 text-sm">
                  <div class="flex items-center justify-center gap-2">
                    {{-- Activar / Revocar --}}
                    <form action="{{ url('toggle-api-client', $client->id) }}" method="POST"
                          onsubmit="return confirm('¿Desea cambiar el estado de este cliente API?');">
                      @csrf
                      @if($client->active && !$client->trashed())
                        <button type="submit" class="btn btn-sm btn-warning" title="Revocar">
                          <i class="fa-solid fa-power-off"></i>
                        </button>
                      @else
                        <button type="submit" class="btn btn-sm btn-success" title="Activar">
                          <i class="fa-solid fa-toggle-on"></i>
                        </button>
                      @endif
                    </form>

                    {{-- Archivar (soft delete) --}}
                    @unless($client->trashed())
                    <form action="{{ url('delete-api-client', $client->id) }}" method="POST"
                          onsubmit="return confirm('¿Desea archivar este cliente API? Dejará de tener acceso.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-danger" title="Archivar">
                        <i class="fa-solid fa-box-archive"></i>
                      </button>
                    </form>
                    @endunless
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                  <i class="fa-solid fa-key text-4xl mb-3 text-slate-300 dark:text-slate-600 block"></i>
                  <p class="font-medium">No hay clientes API registrados</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($clients->hasPages())
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      {{ $clients->links() }}
    </div>
  @endif
@endsection
