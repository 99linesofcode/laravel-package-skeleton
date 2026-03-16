import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'workbench/resources/css/app.css',
                'workbench/resources/js/app.js',
            ],
            refresh: [
                'workbench/resources/views/**',
                'resources/views/**',
            ],
            publicDirectory: 'vendor/orchestra/testbench-core/laravel/public',
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: [
                '**/vendor/orchestra/testbench-core/laravel/**',
                '**/storage/framework/views/**',
                '**/workbench/storage/framework/views/**',
            ],
        },
    },
});
