<template>
    <div class="text-center sm:text-left">

        <a
            v-for="(url,index) in socialMediaLinks"
            :key="url"
            :href="url"
            class="hover:underline text-blue-500 block sm:inline"
            :class="{'ml-0 sm:ml-4': index > 0}"
            target="_blank"
        >
            <uil-instagram class="inline" style="margin-top: -3px" v-if="getDomain(url) === 'instagram.com'"></uil-instagram>
            <uil-facebook class="inline" style="margin-top: -3px" v-else-if="getDomain(url) === 'facebook.com'"></uil-facebook>
            <uil-twitter class="inline" style="margin-top: -3px" v-else-if="getDomain(url) === 'twitter.com'"></uil-twitter>
            <uil-github class="inline" style="margin-top: -3px" v-else-if="getDomain(url) === 'github.com'"></uil-github>
            <uil-youtube class="inline" style="margin-top: -3px" v-else-if="getDomain(url) === 'youtube.com'"></uil-youtube>
            <uil-whatsapp class="inline" style="margin-top: -3px" v-else-if="getDomain(url) === 'whatsapp.com'"></uil-whatsapp>
            <uil-reddit-alien-alt class="inline" style="margin-top: -3px" v-else-if="getDomain(url) === 'reddit.com'"></uil-reddit-alien-alt>
            <uil-linkedin-alt class="inline" style="margin-top: -3px" v-else-if="getDomain(url) === 'linkedin.com'"></uil-linkedin-alt>
            <uil-link class="inline" style="margin-top: -3px" v-else></uil-link>
            {{ getDomain(url) }}
        </a>
    </div>
</template>

<script>
import { UilGithub, UilLink, UilYoutube, UilWhatsapp, UilRedditAlienAlt, UilLinkedinAlt } from '@iconscout/vue-unicons'
export default {
    name: "social-media-links",
    components: { UilGithub, UilLink, UilYoutube, UilWhatsapp, UilRedditAlienAlt, UilLinkedinAlt },

    props: ['socialMediaLinksProp'],

    created() {
        if(this.socialMediaLinksProp !== null){
            this.socialMediaLinks = this.socialMediaLinksProp.split(/\r?\n/);
        }
    },

    data(){
        return {
            socialMediaLinks: null,
        }
    },

    methods: {
        /**
         * Get domain name from url.
         * @param {String} url
         */
        getDomain(url){
            return (new URL(url)).hostname.replace('www.','');
        }
    }
}
</script>
