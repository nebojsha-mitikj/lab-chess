require('./bootstrap');
require('alpinejs');

import tailwindConfig from  './../../tailwind-preset';

window.Vue = require('vue').default;

Vue.mixin({
    computed: {
        getPrimary: () => tailwindConfig.theme.extend.colors.primary.DEFAULT,
        getPrimaryLight: () => tailwindConfig.theme.extend.colors.primary.light,
        getPrimaryDark: () => tailwindConfig.theme.extend.colors.primary.dark,
        getOrange: () => '#F46120',
        getYellow: () => '#EFB701',
    }
});

import '@fortawesome/fontawesome-free/css/all.min.css'

// Guest Courses
Vue.component('guest-courses', require('./components/guest/guest-courses.vue').default);

// Guest Trainer
Vue.component('guest-trainer', require('./components/guest/guest-trainer.vue').default);

// Icons
Vue.component('uil-angle-right', require('@iconscout/vue-unicons/icons/uil-angle-right.vue').default);

const app = new Vue({
    el: '#app'
});
