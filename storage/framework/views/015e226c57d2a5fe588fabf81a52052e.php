<?php
    // Obtener los 4 cultos más recientes (cambiado de 3 a 4)
    $latestWorships = App\Models\Worship::orderBy('broadcast', 'desc')
        ->whereNull('deleted_at')
        ->take(4)  // Cambiado a 4 para mostrar más elementos
        ->get();
        
    // Determinar cuál es el más reciente para mostrar el badge "NUEVO"
    $isNew = function($worship) use ($latestWorships) {
        if ($latestWorships->isEmpty()) return false;
        
        $mostRecent = $latestWorships->first();
        $daysDiff = now()->diffInDays(\Carbon\Carbon::parse($mostRecent->broadcast));
        
        // Mostrar badge "NUEVO" si el culto más reciente es de menos de 7 días
        return $worship->id === $mostRecent->id && $daysDiff <= 7;
    };
?>

<!-- Sección de últimos cultos (con manejo de estado vacío) -->
<section class="py-8 bg-gradient-to-r from-green-50 to-emerald-50 dark:from-gray-900 dark:to-gray-800 border-y border-green-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Encabezado ultra compacto -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-green-600 to-green-700 rounded-lg flex items-center justify-center shadow-lg">
                    <i class="fas fa-church text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Últimos Mensajes</h2>
                    <p class="text-xs text-gray-600 dark:text-gray-400">Inspírate con la palabra reciente</p>
                </div>
            </div>
            
            <a href="<?php echo e(route('worship.public.index')); ?>" 
               class="group flex items-center gap-1 text-sm font-medium text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 transition-colors">
                <span>Ver todos</span>
                <i class="fas fa-arrow-right text-xs transition-transform group-hover:translate-x-1"></i>
            </a>
        </div>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($latestWorships->isNotEmpty()): ?>
            <!-- Carrusel de tarjetas compactas -->
            <div class="relative" x-data="carouselComponent()">
                <!-- Contenedor con scroll horizontal -->
                <div class="flex gap-4 overflow-x-auto pb-2 scrollbar-hide scroll-smooth snap-x snap-mandatory" 
                     x-ref="carousel"
                     x-on:scroll="updateScrollPosition()"
                     style="scrollbar-width: none; -ms-overflow-style: none;">
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $latestWorships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $worship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <article class="flex-none w-72 snap-start group">
                            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 dark:border-gray-700 h-32 flex">
                                <!-- Imagen miniatura -->
                                <div class="w-28 h-32 relative overflow-hidden flex-shrink-0">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->image): ?>
                                        <img src="<?php echo e(asset('images/worship/' . $worship->image)); ?>" 
                                             alt="<?php echo e($worship->title); ?>" 
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110">
                                    <?php else: ?>
                                        <div class="w-full h-full bg-gradient-to-br from-green-600 to-green-700 flex items-center justify-center">
                                            <i class="fas fa-church text-white/40 text-2xl"></i>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <!-- Badge NUEVO -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isNew($worship)): ?>
                                        <div class="absolute top-2 right-2">
                                            <span class="relative inline-flex items-center">
                                                <span class="absolute -inset-1 bg-red-500 rounded-full animate-ping opacity-75"></span>
                                                <span class="relative bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                                    NUEVO
                                                </span>
                                            </span>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <!-- Badge de fecha -->
                                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/70 to-transparent p-2">
                                        <div class="text-white text-xs font-medium">
                                            <?php echo e(\Carbon\Carbon::parse($worship->broadcast)->format('d/m')); ?>

                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Contenido compacto -->
                                <div class="flex-1 p-3 flex flex-col justify-between">
                                    <!-- Título y badge -->
                                    <div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->badge): ?>
                                            <span class="inline-block bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 text-xs font-semibold px-1.5 py-0.5 rounded mb-1">
                                                <?php echo e($worship->badge); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        
                                        <h3 class="font-semibold text-sm text-gray-900 dark:text-white line-clamp-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                            <a href="<?php echo e(route('worship.public.show', $worship->slug)); ?>" class="hover:underline">
                                                <?php echo e($worship->title); ?>

                                            </a>
                                        </h3>
                                    </div>
                                    
                                    <!-- Resumen ultra corto -->
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->abstract): ?>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-1 mb-2">
                                            <?php echo e(Str::words(strip_tags($worship->abstract), 7)); ?>...
                                        </p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    
                                    <!-- Meta info y botón -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1">
                                                <i class="fas fa-user text-[10px]"></i>
                                                <?php echo e(Str::limit($worship->autor, 10)); ?>

                                            </span>
                                            
                                            <!-- Iconos de multimedia -->
                                            <div class="flex items-center gap-1">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->audio): ?>
                                                    <i class="fas fa-headphones text-[10px]"></i>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($worship->video): ?>
                                                    <i class="fas fa-video text-[10px]"></i>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                        
                                        <a href="<?php echo e(route('worship.public.show', $worship->slug)); ?>" 
                                           class="inline-flex items-center justify-center w-6 h-6 bg-green-600 hover:bg-green-700 text-white rounded-full transition-all duration-200 transform hover:scale-110">
                                            <i class="fas fa-play text-[8px] ml-0.5"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                    
                    <!-- Espaciador final para mejor alineación -->
                    <div class="w-4 flex-shrink-0"></div>
                </div>
                
                <!-- Indicadores de scroll (solo visibles en desktop) -->
                <div class="hidden md:flex justify-center gap-2 mt-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::openLoop(); ?><?php endif; ?><?php $__currentLoopData = $latestWorships; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $worship): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::startLoop($loop->index); ?><?php endif; ?>
                        <button class="carousel-dot w-2 h-2 rounded-full bg-green-200 dark:bg-green-800 transition-all duration-300"
                                :class="currentSlide === <?php echo e($key); ?> ? 'bg-green-600 dark:bg-green-400 w-8' : ''"
                                @click="goToSlide(<?php echo e($key); ?>)">
                        </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::endLoop(); ?><?php endif; ?><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::closeLoop(); ?><?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <!-- Estado vacío atractivo -->
            <div class="text-center py-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-800 rounded-full mb-4">
                    <i class="fas fa-bible text-gray-400 text-2xl"></i>
                </div>
                
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                    Próximamente nuevos mensajes
                </h3>
                
                <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                    Estamos preparando contenido inspirador para ti. Mientras tanto, explora otros recursos espirituales en nuestra web.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <a href="<?php echo e(route('worship.public.index')); ?>" 
                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors">
                        <i class="fas fa-search"></i>
                        Explorar archivos
                    </a>
                </div>
                
                <!-- Mensaje para administradores (solo visible si está logueado) -->
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->check() && auth()->user()->hasRole(['admin', 'editor'])): ?>
                    <div class="mt-6 p-3 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                        <p class="text-xs text-blue-700 dark:text-blue-400">
                            <i class="fas fa-info-circle mr-1"></i>
                            Como administrador, puedes 
                            <a href="<?php echo e(url('/admin/worship/create')); ?>" class="underline font-medium">agregar nuevos cultos</a> 
                            desde el panel de administración.
                        </p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
