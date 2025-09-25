@extends('layouts.main')

@section('title', 'Palabra de vida')

@push('head')
  {{-- (Opcional) Estilos específicos de esta página --}}
  <style>[x-cloak]{display:none!important}</style>
@endpush

@section('content')
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
      <input
        type="date"
        id="datePicker"
        max="{{ date('Y-m-d') }}"
        class="w-full sm:w-auto rounded-md border border-gray-300 bg-white text-gray-900
               focus:ring-[var(--green-light)] focus:border-[var(--green-light)]
               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
      />
      <h2 class="text-xl font-semibold text-[var(--green-dark)] dark:text-[var(--green-light)]">Historial</h2>
    </div>

    @foreach ($verses as $month => $group)
      <div class="text-start">
        <span class="inline-block bg-[var(--green-dark)] text-white px-4 py-1 rounded-full text-base font-extrabold
                     border-2 border-[var(--green-light)] shadow-md">
          {{ $month }}
        </span>
      </div>

      <div class="overflow-x-auto rounded-lg ring-1 ring-gray-200 dark:ring-gray-800">
        <table class="w-full min-w-[640px] text-sm text-center">
          <thead class="bg-[var(--green-dark)] text-white dark:bg-gray-800">
            <tr>
              <th class="px-3 py-2 font-semibold">Fecha</th>
              <th class="px-3 py-2 font-semibold">Imagen</th>
              <th class="px-3 py-2 font-semibold">Documento</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900">
            @foreach ($group as $verse)
              <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                <td class="px-3 py-2 whitespace-nowrap">
                  {{ \Carbon\Carbon::parse($verse->date)->format('d/m/Y') }}
                </td>
                <td class="px-3 py-2">
                  {{-- LIGHTBOX --}}
                  <a href="{{ asset('images/bible/' . $verse->image) }}"
                     data-lightbox="verses"
                     data-title="Derechos Reservados E.C.C.A. — {{ \Carbon\Carbon::parse($verse->date)->format('d/m/Y') }}">
                    <img src="{{ asset('images/bible/' . $verse->image) }}"
                         alt="Imagen"
                         class="w-20 h-auto rounded-md shadow">
                  </a>
                </td>
                <td class="px-3 py-2">
                  @if ($verse->video)
                    <button type="button"
                      class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-sm
                             border-black/20 hover:bg-black/5 transition
                             focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--green-light)]
                             focus-visible:ring-offset-2 focus-visible:ring-offset-white
                             dark:border-white/20 dark:hover:bg-white/10
                             dark:focus-visible:ring-offset-gray-900"
                      title="Ver PDF"
                      onclick="window.dispatchEvent(new CustomEvent('open-pdf', { detail: '{{ asset('documents/quote/' . $verse->video) }}' }))">
                      <i class="fa-solid fa-file-pdf"></i>
                      <strong>Ver PDF</strong>
                    </button>
                  @else
                    <p class="text-gray-500 dark:text-gray-400">Pendiente de documento</p>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot class="bg-[var(--green-dark)] text-white dark:bg-gray-800">
            <tr>
              <th class="px-3 py-2 font-semibold">Fecha</th>
              <th class="px-3 py-2 font-semibold">Imagen</th>
              <th class="px-3 py-2 font-semibold">Documento</th>
            </tr>
          </tfoot>
        </table>
      </div>
    @endforeach
  </div>
@endsection

@push('modals')
  {{-- Modal PDF fuera del <main> para evitar stacking/transform issues --}}
  <div x-data="{ show:false, pdfSrc:'' }"
       x-init="
         window.addEventListener('open-pdf', e => {
           pdfSrc = e.detail;
           show = true;
           document.body.classList.add('overflow-hidden');
         });
       "
       @keydown.escape.window="show=false; pdfSrc=''; document.body.classList.remove('overflow-hidden')"
       x-cloak>
    <div x-show="show"
         x-transition.opacity
         @click.self="show=false; pdfSrc=''; document.body.classList.remove('overflow-hidden')"
         class="fixed inset-0 z-[2147483647] grid place-items-center p-4 bg-black/60"
         role="dialog" aria-modal="true" aria-labelledby="pdfModalTitle">
      <div class="w-full max-w-6xl bg-white dark:bg-gray-900 rounded-xl shadow-xl ring-1 ring-gray-200 dark:ring-gray-800 overflow-hidden"
           @click.stop>
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-800">
          <h3 id="pdfModalTitle" class="text-base font-semibold">Vista previa del PDF</h3>
          <button type="button"
                  class="inline-flex items-center justify-center rounded-md px-2 py-1
                         hover:bg-black/5 dark:hover:bg-white/10 focus:outline-none
                         focus-visible:ring-2 focus-visible:ring-[var(--green-light)]"
                  aria-label="Cerrar"
                  @click="show=false; pdfSrc=''; document.body.classList.remove('overflow-hidden')">
            <i class="fa-solid fa-xmark text-lg"></i>
          </button>
        </div>
        <iframe :src="pdfSrc"
                class="w-full h-[90svh] min-h-[720px]"
                style="border:none"
                loading="lazy"
                allowfullscreen>
        </iframe>
      </div>
    </div>
  </div>
@endpush

@push('scripts')
  <script>
    // Configuración Lightbox (desactiva scroll y añade transiciones)
    if (window.lightbox && typeof lightbox.option === 'function') {
      lightbox.option({
        fadeDuration: 200,
        resizeDuration: 200,
        wrapAround: true,
        disableScrolling: true,
        positionFromTop: 50
      });
    }

    // Selector de fecha
    const availableDates = @json($availableDates);
    document.addEventListener('DOMContentLoaded', function () {
      const datePicker = document.getElementById('datePicker');
      const today = new Date().toISOString().split('T')[0];
      datePicker.setAttribute('max', today);

      datePicker.addEventListener('change', function () {
        const selectedDate = new Date(this.value).toISOString().split('T')[0];

        if (selectedDate > today) {
          Swal.fire({
            icon: 'error',
            title: 'Fecha inválida',
            text: 'No puedes seleccionar una fecha futura.',
            confirmButtonColor: '#2a3d1f',
            confirmButtonText: 'Entendido'
          });
          this.value = '';
          return;
        }

        if (availableDates.includes(selectedDate)) {
          window.open(`/single-feed/${selectedDate}`, '_blank');
        } else {
          Swal.fire({
            icon: 'warning',
            title: 'Sin contenido',
            text: 'No hay registro disponible para esa fecha.',
            confirmButtonColor: '#2a3d1f',
            confirmButtonText: 'Aceptar'
          });
          this.value = '';
        }
      });
    });
  </script>
@endpush
