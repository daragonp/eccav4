<?php
  $type = $type ?? 'image';
  $alt  = $alt  ?? 'media';
?>

<?php if($type === 'image'): ?>
  <a href="<?php echo e(asset($url)); ?>" target="_blank" class="inline-flex">
    <img src="<?php echo e(asset($url)); ?>"
         alt="<?php echo e($alt); ?>"
         class="h-12 w-12 object-cover rounded-md ring-1 ring-slate-200 dark:ring-slate-800" />
  </a>
<?php elseif($type === 'audio'): ?>
  <audio controls class="w-40">
    <source src="<?php echo e(asset($url)); ?>">
    Tu navegador no soporta audio HTML5.
  </audio>
<?php else: ?>
  <a href="<?php echo e(asset($url)); ?>" target="_blank" class="text-[var(--green-dark)] underline">Ver archivo</a>
<?php endif; ?>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/admin/partials/media.blade.php ENDPATH**/ ?>