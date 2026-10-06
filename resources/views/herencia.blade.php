@extends('layouts.main')

@section('title', 'Herencia')

@section('page-hero')
  <div>
    <h1 class="page-title">Herencia</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Fe cristiana y raíces de la diáspora africana.</p>
  </div>
@endsection

@section('content')
  <section class="panel space-y-6">
    <div>
      <h2 class="text-xl font-bold text-[var(--green-dark)] dark:text-[var(--green-light)] mb-2">
        Una herencia de fe
      </h2>
      <p class="leading-relaxed text-gray-700 dark:text-gray-200">
        Como comunidad de población afro, reconocemos que nuestra mayor herencia es el Evangelio
        de nuestro Señor Jesucristo. En Él encontramos identidad, dignidad y esperanza, y desde esa
        certeza caminamos como pueblo guardado por la Palabra de Dios.
      </p>
    </div>

    <div>
      <h2 class="text-xl font-bold text-[var(--green-dark)] dark:text-[var(--green-light)] mb-2">
        Raíces y memoria
      </h2>
      <p class="leading-relaxed text-gray-700 dark:text-gray-200">
        Valoramos la historia y la memoria de la diáspora africana: su resiliencia, su fe y su aporte
        a la vida de nuestras comunidades. Honrar estas raíces nos ayuda a comprender quiénes somos y
        a transmitir con gratitud lo recibido a las nuevas generaciones.
      </p>
    </div>

    <div>
      <h2 class="text-xl font-bold text-[var(--green-dark)] dark:text-[var(--green-light)] mb-2">
        Legado para las nuevas generaciones
      </h2>
      <p class="leading-relaxed text-gray-700 dark:text-gray-200">
        Nuestro compromiso es dejar un legado de fe, servicio y unidad. Formamos líderes y discípulos
        que, firmes en la Palabra de Dios, cuiden de sus hermanos y edifiquen la familia y la comunidad
        bajo principios bíblicos: “Biblia solo Biblia, el dedo índice en la Palabra de Dios”.
      </p>
    </div>
  </section>
@endsection
