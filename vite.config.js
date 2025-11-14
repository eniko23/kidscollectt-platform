import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

// --- İKİ EKLENTİYİ DE İÇERİ AKTAR ---
import typography from '@tailwindcss/typography'; 
import forms from '@tailwindcss/forms'; 

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        // --- TAILWIND AYARLARININ TAMAMI BURADA ---
        tailwindcss({
            config: {
                content: [
                    './resources/views/**/*.blade.php',
                    './app/Filament/**/*.php',
                    './vendor/filament/**/*.blade.php',
                    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
                    './storage/framework/views/*.php',
                ],
                theme: {
                    extend: {},
                },
                plugins: [
                    typography, // <-- Yazı tipi eklentisi (Açıklama için)
                    forms,      // <-- Form eklentisi (Renk yuvarlakları için)
                ],
            }
        }),
        // --- BİTTİ ---
    ],
        resolve: {
        alias: {
            'livewire/livewire': '/vendor/livewire/livewire/dist/livewire.esm.js',
        },
    },
    server: {
        cors: true,
    },
});