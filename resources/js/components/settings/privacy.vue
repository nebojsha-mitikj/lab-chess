<template>
    <div>
        <h1 class="header">Privacy</h1>

        <div class="mt-3">
            <div class="mt-5 block grid grid-cols-12 md:block">
                <toggle-button class="ml-1 mt-1 col-span-2" @change="updatePrivacy" v-model="publicProfile" :color="getPrimaryLight"/>
                <span class="ml-3 col-span-10 pl-2 sm:pl-0">Make my profile public.</span>
            </div>
        </div>
    </div>
</template>

<script>

export default {
    name: "privacy",

    props: ['userConfiguration'],

    data(){
        return {
            publicProfile: true
        }
    },

    created(){
        this.publicProfile = Boolean(this.userConfiguration.public_profile);
    },

    methods: {
        /**
         * Update clicked privacy.
         */
        updatePrivacy(){
            axios.put('/api/settings/updatePrivacy', {'public_profile': this.publicProfile});
        }
    }
}
</script>
