/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/**/*.blade.php',
        "./resources/**/*.js",
    ],
    theme: {
        fontFamily: {
            'lato': ['Lato', 'sans-serif'],
            'lobster': ['Lobster', 'cursive'],
        },
        extend: {},
    },
    plugins: [],
}
