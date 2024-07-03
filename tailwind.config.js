import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                uinBlue: '#127ec3',
                uinGreen: '#85c442',
                uinOrange: '#f7ba00',
                uinYellow: '#ffb700',
                uinNavy: '#04256c',
                uinTosca: '03726a',
                uinRed: 'e86412',
            }
        },
    },

    plugins: [forms],
};
