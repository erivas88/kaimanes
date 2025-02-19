import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/map.js'  // Agregamos el archivo del mapa
            ],
            refresh: true,
        }),
    ],
    build: {
        minify: true, // Activa la minimización
        terserOptions: {
            compress: {
                drop_console: true, // Elimina console.log
            },
            output: {
                comments: false,    // Elimina comentarios
            },
        },
    },
});
