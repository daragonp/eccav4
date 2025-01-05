// vite.config.js
import { defineConfig } from 'vite';
const laravel = await import('laravel-vite-plugin');

export default defineConfig({
    plugins: [
        laravel.default({
            input: [
                'resources/css/app.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
});
