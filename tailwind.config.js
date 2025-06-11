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
            // --- Add your custom colors here ---
            colors: {
                'opd-light-bg': '#f0f9f8',    // Your very light background color
                'opd-primary': '#87cefa',     // Your teal/mint primary accent
                'opd-secondary': '#ffd3dd',   // Your light pink/rose secondary accent
                // You might want to add some text colors too for consistency
                'opd-text-dark': '#333333',   // A dark grey for primary text
                'opd-text-light': '#666666',  // A lighter grey for secondary text
            },
            // ------------------------------------
        },
    },

    plugins: [forms],
};