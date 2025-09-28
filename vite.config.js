import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import tailwind from '@tailwindcss/vite'

export default defineConfig({
  build: { outDir: 'public/build' },
  plugins: [
    laravel({
      input: [
        // CSS
        'resources/css/app.css',
        'resources/css/tw.css',
        'resources/css/radio-player.css',

        // JS
        'resources/js/app.js',
        'resources/js/radio-player.js',
      ],
      refresh: true,
    }),
    tailwind(),
  ],
})
