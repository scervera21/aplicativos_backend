import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    safelist: [
        'bg-emerald-50', 'border-emerald-300', 'text-emerald-700', 'text-emerald-600', 'text-emerald-500',
        'bg-indigo-50',  'border-indigo-300',  'text-indigo-700',  'text-indigo-600',  'text-indigo-400',
        'bg-amber-50',   'border-amber-300',   'text-amber-700',   'text-amber-600',   'text-amber-400',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
