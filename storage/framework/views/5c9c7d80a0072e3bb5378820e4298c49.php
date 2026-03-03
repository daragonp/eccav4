<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-2633231257763494"
     crossorigin="anonymous"></script>


<link rel="icon" href="<?php echo e(asset('images/fav/favicon.svg')); ?>" type="image/svg+xml" />
<link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('images/fav/apple-touch-icon.png')); ?>" />
<link rel="manifest" href="<?php echo e(asset('images/fav/site.webmanifest')); ?>" />


<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/css/lightbox.min.css"
      crossorigin="anonymous" referrerpolicy="no-referrer" />


<script>
  (function() {
    const theme = localStorage.getItem('theme') || 'light';
    if (theme === 'dark') {
      document.documentElement.classList.add('dark');
    }
  })();
</script>


<?php echo app('Illuminate\Foundation\Vite')([
  'resources/css/app.css',
  'resources/css/tw.css',
  'resources/css/radio-player.css',
  'resources/css/privacy-notice.css',
  'resources/js/app.js',
  'resources/js/radio-player.js',
  'resources/js/privacy-notice.js',
]); ?>

<?php echo $__env->yieldPushContent('head'); ?>
<script src="https://kit.fontawesome.com/71f1c28685.js" crossorigin="anonymous"></script>
<title><?php echo $__env->yieldContent('title'); ?> - Emancipación Cristiana Afro</title>
</head>

<body class="font-sans antialiased bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100">
  
  
  <div x-data="privacyNoticeApp()" x-init="init()" style="display: contents;">
    
    
    <?php echo $__env->make('layouts.menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php if (! empty(trim($__env->yieldContent('page-hero')))): ?>
      <div class="page-container page-hero">
        <?php echo $__env->yieldContent('page-hero'); ?>
      </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <main class="page-container py-6 sm:py-10">
      <?php echo $__env->yieldContent('content'); ?>
    </main>

    
    <?php echo $__env->yieldPushContent('modals'); ?>

    
    <?php echo $__env->make('footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php echo $__env->make('player', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('privacy-notice', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

  </div>

  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.4/js/lightbox.min.js"
          crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  
  <style>
    #lightboxOverlay{ z-index:2147483646 !important; position:fixed !important; inset:0 !important; }
    .lightbox, .lb-outerContainer, .lb-dataContainer{ z-index:2147483647 !important; }
  </style>

  
  <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH /Library/Proyectos/eccav4/resources/views/layouts/main.blade.php ENDPATH**/ ?>