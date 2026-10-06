@extends('layouts.main')

@section('title', 'Programación')

@section('page-hero')
  <div>
    <h1 class="page-title">Programación de radio</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Conoce la parrilla semanal de nuestra emisora.</p>
  </div>
@endsection

@section('content')
  @if ($schedules->isEmpty())
    <section class="panel">
      <p class="leading-relaxed text-gray-700 dark:text-gray-200">
        Aún no hay programas publicados en la parrilla. Vuelve pronto para conocer nuestra programación.
      </p>
    </section>
  @else
    <div class="space-y-8">
      @foreach ($schedules as $day => $programas)
        <section class="panel space-y-5">
          <h2 class="text-2xl font-extrabold text-[var(--green-dark)] dark:text-[var(--green-light)]">
            {{ $programas->first()->day_name }}
          </h2>
          <div class="hr-brand"></div>

          <ul class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach ($programas as $programa)
              <li class="rounded-lg border border-[var(--green-light)] bg-white dark:bg-gray-900 p-4 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                  <h3 class="text-lg font-bold text-[var(--green-dark)] dark:text-[var(--green-light)]">
                    {{ $programa->name }}
                  </h3>
                  <span class="shrink-0 rounded-full bg-[var(--green-dark)] text-white px-3 py-1 text-sm font-medium">
                    {{ \Carbon\Carbon::parse($programa->start)->format('H:i') }} - {{ \Carbon\Carbon::parse($programa->end)->format('H:i') }}
                  </span>
                </div>

                @if (!empty($programa->host))
                  <p class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                    Dirige: {{ $programa->host }}
                  </p>
                @endif

                @if (!empty($programa->about))
                  <p class="mt-2 leading-relaxed text-gray-700 dark:text-gray-200">
                    {{ $programa->about }}
                  </p>
                @endif
              </li>
            @endforeach
          </ul>
        </section>
      @endforeach
    </div>
  @endif
@endsection
