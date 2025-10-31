import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwind from '@tailwindcss/vite'

export default defineConfig({
  build: { outDir: 'public/build' },
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
    ],
    theme: {
      extend: {
        colors: {
          'brand-green': {
            50: '#f0fdf4',
            100: '#dcfce7',
            200: '#bbf7d0',
            300: '#86efac',
            400: '#4ade80',
            500: '#22c55e',
            600: '#16a34a',
            700: '#15803d',
            800: '#166534',
            900: '#14532d',
          },
        },
      },
    }, 
    plugins: [
    laravel({
      input: [
        // PÚBLICO
        'resources/css/app.css',
        'resources/css/tw.css',        
        'resources/css/dashboard.css',
        'resources/css/radio-player.css',
        'resources/css/privacy-notice.css', // ⬅️ AÑADIDO
        
        'resources/js/app.js',
        'resources/js/radio-player.js',
        'resources/js/privacy-notice.js',

        // ADMIN
        'resources/js/admin.js',
        './resources/css/menu-fix.css', 
        'resources/js/datatables-es.json',

      ],
      refresh: true,
    }),
    tailwind(),
  ],
})