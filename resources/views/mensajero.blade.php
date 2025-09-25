@extends('layouts.main')

@section('title', 'Mensajero veloz')

@section('page-hero')
  <div>
    <h1 class="page-title">Mensajero veloz</h1>
    <div class="hr-brand"></div>
    <p class="page-subtle">Compartiendo el evangelio con diligencia.</p>
  </div>
@endsection

@section('content')
  <section class="panel space-y-6">
    <p class="leading-relaxed text-gray-700 dark:text-gray-200">
      Este programa promueve y coordina acciones de evangelización, oración y servicio
      en la comunidad, con foco en las poblaciones afro.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div>
        <h2 class="text-lg font-semibold mb-2">¿Cómo participar?</h2>
        <ul class="content-list">
          <li>Únete a equipos de visita y oración.</li>
          <li>Comparte recursos y materiales de discipulado.</li>
          <li>Apoya iniciativas de ayuda y acompañamiento.</li>
        </ul>
      </div>
      <aside class="space-y-2">
        <h3 class="text-sm uppercase tracking-wide text-gray-500 dark:text-gray-400">Contacto</h3>
        <a href="mailto:radio@emancipacioncristianaafro.org" class="content-link inline-flex items-center gap-2">
          <i class="fa-solid fa-envelope"></i> radio@emancipacioncristianaafro.org
        </a>
      </aside>
    </div>
  </section>
@endsection
