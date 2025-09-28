@php
use Illuminate\Support\Str;

$slides = collect($slider ?? [])->map(function ($b) {
    $left  = $b->izq ?? $b->left ?? $b->image_left ?? $b->left_image ?? null;
    $right = $b->der ?? $b->right ?? $b->image_right ?? $b->right_image ?? null;
    if (!$left || !$right) return null;

    $leftUrl = Str::startsWith($left, ['http://','https://'])
        ? $left
        : asset(Str::startsWith($left, ['images/','storage/']) ? $left : 'images/slider/'.$left);

    $rightUrl = Str::startsWith($right, ['http://','https://'])
        ? $right
        : asset(Str::startsWith($right, ['images/','storage/']) ? $right : 'images/slider/'.$right);

    return ['left' => $leftUrl, 'right' => $rightUrl];
})->filter()->values();

if ($slides->isEmpty()) {
    $slides = collect([
        ['left' => asset('images/slider/izq_placeholder.png'), 'right' => asset('images/slider/der_placeholder.png')],
    ]);
}
@endphp

<section
  x-data='{
    index: 0,
    slides: @json($slides),
    timer: null,
    interval: 60000,
    init() { if (this.slides.length) { this.start(); } },
    start() { this.stop(); this.timer = setInterval(() => this.next(), this.interval); },
    stop()  { if (this.timer) { clearInterval(this.timer); this.timer = null; } },
    next()  { this.index = (this.index + 1) % this.slides.length; },
    prev()  { this.index = (this.index - 1 + this.slides.length) % this.slides.length; },
    go(i)   { this.index = i % this.slides.length; this.start(); },
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

  <div class="overflow-hidden bg-white dark:bg-gray-800">
    <template x-for="(slide, i) in slides" :key="i">
      <div x-show="index === i" x-transition.opacity class="grid grid-cols-1 md:grid-cols-2 gap-2 p-2">
        <div class="text-center p-2">
          <img :src="slide.left"
               class="mx-auto max-h-[480px] w-full object-cover rounded shadow"
               :alt="`Banner izquierdo ${i+1}`"
               loading="lazy" decoding="async" draggable="false">
        </div>
        <div class="text-center p-2">
          <img :src="slide.right"
               class="mx-auto max-h-[480px] w-full object-cover rounded shadow"
               :alt="`Banner derecho ${i+1}`"
               loading="lazy" decoding="async" draggable="false">
        </div>
      </div>
    </template>
  </div>

  <template x-if="slides.length > 1">
    <div>
      <button @click="prev"
        class="absolute left-2 top-1/2 -translate-y-1/2 rounded-full p-2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 shadow"
        aria-label="Anterior">‹</button>
      <button @click="next"
        class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full p-2 bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-800 shadow"
        aria-label="Siguiente">›</button>
    </div>
  </template>

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
