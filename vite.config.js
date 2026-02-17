import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@gentelella': path.resolve(__dirname, 'public/gentelella/src'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                includePaths: ['node_modules'],
            },
        },
    },
    server: {
        host: '127.0.0.1',
        hmr: {
            host: 'localhost',
        },
    },
});
