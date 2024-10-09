import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import fs from 'fs';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 8080,
        https: {
            key: fs.readFileSync('/etc/pki/tls/private/server.key'),
            cert: fs.readFileSync('/etc/pki/tls/certs/server.crt'),
        },
        hmr: {
            // host: 'localhost'
            host: '192.168.0.184'
        },
    },
    define: {
        'process.env': {},
    },
});
