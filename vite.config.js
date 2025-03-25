import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

const host = '0.0.0.0';
const port = 5173;
const origin = `https://planexa-document-repo.ddev.site:5173`;
export default defineConfig({
    server: {

        host: "0.0.0.0",
        port: 5173,
        strictPort: true,
        origin: origin,
        cors: {
            origin: /https?:\/\/([A-Za-z0-9\-\.]+)?(\.ddev\.site)(?::\d+)?$/,
        },

    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/backend.css',
                'resources/js/backend.js',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        // tailwindcss(),
    ],
});
