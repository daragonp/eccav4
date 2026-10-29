@extends('layouts.panel')

@section('title', 'Estadísticas API')
@section('pageheading', 'Estadísticas de uso de la API')

@section('datatable')
  {{-- Tarjetas resumen --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="card">
      <div class="card-body p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">Total de peticiones</p>
        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($total) }}</p>
      </div>
    </div>
    <div class="card">
      <div class="card-body p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">Autorizadas</p>
        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ number_format($autorizadas) }}</p>
      </div>
    </div>
    <div class="card">
      <div class="card-body p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">No autorizadas</p>
        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ number_format($noAutorizadas) }}</p>
      </div>
    </div>
    <div class="card">
      <div class="card-body p-5">
        <p class="text-sm text-slate-500 dark:text-slate-400">Clientes activos</p>
        <p class="text-2xl font-bold text-slate-900 dark:text-white mt-1">{{ number_format($clientesActivos) }}</p>
      </div>
    </div>
  </div>

  {{-- Conteo por cliente --}}
  <div class="card mb-6">
    <div class="card-body p-0">
      <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
          <i class="fa-solid fa-users-gear mr-2 text-brand-green"></i> Uso por cliente
        </h2>
      </div>
      <div class="table-wrap">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Cliente</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Prefijo</th>
              <th class="px-4 py-3 text-right text-sm font-semibold text-slate-900 dark:text-white">Peticiones</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Último uso</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($clientes as $client)
              @php $conteo = $totalesPorCliente[$client->id] ?? null; @endphp
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-sm font-medium text-slate-900 dark:text-white">
                  {{ $client->name }}
                  @if($client->trashed())
                    <span class="ml-1 text-xs text-slate-400">(archivado)</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm font-mono text-slate-600 dark:text-slate-300">{{ $client->token_prefix }}…</td>
                <td class="px-4 py-3 text-sm text-right text-slate-900 dark:text-white">{{ number_format($conteo->total ?? 0) }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                  {{ !empty($conteo->ultimo) ? \Carbon\Carbon::parse($conteo->ultimo)->format('d/m/Y H:i') : 'Nunca' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="px-4 py-8 text-center text-slate-500 dark:text-slate-400">
                  No hay clientes API registrados
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Log reciente --}}
  <div class="card">
    <div class="card-body p-0">
      <div class="px-4 py-3 border-b border-slate-200 dark:border-slate-700">
        <h2 class="text-base font-semibold text-slate-900 dark:text-white">
          <i class="fa-solid fa-clock-rotate-left mr-2 text-brand-green"></i> Peticiones recientes
        </h2>
      </div>
      <div class="table-wrap">
        <table class="w-full">
          <thead class="bg-slate-50 dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
            <tr>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Cliente</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Endpoint</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Método</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">IP</th>
              <th class="px-4 py-3 text-center text-sm font-semibold text-slate-900 dark:text-white">Estado</th>
              <th class="px-4 py-3 text-left text-sm font-semibold text-slate-900 dark:text-white">Fecha</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
            @forelse ($logs as $log)
              <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                <td class="px-4 py-3 text-sm text-slate-900 dark:text-white">
                  {{ $log->client->name ?? '— (no autorizado)' }}
                </td>
                <td class="px-4 py-3 text-sm font-mono text-slate-600 dark:text-slate-300">{{ $log->endpoint }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $log->method }}</td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">{{ $log->ip }}</td>
                <td class="px-4 py-3 text-sm text-center">
                  @if($log->status_code >= 200 && $log->status_code < 300)
                    <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700">{{ $log->status_code }}</span>
                  @else
                    <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700">{{ $log->status_code }}</span>
                  @endif
                </td>
                <td class="px-4 py-3 text-sm text-slate-600 dark:text-slate-300">
                  {{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : '—' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="px-4 py-12 text-center text-slate-500 dark:text-slate-400">
                  <i class="fa-solid fa-chart-line text-4xl mb-3 text-slate-300 dark:text-slate-600 block"></i>
                  <p class="font-medium">Aún no se han registrado peticiones</p>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  @if ($logs->hasPages())
    <div class="p-4 border-t border-slate-200 dark:border-slate-700">
      {{ $logs->links() }}
    </div>
  @endif
@endsection
