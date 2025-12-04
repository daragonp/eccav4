<?php $__env->startSection('title', 'Tez brillante'); ?>

<?php $__env->startSection('content'); ?>
  <section aria-labelledby="home-quote" class="mb-8">
    <h2 id="home-quote" class="sr-only">Palabra del día</h2>
    <?php echo $__env->make('quote', ['narrow' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </section>

  <!-- Últimos cultos -->
  <section aria-labelledby="home-latest-worships" class="mb-8">
    <h2 id="home-latest-worships" class="sr-only">Últimos cultos dominicales</h2>
    <?php echo $__env->make('components.latest-worships', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </section>

  <!-- Sección social sin contenedor para que ocupe todo el ancho -->
  <section aria-labelledby="home-social" class="mb-8 px-none">
    <h2 id="home-social" class="sr-only">Enlaces sociales y recursos</h2>
    <?php echo $__env->make('social', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </section>

  <section aria-labelledby="home-ad" class="mb-8 page-container">
    <h2 id="home-ad" class="sr-only">Publicidad</h2>
    <div class="flex justify-center">
      
      <ins class="adsbygoogle"
           style="display:block"
           data-ad-format="fluid"
           data-ad-layout-key="-fb+5w+4e-db+86"
           data-ad-client="ca-pub-2633231257763494"
           data-ad-slot="1234567890"></ins>
      <script>
           (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    </div>
  </section>

  <section aria-labelledby="home-carousel" class="mb-8">
    <h2 id="home-carousel" class="sr-only">Banners</h2>
    <?php echo $__env->make('carrusel', ['narrow' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
  </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.main', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/eccav4/resources/views/welcome.blade.php ENDPATH**/ ?>