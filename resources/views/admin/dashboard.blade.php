@extends('layouts.panel')

@section('title', 'Panel principal')
@section('pageheading', 'Panel principal')

@section('datatable')
  <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-4">
    <div class="card-stats-users">
      <div class="card-body">
        <div class="text-xs uppercase text-slate-500 dark:text-slate-400">Usuarios</div>
        <div class="mt-1 text-3xl font-extrabold">{{ $stats['users'] ?? 0 }}</div>
        <div class="mt-2 text-sm"><a href="{{ url('show-users') }}" class="text-[var(--brand-blue)]">Gestionar usuarios →</a></div>
      </div>
    </div>
    <div class="card-stats-verses">
      <div class="card-body">
        <div class="text-xs uppercase opacity-80">Versículos</div>
        <div class="mt-1 text-3xl font-extrabold">{{ $stats['verses'] ?? 0 }}</div>
        <div class="mt-2 text-sm"><a href="{{ url('show-quote') }}" class="text-white underline">Ver versículos →</a></div>
      </div>
    </div>
    <div class="card-stats-schedules">
      <div class="card-body">
        <div class="text-xs uppercase opacity-80">Programación</div>
        <div class="mt-1 text-3xl font-extrabold">{{ $stats['schedules'] ?? 0 }}</div>
        <div class="mt-2 text-sm"><a href="{{ url('show-schedule') }}" class="underline">Ver parrilla →</a></div>
      </div>
    </div>
    <div class="card-stats-banners">
      <div class="card-body">
        <div class="text-xs uppercase text-slate-700 dark:text-slate-300">Banners</div>
        <div class="mt-1 text-3xl font-extrabold">{{ $stats['banners'] ?? 0 }}</div>
        <div class="mt-2 text-sm"><a href="{{ url('show-slider') }}" class="text-[var(--ink)] dark:text-slate-200">Gestionar →</a></div>
      </div>
    </div>
    <div class="card-stats-news">
      <div class="card-body">
        <div class="text-xs uppercase opacity-80">Noticias</div>
        <div class="mt-1 text-3xl font-extrabold">{{ $stats['news'] ?? 0 }}</div>
        <div class="mt-2 text-sm"><a href="{{ url('show-news') }}" class="underline">Ir a noticias →</a></div>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">
    <div class="card">
      <div class="card-body">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold">Últimos versículos</h3>
          <a class="chip-brand" href="{{ url('show-quote') }}"><i class="fa-solid fa-book-bible"></i> Ver todo</a>
        </div>
        <div class="mt-3 overflow-x-auto">
          <table class="w-full">
            <thead>
              <tr class="text-left text-sm">
                <th class="py-2">Fecha</th>
                <th class="py-2">Imagen</th>
                <th class="py-2">PDF</th>
              </tr>
            </thead>
            <tbody class="text-sm">
              @forelse ($latestVerses as $v)
              <tr class="border-t border-white/30 dark:border-white/10">
                <td class="py-2">{{ \Carbon\Carbon::parse($v->date)->format('Y-m-d') }}</td>
                <td class="py-2">
                  @if($v->image)
                    <img src="{{ asset('images/bible/'.$v->image) }}" alt="" class="w-10 h-10 rounded object-cover">
                  @else
                    —
                  @endif
                </td>
                <td class="py-2">
                  @if($v->video)
                    <a class="btn btn-info text-xs" target="_blank" href="{{ asset('documents/quote/'.$v->video) }}">
                      <i class="fa-solid fa-file-pdf"></i> Ver PDF
                    </a>
                  @else
                    —
                  @endif
                </td>
              </tr>
              @empty
              <tr><td colspan="3" class="py-4 text-slate-500">Sin registros</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="card-body">
        <div class="flex items-center justify-between">
          <h3 class="text-lg font-semibold">Últimas noticias</h3>
          <a class="chip-brand" href="{{ url('show-news') }}"><i class="fa-solid fa-newspaper"></i> Ver todo</a>
        </div>
        <ul class="mt-3 space-y-2">
          @forelse ($latestNews as $n)
            <li class="flex items-center justify-between gap-3 border-t border-white/30 dark:border-white/10 pt-2">
              <div class="min-w-0">
                <div class="font-medium truncate">{{ $n->title }}</div>
                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $n->created_at->format('Y-m-d H:i') }}</div>
              </div>
              <a href="{{ url('view-news/'.$n->id) }}" class="btn btn-info text-xs"><i class="fa-solid fa-eye"></i> Ver</a>
            </li>
          @empty
            <li class="text-slate-500">Sin registros</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
@endsection