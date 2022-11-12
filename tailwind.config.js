const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    darkMode: "class",
    theme: {
        extend: {
            // fontFamily: {
            //     sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            // },
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    
                    primary: "#58BF6C",
                    secondary: "#D8F9E1",
                    light: "#F8F8F8",
                    gray: "#9FA6B2",
                    dark: "#080808",
                    blue: "#E3F4FF"
                },
            },
            fontSize: {
                base: ['1rem', '1.75rem'],
            },
        },
    },

    plugins: [
        require("tailwindcss/nesting"),
        require('@tailwindcss/forms'), 
        require('@tailwindcss/typography')
    ],
};
