<?php $showAddButton = true; ?>



<?php $__env->startSection('title', 'Culto Dominical'); ?>
<?php $__env->startSection('pageheading', 'Culto Dominical'); ?>

<?php $__env->startSection('addbutton', 'Agregar'); ?>
<?php $__env->startSection('formaction', url('addworship')); ?>


<?php $__env->startSection('modalFields'); ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="broadcast" class="block text-sm mb-1">Fecha de emisión</label>
      <input id="broadcast" type="date" name="broadcast" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
    </div>
    <div>
      <label for="title" class="block text-sm mb-1">Título (opcional)</label>
      <input id="title" type="text" name="title"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
             placeholder="Si no se proporciona, se generará automáticamente">
    </div>
    <div class="md:col-span-2">
      <label for="abstract" class="block text-sm mb-1">Resumen</label>
      <textarea id="abstract" name="abstract" rows="4"
                class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2"
                placeholder="Escribe un resumen del culto. Si no lo proporcionas, se generará automáticamente con IA."></textarea>
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        <i class="fas fa-info-circle mr-1"></i> 
        Puedes escribir un resumen manualmente o dejarlo vacío para que la IA lo genere automáticamente
      </p>
    </div>
    <div class="md:col-span-2">
      <label for="image" class="block text-sm mb-1">Imagen</label>
      <input id="image" type="file" name="image" accept="image/*"
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        <i class="fas fa-info-circle mr-1"></i> 
        Puedes subir una imagen manualmente o dejarlo vacío para que la IA genere una automáticamente
      </p>
    </div>
    <div class="md:col-span-2">
      <label for="audio" class="block text-sm mb-1">Audio</label>
      <input id="audio" type="file" name="audio" accept="audio/*" required
             class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
      <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
        <i class="fas fa-microphone mr-1"></i> 
        El audio se procesará con IA para generar contenido automático si no se proporciona resumen o imagen
      </p>
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
<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/admin/worship/show-worship.blade.php ENDPATH**/ ?>