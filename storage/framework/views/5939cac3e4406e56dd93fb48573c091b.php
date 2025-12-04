<?php $__env->startSection('title', 'Cultos Dominicales - Emancipación Cristiana Afro'); ?>

<?php $__env->startSection('page-hero'); ?>
<div class="relative bg-gradient-to-br from-green-900 via-green-800 to-green-700 text-white py-16 px-4 mb-10 overflow-hidden">
    <!-- Elemento decorativo de fondo -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-64 h-64 bg-white rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-green-300 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="relative max-w-4xl mx-auto text-center">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mb-6">
            <i class="fas fa-church text-4xl"></i>
        </div>
        <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Cultos Dominicales</h1>
        <p class="text-xl text-green-100 max-w-2xl mx-auto">
            Encuentra inspiración y guía espiritual en las enseñanzas de nuestros cultos dominicales
        </p>
        
        <!-- Estadísticas -->
        <div class="flex justify-center gap-8 mt-8">
            <div class="text-center">
                <div class="text-3xl font-bold"><?php echo e($worships->total()); ?></div>
                <div class="text-green-200 text-sm">Cultos disponibles</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold"><?php echo e($worships->count()); ?></div>
                <div class="text-green-200 text-sm">En esta página</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto">
    <!-- Filtros y búsqueda (opcional) -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center gap-2 text-gray-600 dark:text-gray-400">
                <i class="fas fa-filter"></i>
                <span class="text-sm">Mostrando los cultos más recientes</span>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Vista de cuadrícula/activa -->
                <button class="p-2 rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                    <i class="fas fa-th-large"></i>
                </button>
                <button class="p-2 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Grid de artículos tipo blog -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $worships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $worship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700">
                <!-- Imagen con efecto hover -->
                <div class="relative h-48 overflow-hidden">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->image): ?>
                        <img src="<?php echo e(asset('images/worship/' . $worship->image)); ?>" 
                             alt="<?php echo e($worship->title); ?>" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    <?php else: ?>
                        <div class="w-full h-full bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center">
                            <i class="fas fa-church text-white/50 text-6xl"></i>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <!-- Badge de categoría -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->badge): ?>
                        <div class="absolute top-4 left-4">
                            <span class="bg-green-600/90 backdrop-blur-sm text-white text-xs font-semibold px-3 py-1.5 rounded-full">
                                <?php echo e($worship->badge); ?>

                            </span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <!-- Overlay con fecha -->
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-4">
                        <div class="text-white text-sm font-medium">
                            <?php echo e(\Carbon\Carbon::parse($worship->broadcast)->format('d \d\e F \d\e Y')); ?>

                        </div>
                    </div>
                </div>
                
                <!-- Contenido -->
                <div class="p-6">
                    <!-- Meta información -->
                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-3">
                        <span class="flex items-center gap-1">
                            <i class="fas fa-user text-xs"></i>
                            <?php echo e($worship->autor); ?>

                        </span>
                        <span class="flex items-center gap-1">
                            <i class="fas fa-clock text-xs"></i>
                            <?php echo e(\Carbon\Carbon::parse($worship->broadcast)->diffForHumans()); ?>

                        </span>
                    </div>
                    
                    <!-- Título -->
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2">
                        <a href="<?php echo e(route('worship.public.show', $worship->slug)); ?>" class="hover:underline">
                            <?php echo e($worship->title); ?>

                        </a>
                    </h3>
                    
                    <!-- Extracto -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->abstract): ?>
                        <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3 text-sm leading-relaxed">
                            <?php echo e(strip_tags($worship->abstract)); ?>

                        </p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    
                    <!-- Iconos de multimedia -->
                    <div class="flex items-center gap-3 mb-4">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->audio): ?>
                            <span class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-headphones"></i>
                                Audio
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->video): ?>
                            <span class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-video"></i>
                                Video
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->pdfdoc): ?>
                            <span class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400">
                                <i class="fas fa-file-pdf"></i>
                                PDF
                            </span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    
                    <!-- Botón de lectura -->
                    <a href="<?php echo e(route('worship.public.show', $worship->slug)); ?>" 
                       class="inline-flex items-center gap-2 text-green-600 dark:text-green-400 font-medium text-sm hover:text-green-700 dark:hover:text-green-300 transition-colors">
                        Continuar leyendo
                        <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </article>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-full text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                    <i class="fas fa-church text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No hay cultos disponibles</h3>
                <p class="text-gray-600 dark:text-gray-400">Pronto estarán disponibles nuevos cultos dominicales.</p>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    
    <!-- Paginación mejorada -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worships->hasPages()): ?>
        <div class="mt-12 flex justify-center">
            <?php echo e($worships->links('pagination::tailwind')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/public/worships/index.blade.php ENDPATH**/ ?>