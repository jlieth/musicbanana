const colors = require("tailwindcss/colors");

module.exports = {
    content: [
        `${__dirname}/assets/js/**/*.svelte`,
        `${__dirname}/node_modules/flowbite-svelte/**/*.{html,js,svelte,ts}`,
    ],
    theme: {
        extend: {
            colors: {
                "header": colors.zinc,
                "highlight": colors.cyan,
            },
        },
        screens: {
            "xxl": {max: "9999px"},
            "xl": {max: "1400px"},
            "lg": {max: "1200px"},
            "md": {max: "992px"},
            "sm": {max: "768px"},
            "xs": {max: "576px"},
        },
        maxWidth: {
            "xs": "480px",
            "sm": "540px",
            "md": "720px",
            "lg": "960px",
            "xl": "1140px",
            "xxl": "1320px",
        },
    },
    plugins: [
        require("flowbite/plugin")
    ]
}
