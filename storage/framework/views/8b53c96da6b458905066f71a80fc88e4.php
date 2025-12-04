<?php
  // Requeridos para el modal de edición
  $modalId    = $modalId    ?? ('EditModal_'.$id);
  $formAction = $formAction ?? '#';
  $editPartial= $editPartial?? null;
  $sectionType = $sectionType ?? 'news';
  $sectionTitle = $sectionTitle ?? 'Elemento';
  // Cambiar de deleted_at a active
  $isActive = isset($tableM) && is_null($tableM->deleted_at);
?>

<div class="flex items-center justify-center gap-1.5">

  
  <a href="<?php echo e($view ?? '#'); ?>" 
     class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-900/20 dark:text-blue-400 dark:hover:bg-blue-900/30 transition-all duration-200 group" 
     title="Ver detalles">
    <i class="fa-solid fa-eye text-sm group-hover:scale-110 transition-transform"></i> 
  </a>
<p>|</p>
  
  <?php if(!$isActive): ?>
    <a href="<?php echo e($activate ?? '#'); ?>" 
       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-100 dark:bg-emerald-900/20 dark:text-emerald-400 dark:hover:bg-emerald-900/30 transition-all duration-200 group" 
       title="Activar versículo">
      <i class="fa-solid fa-toggle-off text-sm group-hover:scale-110 transition-transform"></i>
    </a>
  <?php else: ?>
    <a href="<?php echo e($softdelete ?? '#'); ?>" 
       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-100 dark:bg-amber-900/20 dark:text-amber-400 dark:hover:bg-amber-900/30 transition-all duration-200 group" 
       title="Desactivar versículo">
      <i class="fa-solid fa-toggle-on text-sm group-hover:scale-110 transition-transform"></i>
    </a>
  <?php endif; ?>
  
<?php if(isset($reprocess) && $tableM->audio && !$tableM->ai_processed): ?>
<a href="<?php echo e($reprocess); ?>" class="btn btn-sm btn-action btn-action-secondary" title="Procesar con IA">
    <i class="fas fa-robot"></i>
</a>
<?php endif; ?>
</div>


<?php echo $__env->make('admin.partials.universal-edit-modal', [
    'modalId' => $modalId,
    'formAction' => $formAction,
    'tableM' => $tableM ?? null,
    'sectionType' => $sectionType,
    'sectionTitle' => $sectionTitle,
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/admin/partials/actions.blade.php ENDPATH**/ ?>