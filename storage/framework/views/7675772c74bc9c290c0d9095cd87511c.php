<?php $__env->startSection('title', 'Panel principal'); ?>
<?php $__env->startSection('pageheading', 'Panel principal'); ?>

<?php $__env->startSection('datatable'); ?>
<!-- Panel de control moderno -->
<div class="space-y-6">
    <!-- Resumen de estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
        <!-- Tarjeta de Usuarios -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 left-0 w-2 h-full bg-blue-500"></div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-xl">
                            <i class="fas fa-users text-lg text-blue-600 dark:text-blue-400"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Usuarios</h3>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-2xl font-bold text-slate-900 dark:text-white"><?php echo e($stats['users'] ?? 0); ?></span>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                    <i class="fas fa-arrow-up text-xs"></i> 12%
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo e(url('show-users')); ?>" class="p-2 text-slate-400 hover:text-blue-600 dark:text-slate-500 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="mt-4">
                    <div class="h-2 bg-blue-50 dark:bg-blue-900/20 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 rounded-full" style="width: 75%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400"><?php echo e($stats['users_change'] ?? '12 nuevos este mes'); ?></p>
                </div>
            </div>
        </div>
    
        <!-- Tarjeta de Versículos -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 left-0 w-2 h-full bg-green-500"></div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-xl">
                            <i class="fas fa-book-bible text-lg text-green-600 dark:text-green-400"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Versículos</h3>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-2xl font-bold text-slate-900 dark:text-white"><?php echo e($stats['verses'] ?? 0); ?></span>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                    <i class="fas fa-arrow-up text-xs"></i> 8%
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo e(url('show-quote')); ?>" class="p-2 text-slate-400 hover:text-green-600 dark:text-slate-500 dark:hover:text-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="mt-4">
                    <div class="h-2 bg-green-50 dark:bg-green-900/20 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 rounded-full" style="width: 65%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400"><?php echo e($stats['verses_change'] ?? '8 nuevos este mes'); ?></p>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Programación -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 left-0 w-2 h-full bg-purple-500"></div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                            <i class="fas fa-clock text-lg text-purple-600 dark:text-purple-400"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Programación</h3>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-2xl font-bold text-slate-900 dark:text-white">354</span>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                    <i class="fas fa-arrow-up text-xs"></i> 5%
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo e(url('show-schedule')); ?>" class="p-2 text-slate-400 hover:text-purple-600 dark:text-slate-500 dark:hover:text-purple-400 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="mt-4">
                    <div class="h-2 bg-purple-50 dark:bg-purple-900/20 rounded-full overflow-hidden">
                        <div class="h-full bg-purple-500 rounded-full" style="width: 85%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">5 nuevos este mes</p>
                </div>
            </div>
        </div>

        <!-- Tarjeta de Noticias -->
        <div class="stat-card relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 left-0 w-2 h-full bg-indigo-500"></div>
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl">
                            <i class="fas fa-newspaper text-lg text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-slate-500 dark:text-slate-400">Noticias</h3>
                            <div class="flex items-baseline space-x-2">
                                <span class="text-2xl font-bold text-slate-900 dark:text-white">27</span>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">
                                    <i class="fas fa-arrow-up text-xs"></i> 15%
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo e(url('show-news')); ?>" class="p-2 text-slate-400 hover:text-indigo-600 dark:text-slate-500 dark:hover:text-indigo-400 rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
                <div class="mt-4">
                    <div class="h-2 bg-indigo-50 dark:bg-indigo-900/20 rounded-full overflow-hidden">
                        <div class="h-full bg-indigo-500 rounded-full" style="width: 92%"></div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">15 nuevas este mes</p>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>
    
</div>

