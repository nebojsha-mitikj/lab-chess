const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    presets: [
        require('./tailwind-preset')
    ],
    purge: {
        enabled: true,
        content: [
            './resources/views/layouts/guest.blade.php',
            './resources/views/welcome.blade.php',
            './resources/views/policy/privacy.blade.php',
            './resources/views/subscription/subscription.blade.php',
            './resources/views/auth/forgot-password.blade.php',
            './resources/views/auth/login.blade.php',
            './resources/views/auth/register.blade.php',
            './resources/views/auth/verify-email.blade.php',
        ]
    }
};
