<?php $__env->startSection('title', 'Roles de usuarios'); ?>
<?php $__env->startSection('pageheading', 'Roles'); ?>


<?php $__env->startSection('addbutton'); ?>
  <i class="fa-solid fa-plus-circle"></i> Nuevo rol
<?php $__env->stopSection(); ?>


<?php $__env->startSection('modalTitle','Crear nuevo rol'); ?>
<?php $__env->startSection('formaction', url('/addrole')); ?>
<?php $__env->startSection('modalFields'); ?>
  <div class="grid grid-cols-1 gap-4">
    <div>
      <label for="name" class="block text-sm font-medium mb-1">Nombre del rol</label>
      <input id="name" name="name" type="text" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" placeholder="Por ejemplo, Administrador" required>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('datatable'); ?>
  
  <div class="card mb-6">
    <div class="card-body p-4">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center">
          <i class="fas fa-user-shield text-brand-green"></i>
        </div>
        <div>
          <h3 class="font-semibold text-slate-900 dark:text-white">Roles de Usuario</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
            Administra los roles del sistema y sus permisos asociados. Usa el botón "Nuevo rol" para crear nuevos perfiles de acceso o las acciones por fila para editar o eliminar roles existentes.
          </p>
        </div>
      </div>
    </div>
  </div>

  
  <div class="card">
    <div class="card-body p-0">
      <div class="table-wrap">
        <?php echo $dataTable->table(['class' => 'w-full'], true); ?>

      </div>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
  
  <?php echo $dataTable->scripts(); ?>

  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Aplicar estilos a los controles de DataTables después de que se cargue
      setTimeout(function() {
        // Asumimos que el ID de la tabla es 'roles-table'
        const lengthSelect = document.querySelector('#roles-table_length select');
        const filterInput = document.querySelector('#roles-table_filter input');
        
        if (lengthSelect) {
          lengthSelect.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-2 py-1 text-sm';
        }
        
        if (filterInput) {
          filterInput.className = 'rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2 text-sm';
          filterInput.placeholder = 'Buscar...';
        }
      }, 100);
    });
  </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/admin/role/show-role.blade.php ENDPATH**/ ?>