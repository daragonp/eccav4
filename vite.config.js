import { defineConfig } from 'vite';
const laravel = await import('laravel-vite-plugin');

export default defineConfig({
  build: {
    outDir: 'public/build', // Asegúrate de que los archivos generados se guarden aquí
  },
  plugins: [
    laravel.default({
      input: [
        'resources/css/app.css',
        'resources/sass/app.scss',
        'resources/js/app.js',
        'resources/js/menu.js',
        'resources/css/radio-player.css',
        'resources/js/radio-player.js', 
      ],
      refresh: true,
    }),
  ],
});
