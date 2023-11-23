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
            {
                bumblebee: {
                    ...require("daisyui/src/theming/themes")["bumblebee"],
                    "base-100": "#f2f2f2",
                    "base-200": "#ffffff",
                  },
            },
            "forest",
        ],
    },

    plugins: [require("daisyui")],
};
