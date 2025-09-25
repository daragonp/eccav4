@extends('layouts.main')

@section('title', 'Declaración de fe')

@section('page-hero')
  <div>
    <h1 class="page-title">Declaración de fe</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Aquello que creemos y enseñamos.</p>
  </div>
@endsection

@section('content')
  <section class="panel space-y-6">
    <div>
      <h2 class="text-xl font-bold text-[var(--green-dark)] dark:text-[var(--green-light)] mb-2">
        1. La Biblia
      </h2>
      <p class="leading-relaxed text-gray-700 dark:text-gray-200">
        Creemos que la Biblia es la Palabra de Dios, inspirada y autoridad suprema en fe y conducta.
      </p>
    </div>

    <div>
      <h2 class="text-xl font-bold text-[var(--green-dark)] dark:text-[var(--green-light)] mb-2">
        2. Dios
      </h2>
      <p class="leading-relaxed text-gray-700 dark:text-gray-200">
        Creemos en un solo Dios, eterno y trino: Padre, Hijo y Espíritu Santo.
      </p>
    </div>

    <div>
      <h2 class="text-xl font-bold text-[var(--green-dark)] dark:text-[var(--green-light)] mb-2">
        3. Salvación
      </h2>
      <p class="leading-relaxed text-gray-700 dark:text-gray-200">
        La salvación es por gracia mediante la fe en Jesucristo, Señor y Salvador.
      </p>
    </div>

    {{-- Agrega más puntos según tu declaración --}}
  </section>
@endsection
