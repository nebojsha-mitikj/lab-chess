<template>
    <div>
        <h1 class="header">Profile</h1>

        <p class="mt-5">Full name</p>
        <input
            type="text"
            @keydown="$refs.saveChangesButton.disabled = false"
            v-model="fullName"
            class="rounded-md shadow-sm border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30"
        >
        <p v-for="fullNameError in fullNameErrors" class="text-danger" v-text="fullNameError"></p>

        <p class="mt-5">Biography</p>
        <textarea
            rows="4"
            @keydown="$refs.saveChangesButton.disabled = false"
            v-model="biography"
            class="rounded-md min-w-full shadow-sm border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30"
        ></textarea>
        <p v-for="biographyError in biographyErrors" class="text-danger" v-text="biographyError"></p>

        <p class="mt-5">Social media links</p>
        <textarea
            rows="4"
            @keydown="$refs.saveChangesButton.disabled = false"
            v-model="socialMediaLinks"
            class="rounded-md min-w-full shadow-sm border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30"
            placeholder="https://your-social-media-link-01.com
https://your-social-media-link-02.com
https://your-social-media-link-03.com
"
        ></textarea>

        <p v-for="socialMediaLinksError in socialMediaLinksErrors" class="text-danger" v-text="socialMediaLinksError">
        </p>

        <p class="text-sm text-gray-600">Facebook, Twitter, GitHub, Chess.com, Lichess.org...</p>
        <p class="text-sm text-gray-600">One URL per line.</p>

        <p class="text-green-600 mt-2" v-text="successMessage"></p>

        <save-changes-button ref="saveChangesButton" @saveChanges="updateProfile">
        </save-changes-button>
    </div>
</template>

<script>
import SaveChangesButton from "../assets/save-changes-button";
export default {
    name: "profile",
    components: {SaveChangesButton},
    data(){
        return {
            fullName: '',
            fullNameErrors: [],
            biography: '',
            biographyErrors: [],
            socialMediaLinks: '',
            socialMediaLinksErrors: [],
            successMessage: '',
        }
    },

    created(){
        if(window.user !== null){
            this.fullName = window.user.full_name;
            this.biography = window.user.biography;
            this.socialMediaLinks = window.user.social_media_links;
        }
    },

    methods: {
        /**
         * Clear error and success messages.
         */
        clearNotifications(){
            this.fullNameErrors = [];
            this.biographyErrors = [];
            this.socialMediaLinksErrors = [];
            this.successMessage = '';
        },

        /**
         * Update profile data.
         */
        updateProfile(){
            this.clearNotifications();
            axios.put('/api/settings/updateProfile', {
                full_name: this.fullName,
                biography: this.biography,
                social_media_links: this.socialMediaLinks
            }).then(result => {
                this.$refs.saveChangesButton.loading = false;
                this.$refs.saveChangesButton.disabled = true;
                this.successMessage = result.data.message;
            }).catch(error => {
                this.$refs.saveChangesButton.loading = false;
                this.$refs.saveChangesButton.disabled = false;
                if(error.response.data.errors != null){
                    let errors = error.response.data.errors;
                    this.fullNameErrors = errors.full_name != null ? errors.full_name : [];
                    this.biographyErrors = errors.biography != null ? errors.biography : [];
                    this.socialMediaLinksErrors = errors.social_media_links != null ? errors.social_media_links : [];
                }
            })
        }
    },
}
</script>
