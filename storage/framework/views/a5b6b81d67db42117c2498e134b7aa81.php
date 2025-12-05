<?php $__env->startSection('title', $worship->title . ' - Emancipación Cristiana Afro'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto">
    <!-- Navegación de migas de pan -->
    <nav class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-6">
        <a href="<?php echo e(route('worship.public.index')); ?>" class="hover:text-green-600 dark:hover:text-green-400 transition-colors">
            <i class="fas fa-home mr-2"></i>Cultos
        </a>
        <i class="fas fa-chevron-right mx-2 text-xs"></i>
        <span class="text-gray-700 dark:text-gray-300"><?php echo e($worship->title); ?></span>
    </nav>
    
    <!-- Encabezado del artículo -->
    <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm overflow-hidden border border-gray-100 dark:border-gray-700">
        <!-- Imagen principal a todo el ancho -->
        <div class="relative h-64 md:h-96 overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->image): ?>
                <img src="<?php echo e(asset('images/worship/' . $worship->image)); ?>" 
                     alt="<?php echo e($worship->title); ?>" 
                     class="w-full h-full object-cover">
            <?php else: ?>
                <div class="w-full h-full bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center">
                    <i class="fas fa-church text-white/30 text-8xl"></i>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <!-- Overlay con información -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            
            <!-- Badge y fecha -->
            <div class="absolute bottom-6 left-6 right-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->badge): ?>
                    <span class="inline-block bg-green-600 text-white text-xs font-semibold px-3 py-1.5 rounded-full mb-3">
                        <?php echo e($worship->badge); ?>

                    </span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2"><?php echo e($worship->title); ?></h1>
                
                <div class="flex flex-wrap items-center gap-4 text-white/90 text-sm">
                    <span class="flex items-center gap-2">
                        <i class="fas fa-calendar"></i>
                        <?php echo e(\Carbon\Carbon::parse($worship->broadcast)->format('d \d\e F \d\e Y')); ?>

                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-user"></i>
                        <?php echo e($worship->autor); ?>

                    </span>
                    <span class="flex items-center gap-2">
                        <i class="fas fa-clock"></i>
                        <?php echo e(\Carbon\Carbon::parse($worship->broadcast)->diffForHumans()); ?>

                    </span>
                </div>
            </div>
        </div>
        
        <!-- Contenido del artículo -->
        <div class="p-6 md:p-10">
            <!-- Resumen -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->abstract): ?>
                <div class="mb-8">
                    <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-600 p-6 rounded-r-lg">
                        <h2 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-3 flex items-center gap-2">
                            <i class="fas fa-quote-left"></i>
                            Resumen del mensaje
                        </h2>
                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            <?php echo $worship->abstract; ?>

                        </div>
                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            
            <!-- Sección de multimedia -->
            <div class="space-y-8 mb-8">
                <!-- Audio -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->audio): ?>
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-headphones text-green-600 dark:text-green-400"></i>
                            Escuchar el audio completo
                        </h3>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <audio controls class="w-full">
                                <source src="<?php echo e(asset('audio/worship/' . $worship->audio)); ?>" type="audio/mpeg">
                                Tu navegador no soporta el elemento de audio.
                            </audio>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- Video -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->video): ?>
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-video text-green-600 dark:text-green-400"></i>
                            Ver el video completo
                        </h3>
                        <div class="bg-black rounded-lg overflow-hidden shadow-sm">
                            <video controls class="w-full" poster="<?php echo e($worship->image ? asset('images/worship/' . $worship->image) : ''); ?>">
                                <source src="<?php echo e(asset('video/worship/' . $worship->video)); ?>" type="video/mp4">
                                Tu navegador no soporta el elemento de video.
                            </video>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                
                <!-- PDF -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->pdfdoc): ?>
                    <div class="bg-gray-50 dark:bg-gray-900/30 rounded-xl p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                            <i class="fas fa-file-pdf text-red-600"></i>
                            Descargar el documento
                        </h3>
                        <a href="<?php echo e(asset('documents/worship/' . $worship->pdfdoc)); ?>" 
                           target="_blank"
                           class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                            <i class="fas fa-download"></i>
                            Descargar PDF
                        </a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </article>
    
    <!-- Navegación entre artículos -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($previous): ?>
            <a href="<?php echo e(route('worship.public.show', $previous->slug)); ?>" 
               class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-green-100 dark:group-hover:bg-green-900/30 transition-colors">
                        <i class="fas fa-chevron-left text-gray-600 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Anterior</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2">
                            <?php echo e($previous->title); ?>

                        </h3>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <?php echo e(\Carbon\Carbon::parse($previous->broadcast)->format('d/m/Y')); ?>

                        </div>
                    </div>
                </div>
            </a>
        <?php else: ?>
            <div></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($next): ?>
            <a href="<?php echo e(route('worship.public.show', $next->slug)); ?>" 
               class="group bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700 text-right">
                <div class="flex items-start gap-4 justify-end">
                    <div class="flex-1">
                        <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">Siguiente</div>
                        <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2">
                            <?php echo e($next->title); ?>

                        </h3>
                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            <?php echo e(\Carbon\Carbon::parse($next->broadcast)->format('d/m/Y')); ?>

                        </div>
                    </div>
                    <div class="flex-shrink-0 w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center group-hover:bg-green-100 dark:group-hover:bg-green-900/30 transition-colors">
                        <i class="fas fa-chevron-right text-gray-600 dark:text-gray-400 group-hover:text-green-600 dark:group-hover:text-green-400"></i>
                    </div>
                </div>
            </a>
        <?php else: ?>
            <div></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    
    <!-- Sección de relacionados -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($relatedWorships->isNotEmpty()): ?>
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Cultos relacionados</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $relatedWorships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('worship.public.show', $related->slug)); ?>" 
                       class="group bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 dark:border-gray-700">
                        <div class="h-32 overflow-hidden">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($related->image): ?>
                                <img src="<?php echo e(asset('images/worship/' . $related->image)); ?>" 
                                     alt="<?php echo e($related->title); ?>" 
                                     class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-green-700 to-green-500 flex items-center justify-center">
                                    <i class="fas fa-church text-white/30 text-3xl"></i>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 dark:text-white group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors line-clamp-2 mb-2">
                                <?php echo e($related->title); ?>

                            </h3>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                <?php echo e(\Carbon\Carbon::parse($related->broadcast)->format('d/m/Y')); ?>

                            </div>
                        </div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/public/worships/show.blade.php ENDPATH**/ ?>