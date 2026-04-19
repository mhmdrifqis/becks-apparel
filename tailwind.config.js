import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class',
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    '50': '#fdfbf7',   // Ivory Light
                    '100': '#faf6ed',  // Ivory
                    '200': '#f5ead3',
                    '300': '#ecd29b',
                    '400': '#e2b35a',  // Muted Gold
                    '500': '#d4af37',  // Classic Gold
                    '600': '#b48e2a',
                    '700': '#8f6d22',
                    '800': '#1a4b36',
                    '900': '#06402B',  // Forest Green
                    '950': '#04251a',  // Deep Forest
                },
            },
        },
    },

    plugins: [forms],
};
