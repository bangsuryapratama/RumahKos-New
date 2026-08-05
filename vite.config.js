import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],

    build: {
        // Code splitting for better caching
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs', 'axios'],
                },
            },
        },
        // Enable source maps for debugging
        sourcemap: false,
        // Minify output using built-in esbuild
        minify: 'esbuild',
        // Chunk size warning limit
        chunkSizeWarningLimit: 500,
    },
});
