require('./bootstrap');
require('alpinejs');
import tailwindConfig from  './../../tailwind-preset';
import delayMixin from './components/assets/mixins/delayMixin';

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

// @url: https://www.npmjs.com/package/vue-uuid
import uuid from "vue-uuid";
Vue.use(uuid);

Vue.mixin(delayMixin);

// @url: https://www.npmjs.com/package/vue-js-toggle-button
import { ToggleButton } from 'vue-js-toggle-button'
Vue.component('ToggleButton', ToggleButton)

// Vue Konva
import VueKonva from 'vue-konva';
Vue.use(VueKonva);

// Combining Multiple Files with Laravel Mix
// @url: https://stackoverflow.com/questions/42622118/combining-multiple-files-with-laravel-mix/45127329#45127329

// Settings
Vue.component('account', require('./components/settings/account.vue').default);
Vue.component('coach', require('./components/settings/coach.vue').default);
Vue.component('display', require('./components/settings/display.vue').default);
Vue.component('notifications', require('./components/settings/notifications.vue').default);
Vue.component('password', require('./components/settings/password.vue').default);
Vue.component('privacy', require('./components/settings/privacy.vue').default);
Vue.component('profile', require('./components/settings/profile.vue').default);

// Profile
Vue.component('friends', require('./components/profile/friends.vue').default);
Vue.component('image-upload', require('./components/profile/image-upload.vue').default);
Vue.component('follow-unfollow-buttons', require('./components/profile/follow-unfollow-buttons.vue').default);
Vue.component('social-media-links', require('./components/profile/social-media-links.vue').default);
Vue.component('email-verification', require('./components/profile/email-verification.vue').default);

// Assets
Vue.component('my-spinner', require('./components/assets/my-spinner.vue').default);

// Courses Not used for now
Vue.component('courses', require('./components/profile/courses.vue').default);
Vue.component('courses-main', require('./components/courses/courses-main.vue').default);
Vue.component('lecture', require('./components/courses/lecture.vue').default);
Vue.component('circular-progress-bar', require('./components/assets/circular-progress-bar.vue').default);

// Analytics
Vue.component('analytics', require('./components/analytics/analytics.vue').default);

// Subscription
Vue.component('subscription', require('./components/subscription/subscription').default)

// Trainer
Vue.component('trainer-main', require('./components/trainer/trainer-main.vue').default);
Vue.component('trainer-variant', require('./components/trainer/trainer-variant.vue').default);
Vue.component('trainer-position', require('./components/trainer/trainer-position.vue').default);
Vue.component('trainer', require('./components/profile/trainer.vue').default);

// Leaderboard
Vue.component('leaderboard', require('./components/leaderboard/leaderboard.vue').default);

// Activity
Vue.component('activity', require('./components/profile/activity.vue').default);

// Contact
Vue.component('contact', require('./components/contact/contact.vue').default);

// Icons
Vue.component('uil-angle-left', require('@iconscout/vue-unicons/icons/uil-angle-left.vue').default);
Vue.component('uil-angle-right', require('@iconscout/vue-unicons/icons/uil-angle-right.vue').default);
Vue.component('uil-bolt', require('@iconscout/vue-unicons/icons/uil-bolt.vue').default);
Vue.component('uil-pen', require('@iconscout/vue-unicons/icons/uil-pen.vue').default);
Vue.component('uil-clock', require('@iconscout/vue-unicons/icons/uil-clock.vue').default);
Vue.component('uil-users-alt', require('@iconscout/vue-unicons/icons/uil-users-alt.vue').default);
Vue.component('uil-instagram', require('@iconscout/vue-unicons/icons/uil-instagram.vue').default);
Vue.component('uil-facebook', require('@iconscout/vue-unicons/icons/uil-facebook.vue').default);
Vue.component('uil-twitter', require('@iconscout/vue-unicons/icons/uil-twitter.vue').default);
Vue.component('uil-star', require('@iconscout/vue-unicons/icons/uil-star.vue').default);
Vue.component('uil-user-circle', require('@iconscout/vue-unicons/icons/uil-user-circle.vue').default);
Vue.component('uil-user', require('@iconscout/vue-unicons/icons/uil-user.vue').default);
Vue.component('uil-padlock', require('@iconscout/vue-unicons/icons/uil-padlock.vue').default);
Vue.component('uil-desktop', require('@iconscout/vue-unicons/icons/uil-desktop.vue').default);
Vue.component('uil-basketball', require('@iconscout/vue-unicons/icons/uil-basketball.vue').default);
Vue.component('uil-bell', require('@iconscout/vue-unicons/icons/uil-bell.vue').default);
Vue.component('uil-keyhole-square', require('@iconscout/vue-unicons/icons/uil-keyhole-square.vue').default);
Vue.component('uil-message', require('@iconscout/vue-unicons/icons/uil-message.vue').default);

// For older browsers that don't support .replaceAll String prototype.
if(typeof String.prototype.replaceAll === "undefined") {
    String.prototype.replaceAll = function(match, replace) {
        return this.split(match).join(replace);
    }
}

const app = new Vue({
    el: '#app',
    methods: {
        /**
         * On follow event refresh followers.
         */
        onFollow(){
            this.$refs.friends.refreshFollowers();
            this.$refs.followersCount.innerText = Number.parseInt(this.$refs.followersCount.innerText) + 1;
        },
        /**
         * On unfollow event refresh followers.
         */
        onUnfollow(){
            this.$refs.friends.refreshFollowers();
            this.$refs.followersCount.innerText = Number.parseInt(this.$refs.followersCount.innerText) - 1;
        },

        /**
         * Stop Profile Spinner.
         */
        stopSpinner(){
            document.getElementById('profile-spinner').style.display = 'none';
        }
    }
});
