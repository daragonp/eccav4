import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwind from '@tailwindcss/vite'

export default defineConfig({
  build: { outDir: 'public/build' },
  plugins: [
    laravel({
      input: [
        // CSS
        'resources/css/app.css',       // ⬅️ Tu CSS (sin bootstrap)
        'resources/css/tw.css',        // ⬅️ Tailwind v4
        'resources/css/radio-player.css',

        // JS
        'resources/js/app.js',
        'resources/js/menu.js',
        'resources/js/radio-player.js',
      ],
      refresh: true,
    }),
    tailwind(),
  ],
})