</section>

<script>
function carouselComponent() {
    return {
        currentSlide: 0,
        scrollPosition: 0,
        itemWidth: 288, // Ancho de cada tarjeta + gap
        gapWidth: 16,   // Espacio entre tarjetas
        
        init() {
            // Calcular el ancho real de cada elemento
            this.$nextTick(() => {
                const firstItem = this.$refs.carousel.querySelector('.flex-none');
                if (firstItem) {
                    this.itemWidth = firstItem.offsetWidth + this.gapWidth;
                }
                this.updateCurrentSlide();
            });
        },
        
        updateScrollPosition() {
            this.scrollPosition = this.$refs.carousel.scrollLeft;
            this.updateCurrentSlide();
        },
        
        updateCurrentSlide() {
            // Calcular qué diapositiva está actualmente visible
            const slideIndex = Math.round(this.scrollPosition / this.itemWidth);
            this.currentSlide = Math.max(0, Math.min(slideIndex, this.$refs.carousel.querySelectorAll('.flex-none').length - 1));
        },
        
        goToSlide(index) {
            const targetPosition = index * this.itemWidth;
            this.$refs.carousel.scrollTo({
                left: targetPosition,
                behavior: 'smooth'
            });
        }
    }
}
</script>

<style>
/* Estilos para el carrusel */
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}

.carousel-dot {
    transition: all 0.3s ease;
}

.carousel-dot:hover {
    background-color: rgb(34 197 94 / 0.5);
}

.dark .carousel-dot:hover {
    background-color: rgb(74 222 128 / 0.5);
}
</style><?php /**PATH /Library/Proyectos/eccav4/resources/views/components/latest-worships.blade.php ENDPATH**/ ?>