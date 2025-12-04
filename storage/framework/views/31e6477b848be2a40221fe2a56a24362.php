<?php $__env->startSection('title', 'Usuarios'); ?>
<?php $__env->startSection('pageheading', 'Usuarios'); ?>


<?php $__env->startSection('addbutton'); ?>
  <i class="fa-solid fa-plus-circle"></i> Nuevo usuario
<?php $__env->stopSection(); ?>


<?php $__env->startSection('modalTitle','Crear nuevo usuario'); ?>
<?php $__env->startSection('formaction', url('/adduser')); ?>
<?php $__env->startSection('modalFields'); ?>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
      <label for="name" class="block text-sm font-medium mb-1">Nombre completo</label>
      <input id="name" name="name" type="text" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" placeholder="Juan Pérez" required>
    </div>
    <div>
      <label for="email" class="block text-sm font-medium mb-1">Correo electrónico</label>
      <input id="email" name="email" type="email" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" placeholder="correo@ejemplo.com" required>
    </div>
    <div>
      <label for="password" class="block text-sm font-medium mb-1">Contraseña</label>
      <input id="password" name="password" type="password" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="password_confirmation" class="block text-sm font-medium mb-1">Confirmar contraseña</label>
      <input id="password_confirmation" name="password_confirmation" type="password" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
    </div>
    <div>
      <label for="role_id" class="block text-sm font-medium mb-1">Rol</label>
      <select id="role_id" name="role_id" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2" required>
        <option value="">Seleccionar rol</option>
        <?php if(isset($roles)): ?>
          <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($role->id); ?>"><?php echo e($role->name); ?></option>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
      </select>
    </div>
    <div>
      <label for="status" class="block text-sm font-medium mb-1">Estado</label>
      <select id="status" name="status" class="w-full rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2">
        <option value="1">Activo</option>
        <option value="0">Inactivo</option>
      </select>
    </div>
  </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('datatable'); ?>
  
  <div class="card mb-6">
    <div class="card-body p-4">
      <div class="flex items-start gap-3">
        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-brand-green/10 flex items-center justify-center">
          <i class="fas fa-users text-brand-green"></i>
        </div>
        <div>
          <h3 class="font-semibold text-slate-900 dark:text-white">Usuarios del Sistema</h3>
          <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">
            Administra las cuentas de usuario del sistema. Usa el botón "Nuevo usuario" para crear nuevas cuentas o las acciones por fila para ver, editar o eliminar usuarios existentes.
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
        // Asumimos que el ID de la tabla es 'users-table'
        const lengthSelect = document.querySelector('#users-table_length select');
        const filterInput = document.querySelector('#users-table_filter input');
        
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
<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/admin/user/show-user.blade.php ENDPATH**/ ?>