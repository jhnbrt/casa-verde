import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/header.css',
                'resources/css/home.css',
                'resources/css/home-villas.css',
                'resources/css/villa.css',
                'resources/css/villa-show.css',
                'resources/css/experience.css',
                'resources/css/wellness.css',
                'resources/css/dining.css',
                'resources/css/theme.css',
                'resources/css/about.css',
                'resources/css/contact.css',
                'resources/css/longstay.css',
                'resources/css/pages.css',
                'resources/css/events.css',
                'resources/css/gallery.css',
                'resources/css/footer.css',
                'resources/js/app.js',
                'resources/js/gallery.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