<!-- Sección de contenido principal -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Últimos versículos mejorados -->
    <div class="card group hover:shadow-lg transition-all duration-300">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                        <i class="fas fa-book-bible text-green-600 dark:text-green-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Últimos versículos</h3>
                </div>
                <a href="<?php echo e(url('show-quote')); ?>" class="chip-brand group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-book-bible mr-1"></i> Ver todo
                </a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="text-left text-sm border-b border-slate-200 dark:border-slate-700">
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Fecha</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Imagen</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">PDF</th>
                            <th class="pb-3 font-medium text-slate-700 dark:text-slate-300">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php $__empty_1 = true; $__currentLoopData = $latestVerses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $v): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="border-b border-slate-100 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors group/row">
                            <td class="py-3">
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-900 dark:text-slate-200"><?php echo e(\Carbon\Carbon::parse($v->date)->format('d/m')); ?></span>
                                    <span class="text-xs text-slate-500 dark:text-slate-400"><?php echo e(\Carbon\Carbon::parse($v->date)->format('Y')); ?></span>
                                </div>
                            </td>
                            <td class="py-3">
                                <?php if($v->image): ?>
                                    <div class="relative group/image">
                                        <img src="<?php echo e(asset('images/bible/'.$v->image)); ?>" alt="" class="w-10 h-10 rounded object-cover group-hover/image:scale-110 transition-transform duration-300">
                                        <div class="absolute inset-0 bg-black/50 rounded opacity-0 group-hover/image:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                            <i class="fas fa-search-plus text-white text-xs"></i>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="w-10 h-10 rounded bg-slate-200 dark:bg-slate-700 flex items-center justify-center group-hover/image:scale-110 transition-transform duration-300">
                                        <i class="fas fa-image text-slate-400 text-xs"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="py-3">
                                <?php if($v->video): ?>
                                    <a href="<?php echo e(asset('documents/quote/'.$v->video)); ?>" target="_blank" class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 rounded text-xs hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>PDF</span>
                                    </a>
                                <?php else: ?>
                                    <span class="text-slate-400 text-xs">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="py-3">
                                <span class="chip-success">
                                    <i class="fas fa-check-circle"></i>
                                    Activo
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="4" class="py-8 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <i class="fas fa-bible text-slate-300 dark:text-slate-600 text-2xl"></i>
                                    <span class="text-slate-500 dark:text-slate-400">Sin versículos registrados</span>
                                    <a href="<?php echo e(url('show-quote')); ?>" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Añadir primer versículo</a>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Últimas noticias mejoradas -->
    <div class="card group hover:shadow-lg transition-all duration-300">
        <div class="card-body">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-2">
                    <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                        <i class="fas fa-newspaper text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Últimas noticias</h3>
                </div>
                <a href="<?php echo e(url('show-news')); ?>" class="chip-brand group-hover:scale-105 transition-transform duration-300">
                    <i class="fas fa-newspaper mr-1"></i> Ver todo
                </a>
            </div>
            
            <div class="space-y-3">
                <?php $__empty_1 = true; $__currentLoopData = $latestNews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="group/item p-3 rounded-lg border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-all duration-300 cursor-pointer">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex-1">
                            <h4 class="font-medium text-slate-900 dark:text-white truncate group-hover/item:text-blue-600 dark:group-hover/item:text-blue-400 transition-colors">
                                <?php echo e($n->title); ?>

                            </h4>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                <i class="far fa-clock mr-1"></i>
                                <?php echo e($n->created_at->format('d/m/Y H:i')); ?>

                            </p>
                            <?php if($n->excerpt): ?>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-2 line-clamp-2">
                                    <?php echo e($n->excerpt); ?>

                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="flex flex-col gap-1">
                            <a href="<?php echo e(url('view-news/'.$n->id)); ?>" class="btn-action btn-action-info" title="Ver noticia">
                                <i class="fas fa-eye text-xs"></i>
                            </a>
                            <a href="<?php echo e(url('edit-news/'.$n->id)); ?>" class="btn-action btn-action-secondary" title="Editar noticia">
                                <i class="fas fa-edit text-xs"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="py-8 text-center">
                    <div class="flex flex-col items-center gap-2">
                        <i class="fas fa-newspaper text-slate-300 dark:text-slate-600 text-2xl"></i>
                        <span class="text-slate-500 dark:text-slate-400">Sin noticias publicadas</span>
                        <a href="<?php echo e(url('show-news')); ?>" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">Publicar primera noticia</a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ========================================
    // Manejador para botones de acciones rápidas
    // ========================================
    document.querySelectorAll('.quick-action-btn').forEach(button => {
        button.addEventListener('click', function() {
            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });
    });
    
    // ========================================
    // Animación de entrada para las tarjetas
    // ========================================
    const cards = document.querySelectorAll('.card-stats-users, .card-stats-verses, .card-stats-schedules, .card-stats-banners, .card-stats-news');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
    
    // ========================================
    // Efecto de actualización automática de estadísticas
    // ========================================
    const updateStats = () => {
        const statNumbers = document.querySelectorAll('.text-3xl');
        statNumbers.forEach(stat => {
            const finalValue = stat.textContent;
            const numValue = parseInt(finalValue.replace(/[^0-9]/g, '')) || 0;
            let currentValue = 0;
            const increment = numValue / 30;
            
            const updateNumber = () => {
                if (currentValue < numValue) {
                    currentValue += increment;
                    stat.textContent = Math.floor(currentValue).toLocaleString();
                    requestAnimationFrame(updateNumber);
                } else {
                    stat.textContent = finalValue;
                }
            };
            
            updateNumber();
        });
    };
    
    // Iniciar animación de números
    setTimeout(updateStats, 500);
    
    // ========================================
    // Refrescar datos cada 30 segundos
    // ========================================
    setInterval(() => {
        // Aquí podrías hacer una llamada AJAX para actualizar los datos
        console.log('Actualizando datos del dashboard...');
    }, 30000);
    
    // ========================================
    // Efecto hover en las filas de las tablas
    // ========================================
    const tableRows = document.querySelectorAll('tbody tr');
    tableRows.forEach(row => {
        row.addEventListener('mouseenter', function() {
            this.style.transform = 'translateX(5px)';
        });
        
        row.addEventListener('mouseleave', function() {
            this.style.transform = 'translateX(0)';
        });
    });
    
    // ========================================
    // Inicializar tooltips si es necesario
    // ========================================
    const initTooltips = () => {
        const tooltipElements = document.querySelectorAll('[title]');
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function() {
                // Lógica para mostrar tooltips
            });
        });
    };
    
    initTooltips();
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.panel', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>