const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {

    theme: {
        container: {
            center: true,
            padding: "1rem",
            screens: {
                'lg': '1124px',
                'xl': '1124px',
                '2xl': '1124px'
            }
        },
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary': {
                    'lighter': '#E6F2FF',
                    'light': '#79BBFF',
                    'DEFAULT': '#3D96F2',
                    'dark': '#3787DA'
                },

                'danger': {
                    'light': '#E68F8A',
                    'DEFAULT': '#DB6058',
                    'dark': '#9A433E'
                },
                'fire': {
                    'DEFAULT': '#F2721C'
                },
                'warning': {
                    'DEFAULT': '#F7E9C8'
                },
                'alert': {
                    'light': '#F2E63D',
                    'DEFAULT': '#F2E63D',
                    'dark': '#F2E63D'
                },

                'orange': {
                    'DEFAULT': '#ffedd5',
                    'dark': '#c2410c'
                },

                'info': {
                    'DEFAULT': '#DEF2F5',
                    'dark': '#437E89'
                }
            }
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
