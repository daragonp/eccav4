<?php $showAddButton = true; ?>



<?php $__env->startSection('title', 'Versículos del día'); ?>
<?php $__env->startSection('pageheading', 'Palabra de vida'); ?>

<?php $__env->startSection('addbutton', 'Agregar'); ?>
<?php $__env->startSection('formaction', url('addverse')); ?>


<?php $__env->startSection('modalFields'); ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="dateInput" class="block text-sm mb-1">Fecha</label>
      <input id="dateInput" type="date" name="date" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="pdfInput" class="block text-sm mb-1">Documento PDF</label>
      <input id="pdfInput" type="file" name="video" accept=".pdf"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div class="md:col-span-2">
      <label for="imageInput" class="block text-sm mb-1">Imagen</label>
      <input id="imageInput" type="file" name="image" accept="image/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div class="md:col-span-2">
      <label for="audioInput" class="block text-sm mb-1">Audio</label>
      <input id="audioInput" type="file" name="audio" accept="audio/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('datatable'); ?>
  <div class="table-wrap">
    <?php echo $dataTable->table(['class' => 'w-full']); ?>

  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  <?php echo $dataTable->scripts(); ?>

<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/admin/quote/show-quote.blade.php ENDPATH**/ ?>