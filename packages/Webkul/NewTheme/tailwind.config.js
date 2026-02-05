/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./src/Resources/**/*.blade.php", "./src/Resources/**/*.js"],

    theme: {
        container: {
            center: true,

            screens: {
                "2xl": "1440px",
            },

            padding: {
                DEFAULT: "90px",
            },
        },

        screens: {
            sm: "525px",
            md: "768px",
            lg: "1024px",
            xl: "1240px",
            "2xl": "1440px",
            1180: "1180px",
            1060: "1060px",
            991: "991px",
            868: "868px",
        },

        extend: {
            colors: {
                navyBlue: "#38200F",
                darkBrown: "#502d16",
                lightOrange: "#f5f2ed",
                goldenOrange: "rgba(196, 154, 54, 1)",
                darkGreen: '#90785b',
                transparentOrange:  "rgba(245, 242, 237, 0.61)",
                transparent:  "rgba(245, 242, 237, 0.2)",
                orangeBackground: "#F2ECE7",
                darkBlue: '#0044F2',
                darkPink: '#F85156',
            },

            fontFamily: {
                poppins: ["Inter", "Lato", "Helvetica", "Arial", "sans-serif"],
                dmserif: ["Bona Nova SC", "serif"],
            },
        }
    },

    plugins: [],

    safelist: [
        {
            pattern: /icon-/,
        }
    ]
};
