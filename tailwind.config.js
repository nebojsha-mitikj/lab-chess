const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    presets: [
        require('./tailwind-preset')
    ],
    purge: {
        enabled: true,
        content: [
            './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
            './storage/framework/views/*.php',
            './resources/views/**/*.blade.php',
            './resources/js/*.js',
            './resources/js/components/**/*.js',
            './resources/js/components/**/*.vue',
        ]
    }
};
