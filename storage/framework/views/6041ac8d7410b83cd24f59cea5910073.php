<?php $__env->startSection('title', 'Palabra de vida'); ?>

<?php $__env->startPush('head'); ?>
  
  <style>[x-cloak]{display:none!important}</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
      <input
        type="date"
        id="datePicker"
        max="<?php echo e(date('Y-m-d')); ?>"
        class="w-full sm:w-auto rounded-md border border-gray-300 bg-white text-gray-900
               focus:ring-[var(--green-light)] focus:border-[var(--green-light)]
               dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100"
      />
      <h2 class="text-xl font-semibold text-[var(--green-dark)] dark:text-[var(--green-light)]">Historial</h2>
    </div>

    <?php $__currentLoopData = $verses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month => $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
      <div class="text-start">
        <span class="inline-block bg-[var(--green-dark)] text-white px-4 py-1 rounded-full text-base font-extrabold
                     border-2 border-[var(--green-light)] shadow-md">
          <?php echo e($month); ?>

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
            <?php $__currentLoopData = $group; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $verse): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              <tr class="hover:bg-black/5 dark:hover:bg-white/5">
                <td class="px-3 py-2 whitespace-nowrap">
                  <?php echo e(\Carbon\Carbon::parse($verse->date)->format('d/m/Y')); ?>

                </td>
                <td class="px-3 py-2">
                  
                  <a href="<?php echo e(asset('images/bible/' . $verse->image)); ?>"
                     data-turbo="false"
                     data-lightbox="verses"
                     data-title="Derechos Reservados E.C.C.A. — <?php echo e(\Carbon\Carbon::parse($verse->date)->format('d/m/Y')); ?>">
                    <img src="<?php echo e(asset('images/bible/' . $verse->image)); ?>"
                         alt="Imagen"
                         class="w-20 h-auto rounded-md shadow">
                  </a>
                </td>
                <td class="px-3 py-2">
                  <?php if($verse->video): ?>
                    <button type="button"
                      class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-sm
                             border-black/20 hover:bg-black/5 transition
                             focus:outline-none focus-visible:ring-2 focus-visible:ring-[var(--green-light)]
                             focus-visible:ring-offset-2 focus-visible:ring-offset-white
                             dark:border-white/20 dark:hover:bg-white/10
                             dark:focus-visible:ring-offset-gray-900"
                      title="Ver PDF"
                      onclick="window.dispatchEvent(new CustomEvent('open-pdf', { detail: '<?php echo e(asset('documents/quote/' . $verse->video)); ?>' }))">
                      <i class="fa-solid fa-file-pdf"></i>
                      <strong>Ver PDF</strong>
                    </button>
                  <?php else: ?>
                    <p class="text-gray-500 dark:text-gray-400">Pendiente de documento</p>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('modals'); ?>
  
  <div x-data="{ show:false, pdfSrc:'' }"
       x-init="
         window.addEventListener('open-pdf', e => {
           pdfSrc = e.detail;
           show = true;
           document.body.classList.add('overflow-hidden');
         });
       "
       x-on:keydown.escape.window="show=false; pdfSrc=''; document.body.classList.remove('overflow-hidden')"
       x-cloak>
    <div x-show="show"
         x-transition.opacity
         x-on:click.self="show=false; pdfSrc=''; document.body.classList.remove('overflow-hidden')"
         class="fixed inset-0 z-[2147483647] grid place-items-center p-4 bg-black/60"
         role="dialog" aria-modal="true" aria-labelledby="pdfModalTitle">
      <div class="w-full max-w-6xl bg-white dark:bg-gray-900 rounded-xl shadow-xl ring-1 ring-gray-200 dark:ring-gray-800 overflow-hidden"
           x-on:click.stop>
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-800">
          <h3 id="pdfModalTitle" class="text-base font-semibold">Vista previa del PDF</h3>
          <button type="button"
                  class="inline-flex items-center justify-center rounded-md px-2 py-1
                         hover:bg-black/5 dark:hover:bg-white/10 focus:outline-none
                         focus-visible:ring-2 focus-visible:ring-[var(--green-light)]"
                  aria-label="Cerrar"
                  x-on:click="show=false; pdfSrc=''; document.body.classList.remove('overflow-hidden')">
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script data-turbo-eval="true">
(function(){
  // availableDates viene del servidor en formato 'YYYY-MM-DD'
  const AVAILABLE = new Set(<?php echo json_encode($availableDates ?? [], 15, 512) ?>);

  function bindDatepicker() {
    const datePicker = document.getElementById('datePicker');
    if (!datePicker || datePicker.dataset.bound === '1') return; // evita doble binding con Turbo
    datePicker.dataset.bound = '1';

    // Max hoy (formato local sin zona horaria)
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const todayStr = `${yyyy}-${mm}-${dd}`;
    datePicker.setAttribute('max', todayStr);

    datePicker.addEventListener('change', function () {
      // Usa el valor tal cual del input (YYYY-MM-DD) — sin toISOString()
      const selectedDate = this.value;
      if (!selectedDate) return;

      if (selectedDate > todayStr) {
        if (window.Swal && typeof Swal.fire === 'function') {
          Swal.fire({
            icon: 'error',
            title: 'Fecha inválida',
            text: 'No puedes seleccionar una fecha futura.',
            confirmButtonColor: '#2a3d1f',
            confirmButtonText: 'Entendido'
          });
        } else {
          alert('No puedes seleccionar una fecha futura.');
        }
        this.value = '';
        return;
      }

      if (AVAILABLE.has(selectedDate)) {
        const url = '/single-feed/' + selectedDate;
        // Navega en la misma pestaña (Turbo si está disponible)
        if (window.Turbo && typeof window.Turbo.visit === 'function') {
          window.Turbo.visit(url);
        } else {
          window.location.href = url;
        }
      } else {
        if (window.Swal && typeof Swal.fire === 'function') {
          Swal.fire({
            icon: 'warning',
            title: 'Sin contenido',
            text: 'No hay registro disponible para esa fecha.',
            confirmButtonColor: '#2a3d1f',
            confirmButtonText: 'Aceptar'
          });
        } else {
          alert('No hay registro disponible para esa fecha.');
        }
        this.value = '';
      }
    });
  }

  // Soporta primer load y navegaciones Turbo
  document.addEventListener('DOMContentLoaded', bindDatepicker);
  document.addEventListener('turbo:load', bindDatepicker);
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/worship-home.blade.php ENDPATH**/ ?>