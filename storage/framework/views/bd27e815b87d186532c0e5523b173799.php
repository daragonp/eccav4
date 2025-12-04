<?php $__env->startSection('title', 'Información del culto dominical'); ?>

<?php
 $date = \Carbon\Carbon::parse($worship->broadcast);
 $dias = [
'domingo', 'lunes', 'martes', 'miércoles',
'jueves', 'viernes', 'sábado'
];
 $meses = [
1 => 'enero', 2 => 'febrero', 3 => 'marzo',
4 => 'abril', 5 => 'mayo', 6 => 'junio',
7 => 'julio', 8 => 'agosto', 9 => 'septiembre',
10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
];
 $nombreDia = $dias[$date->dayOfWeek];
 $nombreMes = $meses[$date->month];
 $textoFecha = ucfirst($nombreDia) . ', ' . $date->day . ' de ' . $nombreMes . ' de ' . $date->year;
?>

<?php $__env->startSection('pageheading', $worship->title . ' - ' . $textoFecha); ?>


<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple lightbox for the main image
        const imageModal = document.getElementById('imageModal');
        const modalImage = document.getElementById('modalImage');
        const worshipImage = document.getElementById('worshipImage');

        if (worshipImage) {
            worshipImage.addEventListener('click', function() {
                modalImage.src = this.src;
                imageModal.classList.add('open');
            });
        }

        // Close modal on backdrop click or close button
        if (imageModal) {
            imageModal.addEventListener('click', function(e) {
                if (e.target === imageModal || e.target.closest('[data-modal-close]')) {
                    imageModal.classList.remove('open');
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('datatable'); ?>
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    
    <div class="xl:col-span-2 space-y-6">
        
        <div class="card">
            <div class="card-body p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 dark:text-white"><?php echo e($worship->title); ?></h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                            ID: <?php echo e($worship->id); ?> • Fecha: <span class="font-mono text-xs"><?php echo e(\Carbon\Carbon::parse($worship->broadcast)->format('d/m/Y')); ?></span>
                        </p>
                    </div>
                    <div class="shrink-0">
                        <?php if($worship->deleted_at): ?>
                        <span class="chip-brand bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-300 dark:border-red-700">
                            <i class="fas fa-trash-alt mr-1"></i> Inactivo
                        </span>
                        <?php else: ?>
                        <span class="chip-brand bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-300 dark:border-green-700">
                            <i class="fas fa-check-circle mr-1"></i> Publicado
                        </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body p-6">
                <h2 class="text-lg font-semibold mb-3 text-slate-800 dark:text-slate-200">
                    <i class="fas fa-info-circle mr-2 text-brand-green"></i> Información General
                </h2>
                <div class="space-y-3">
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400 sm:w-1/4">Autor:</span>
                        <span class="text-sm text-slate-900 dark:text-slate-100"><?php echo e($worship->autor); ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row sm:items-center">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400 sm:w-1/4">Etiqueta:</span>
                        <span class="text-sm text-slate-900 dark:text-slate-100"><?php echo e($worship->badge); ?></span>
                    </div>
                    <div class="flex flex-col sm:flex-row">
                        <span class="text-sm font-medium text-slate-500 dark:text-slate-400 sm:w-1/4">Descripción:</span>
                        <p class="text-sm text-slate-900 dark:text-slate-100"><?php echo e($worship->abstract); ?></p>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body p-6">
                <h2 class="text-lg font-semibold mb-3 text-slate-800 dark:text-slate-200">
                    <i class="fas fa-image mr-2 text-brand-green"></i> Imagen del culto
                </h2>
                <?php if($worship->image): ?>
                <div class="rounded-lg overflow-hidden bg-slate-100 dark:bg-slate-800 cursor-pointer group" id="worshipImage">
                    <img src="<?php echo e(asset('images/worship/' . $worship->image)); ?>"
                        alt="Imagen del culto"
                        class="w-full h-auto object-cover transition-transform duration-300 group-hover:scale-105">
                </div>
                <?php if($worship->ai_image): ?>
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        <i class="fas fa-robot mr-1"></i> Esta imagen fue generada por IA basada en el contenido del audio
                    </p>
                </div>
                <?php endif; ?>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center">
                    <i class="fas fa-search-plus mr-1"></i> Haz clic en la imagen para verla en tamaño completo
                </p>
                <?php else: ?>
                <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                    <i class="fas fa-image text-4xl mb-2"></i>
                    <p>No hay imagen disponible</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body p-6">
                <h2 class="text-lg font-semibold mb-3 text-slate-800 dark:text-slate-200">
                    <i class="fas fa-file-pdf mr-2 text-red-600"></i> Documento PDF
                </h2>
                <?php if($worship->pdfdoc): ?>
                <div class="bg-slate-100 dark:bg-slate-800 rounded-lg p-4 text-center">
                    <i class="fas fa-file-pdf text-6xl text-red-500 mb-3"></i>
                    <p class="mb-4 text-slate-700 dark:text-slate-300">Documento adjunto</p>
                    <a href="<?php echo e(asset('documents/worship/' . $worship->pdfdoc)); ?>"
                        target="_blank"
                        class="btn btn-primary">
                        <i class="fas fa-external-link-alt mr-2"></i> Ver / Descargar PDF
                    </a>
                </div>

                
                <details class="mt-4">
                    <summary class="cursor-pointer text-sm font-medium text-slate-700 dark:text-slate-300 hover:text-brand-green flex items-center">
                        <i class="fas fa-eye mr-2"></i> Vista previa del documento
                    </summary>
                    <div class="mt-2 border rounded-lg overflow-hidden">
                        <iframe src="<?php echo e(asset('/laraview/#../documents/worship/' . $worship->pdfdoc)); ?>"
                            width="100%"
                            height="500px"
                            class="border-0">
                        </iframe>
                    </div>
                </details>
                <?php else: ?>
                <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                    <i class="fas fa-file-pdf text-4xl mb-2"></i>
                    <p>No hay documento PDF disponible</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <div class="card">
            <div class="card-body p-6">
                <h2 class="text-lg font-semibold mb-3 text-slate-800 dark:text-slate-200">
                    <i class="fas fa-podcast mr-2 text-brand-green"></i> Audio
                </h2>
                <?php if($worship->audio): ?>
                <div class="bg-slate-100 dark:bg-slate-800 rounded-lg p-4">
                    <audio controls class="w-full">
                        <source src="<?php echo e(asset('audio/worship/' . $worship->audio)); ?>" type="audio/mpeg">
                        Tu navegador no soporta este elemento de audio.
                    </audio>
                </div>
                <?php if($worship->ai_processed): ?>
                <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-300">
                        <i class="fas fa-robot mr-1"></i> Este audio ha sido procesado por IA
                    </p>
                </div>
                <?php endif; ?>
                <?php else: ?>
                <div class="text-center py-8 text-slate-500 dark:text-slate-400">
                    <i class="fas fa-podcast text-4xl mb-2"></i>
                    <p>No hay archivo de audio disponible</p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($worship->ai_summary): ?>
        <div class="card">
            <div class="card-body p-6">
                <h2 class="text-lg font-semibold mb-3 text-slate-800 dark:text-slate-200">
                    <i class="fas fa-robot mr-2 text-blue-600"></i> Resumen generado por IA
                </h2>
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <p class="text-sm text-slate-700 dark:text-slate-300"><?php echo e($worship->ai_summary); ?></p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($worship->video): ?>
        <div class="card">
            <div class="card-body p-6">
                <h2 class="text-lg font-semibold mb-3 text-slate-800 dark:text-slate-200">
                    <i class="fas fa-video mr-2 text-brand-green"></i> Video
                </h2>
                <div class="bg-slate-100 dark:bg-slate-800 rounded-lg p-4">
                    <video controls class="w-full">
                        <source src="<?php echo e(asset('video/worship/' . $worship->video)); ?>" type="video/mp4">
                        Tu navegador no soporta este elemento de video.
                    </video>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    
    <div class="xl:col-span-1">
        <div class="sticky top-20 space-y-6">
            
            <div class="card">
                <div class="card-body p-6">
                    <h2 class="text-lg font-semibold mb-4 text-slate-800 dark:text-slate-200">
                        <i class="fas fa-info-circle mr-2 text-brand-green"></i> Información
                    </h2>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Fecha:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100"><?php echo e(\Carbon\Carbon::parse($worship->broadcast)->format('d/m/Y')); ?></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Creado:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100"><?php echo e($worship->created_at->format('d/m/Y H:i')); ?></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Actualizado:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100"><?php echo e($worship->updated_at->format('d/m/Y H:i')); ?></dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-slate-500 dark:text-slate-400">Estado IA:</dt>
                            <dd class="text-sm text-slate-900 dark:text-slate-100">
                                <?php if(!$worship->audio): ?>
                                <span class="text-xs">Sin audio</span>
                                <?php elseif($worship->ai_processed): ?>
                                <span class="text-xs text-green-600">Procesado</span>
                                <?php else: ?>
                                <span class="text-xs text-yellow-600">Pendiente</span>
                                <?php endif; ?>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            
            <div class="card">
                <div class="card-body p-6">
                    <h2 class="text-lg font-semibold mb-4 text-slate-800 dark:text-slate-200">
                        <i class="fas fa-tools mr-2 text-brand-green"></i> Acciones
                    </h2>
                    <div class="space-y-3">
                        
                        <button class="btn btn-info w-full justify-center" data-modal-open="EditModal_<?php echo e($worship->id); ?>">
                            <i class="fas fa-edit mr-2"></i> Editar Culto
                        </button>

                        
                        <?php if($worship->audio && !$worship->ai_processed): ?>
                        <a href="<?php echo e(url('reprocess-worship-ai', $worship->id)); ?>" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-robot mr-2"></i> Procesar con IA
                        </a>
                        <?php endif; ?>

                        
                        <?php if($worship->deleted_at): ?>
                        <a href="<?php echo e(url('activate-worship', $worship->id)); ?>" class="btn btn-success w-full justify-center">
                            <i class="fas fa-toggle-on mr-2"></i> Activar
                        </a>
                        <?php else: ?>
                        <a href="<?php echo e(url('delete-worship', $worship->id)); ?>" class="btn btn-warning w-full justify-center">
                            <i class="fas fa-power-off mr-2"></i> Desactivar
                        </a>
                        <?php endif; ?>

<form action="<?php echo e(url('realdelete-worship', $worship->id)); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
    <button type="submit" class="btn btn-danger w-full justify-center">
        <i class="fas fa-trash-alt mr-2"></i> Eliminar Definitivamente
    </button>
</form>

                        <hr class="border-slate-200 dark:border-slate-700">

                        
                        <a href="<?php echo e(url('show-worship')); ?>" class="btn btn-secondary w-full justify-center">
                            <i class="fas fa-arrow-left mr-2"></i> Regresar a la Lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if($worship->image): ?>
<section id="imageModal" class="tw-modal" aria-modal="true" role="dialog" aria-hidden="true">
    <div class="tw-modal-panel max-w-5xl">
        <div class="tw-modal-header">
            <h3 class="text-lg font-semibold"><?php echo e($worship->title); ?> - <?php echo e(\Carbon\Carbon::parse($worship->broadcast)->format('d/m/Y')); ?></h3>
            <button data-modal-close class="btn btn-ghost" aria-label="Cerrar">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="tw-modal-body p-0">
            <img id="modalImage" src="" alt="Imagen del culto" class="w-full h-auto">
        </div>
    </div>
</section>
<?php endif; ?>


<?php echo $__env->make('admin.partials.universal-edit-modal', [
'modalId' => 'EditModal_' . $worship->id,
'formAction' => url('update-worship', $worship->id),
'tableM' => $worship,
'sectionType' => 'worship',
'sectionTitle' => 'Culto Dominical',
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/admin/worship/view-worship.blade.php ENDPATH**/ ?>