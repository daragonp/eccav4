<?php
// Normaliza banners - Versión mejorada
$slides = collect($slider ?? [])->map(function ($b) {
    if (!$b->image_left || !$b->image_right) {
        return null;
    }

    return [
        'left' => [
            'type' => $b->left_media_type,
            'src' => $b->left_media_src,
            'mime' => $b->left_media_mime,
        ],
        'right' => [
            'type' => $b->right_media_type,
            'src' => $b->right_media_src,
            'mime' => $b->right_media_mime,
        ],
        'id' => $b->id,
    ];
})->filter()->values();

// Si no hay slides, mostrar placeholders
if ($slides->isEmpty()) {
    $slides = collect([
        [
            'left' => ['type' => 'image', 'src' => asset('images/slider/izq_placeholder.png'), 'mime' => null],
            'right' => ['type' => 'image', 'src' => asset('images/slider/der_placeholder.png'), 'mime' => null],
        ],
    ]);
}

// Wrapper interior: 95% del viewport si 'narrow'; si no, ancho usual limitado.
$inner = !empty($narrow)
    ? 'mx-auto w-[95dvw] max-w-7xl'
    : 'mx-auto max-w-7xl';

?>

<section
    x-data='{
        index: 0,
        slides: <?php echo json_encode($slides, 15, 512) ?>,
        timer: null,
        interval: 60000,
        init() { if (this.slides.length) { this.start(); } },
        start() { this.stop(); this.timer = setInterval(() => this.next(), this.interval); },
        stop() { if (this.timer) { clearInterval(this.timer); this.timer = null; } },
        next() { this.index = (this.index + 1) % this.slides.length; },
        prev() { this.index = (this.index - 1 + this.slides.length) % this.slides.length; },
        go(i) { this.index = i % this.slides.length; this.start(); },
        youtubeEmbed(url) {
            const match = url.match(/(?:youtube(?:-nocookie)?\.com\/(?:.*[?&]v=|embed\/|v\/)|youtu\.be\/)([A-Za-z0-9_-]{11})/);
            return match ? `https://www.youtube.com/embed/${match[1]}` : url;
        }
    }'
    x-init="init()"
    x-cloak
    class="relative full-bleed px-none select-none"
    @mouseenter="stop"
    @mouseleave="start"
    @keydown.arrow-right.prevent="next()"
    @keydown.arrow-left.prevent="prev()"
    tabindex="0"
    role="region"
    aria-roledescription="carrusel"
    aria-label="Banners promocionales"
    aria-live="polite">

    <div class="<?php echo e($inner); ?>">
        <div class="overflow-hidden bg-white dark:bg-gray-800">
            <template x-for="(slide, i) in slides" :key="i">
                <div x-show="index === i" x-transition.opacity class="grid grid-cols-1 md:grid-cols-2 gap-2 p-2">
                    <div class="text-center p-2">
                        <template x-if="slide.left.type === 'image'">
                            <div class="mx-auto w-full h-72 sm:h-[360px] md:h-[420px] overflow-hidden rounded shadow">
                                <img :src="slide.left.src"
                                    class="w-full h-full object-cover"
                                    :alt="`Banner izquierdo ${i+1}`"
                                    loading="lazy"
                                    decoding="async"
                                    draggable="false"
                                    onerror="console.error('Error cargando imagen:', this.src)">
                            </div>
                        </template>
                        <template x-if="slide.left.type === 'video'">
                            <div class="mx-auto w-full h-72 sm:h-[360px] md:h-[420px] overflow-hidden rounded shadow">
                                <video controls playsinline preload="metadata" :src="slide.left.src" class="w-full h-full min-h-full min-w-full object-cover object-center">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        </template>
                        <template x-if="slide.left.type === 'youtube'">
                            <div class="mx-auto w-full h-72 sm:h-[360px] md:h-[420px] overflow-hidden rounded shadow">
                                <iframe :src="youtubeEmbed(slide.left.src)"
                                    class="w-full h-full"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        </template>
                    </div>
                    <div class="text-center p-2">
                        <template x-if="slide.right.type === 'image'">
                            <div class="mx-auto w-full h-72 sm:h-[360px] md:h-[420px] overflow-hidden rounded shadow">
                                <img :src="slide.right.src"
                                    class="w-full h-full object-cover"
                                    :alt="`Banner derecho ${i+1}`"
                                    loading="lazy"
                                    decoding="async"
                                    draggable="false"
                                    onerror="console.error('Error cargando imagen:', this.src)">
                            </div>
                        </template>
                        <template x-if="slide.right.type === 'video'">
                            <div class="mx-auto w-full h-72 sm:h-[360px] md:h-[420px] overflow-hidden rounded shadow">
                                <video controls playsinline preload="metadata" :src="slide.right.src" class="w-full h-full min-h-full min-w-full object-cover object-center">
                                    Tu navegador no soporta el elemento de video.
                                </video>
                            </div>
                        </template>
                        <template x-if="slide.right.type === 'youtube'">
                            <div class="mx-auto w-full h-72 sm:h-[360px] md:h-[420px] overflow-hidden rounded shadow">
                                <iframe :src="youtubeEmbed(slide.right.src)"
                                    class="w-full h-full"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
        </div>
    </div>

    <!-- Botones de navegación -->
    <template x-if="slides.length > 1">
        <div>
            <button @click="prev()"
                class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full p-2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 shadow"
                aria-label="Anterior">‹</button>
            <button @click="next()"
                class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full p-2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 shadow"
                aria-label="Siguiente">›</button>
        </div>
    </template>

    <!-- Indicadores (bullets) -->
    <template x-if="slides.length > 1">
        <div
            class="absolute bottom-3 left-1/2 -translate-x-1/2 z-10
            flex items-center gap-2 rounded-full px-3 py-1
            bg-white/80 dark:bg-black/55 backdrop-blur-md
            ring-1 ring-black/10 dark:ring-white/10 shadow">
            <template x-for="(s, i) in slides" :key="'dot-'+i">
                <button
                    @click="go(i)"
                    :aria-label="'Ir al slide ' + (i+1)"
                    class="h-3 rounded-full transition-all"
                    :class="i === index
                        ? 'w-8 bg-brand-light ring-1 ring-black/10 dark:ring-white/40'
                        : 'w-3 bg-black/40 dark:bg-white/45 hover:bg-black/60 dark:hover:bg-white/70'">
                </button>
            </template>
        </div>
    </template>
</section>
<?php /**PATH /Library/Proyectos/eccav4/resources/views/carrusel.blade.php ENDPATH**/ ?>