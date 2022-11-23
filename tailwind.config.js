const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    purge: ["./resources/**/*.blade.php", "./resources/**/*.js"],
    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("@tailwindcss/aspect-ratio"),
    ],
    darkMode: false, // or 'media' or 'class'
    variants: {
        extend: {},
    },
    theme: {

        extend: {
            fontFamily: {
                sans: ["Rubik", ...defaultTheme.fontFamily.sans],
                rubik: ["RUBIK", "auto"],
                lora: ["Lora", "serif"],
            },
            colors: {
                aqua: "#18AEBF",
                "aqua-100": "#E5FCFF",
                "aqua-200": "#A5F4FD",
                "aqua-300": "#6AE8F6",
                "aqua-400": "#2DD8EB",
                "aqua-500": "#18AEBF",
                "aqua-600": "#0F9DAE",
                "aqua-700": "#098B9A",
                "aqua-800": "#047886",
                "aqua-900": "#006570",
            },
        },
    },
};
