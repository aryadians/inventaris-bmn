import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        VitePWA({
            registerType: 'autoUpdate',
            injectRegister: 'auto',
            workbox: {
                globPatterns: ['**/*.{js,css,html,ico,png,svg,woff2,ttf}'],
                navigateFallback: null,
                cleanupOutdatedCaches: true,
            },
            manifest: {
                name: 'SIMA - Sistem Inventaris BMN',
                short_name: 'SIMA',
                description: 'Sistem Inventaris Barang Milik Negara Lapas Kelas IIB Jombang',
                theme_color: '#10b981', // Emerald-500
                background_color: '#0f172a', // Slate-900
                display: 'standalone',
                orientation: 'portrait',
                scope: '/',
                start_url: '/admin',
                icons: [
                    {
                        src: '/images/logo.png', // Fallback icon using existing logo
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'any maskable'
                    },
                    {
                        src: '/images/logo.png', // Fallback icon
                        sizes: '512x512',
                        type: 'image/png'
                    }
                ]
            }
        }),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
