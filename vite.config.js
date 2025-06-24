import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from "@vitejs/plugin-vue";
import { fileURLToPath, URL } from 'url'
import path from 'path'

export default defineConfig({
    plugins: [
        vue(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),

    ],
    resolve: {

        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '@components': fileURLToPath(new URL('./resources/js/components', import.meta.url)),
            '@services': fileURLToPath(new URL('./resources/js/services', import.meta.url)),
            '@modules': fileURLToPath(new URL('./Modules', import.meta.url)),
        },
    },
    server: {
        host: '127.0.0.1',
        port: 3000,
        https: false,
        hmr: {
            host: '127.0.0.1', // Explicitly set the HMR host
        }
    },
});
