/** @type {import('tailwindcss').Config} */
const defaultTheme = require("tailwindcss/defaultTheme");
module.exports = {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
            sans: ["Inter", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    daisyui: {
        themes: [
            "bumblebee",
            "dark",
        ],
    },

    plugins: [require("daisyui")],
};
