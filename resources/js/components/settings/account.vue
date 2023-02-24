<template>
    <div class="m-auto sm:m-0">
        <h1 class="header">Account</h1>

        <p class="mt-5">Username</p>
        <input
            type="text"
            @keydown="$refs.saveChangesButton.disabled = false"
            v-model="username"
            class="rounded-md shadow-sm border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30"
        >
        <p v-for="usernameError in usernameErrors" class="text-danger" v-text="usernameError"></p>

        <p class="mt-5">Email</p>
        <input
            type="email"
            @keydown="$refs.saveChangesButton.disabled = false"
            v-model="email"
            class="rounded-md shadow-sm border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30"
        >
        <template v-if="emailVerified == null">
            <br>
            <p @click="sendVerificationEmail"
               class="text-danger inline-block text-sm no-select hover:underline cursor-pointer">Your email is not
                verified. Get a new verification email.</p>
        </template>
        <template v-if="emailVerifiedErrorMessage.length > 0">
            <br>
            <p class="text-danger inline-block text-sm" v-text="emailVerifiedErrorMessage"></p>
        </template>
        <template v-if="emailVerifiedMessage.length > 0">
            <br>
            <p class="text-green-600 inline-block text-sm" v-text="emailVerifiedMessage"></p>
        </template>

        <p v-for="emailError in emailErrors" class="text-danger" v-text="emailError"></p>

        <!--
        <p class="mt-5">
            <span style="min-width: 120px; display: inline-block;">Sound effects</span>
            <toggle-button class="ml-1" @change="$refs.saveChangesButton.disabled = false" v-model="soundEffects" :color="getPrimaryLight"/>
        </p>

        <p class="mt-5">
            <span style="min-width: 120px; display: inline-block;">Animations</span>
            <toggle-button class="ml-1" @change="$refs.saveChangesButton.disabled = false" v-model="animations" :color="getPrimaryLight"/>
        </p>
        -->

        <p class="mt-5">
            Profile picture
            <button @click="$refs.uploader.click()" class="ml-3 button sub-button">
                Choose File
            </button>
        </p>
        <p v-if="selectedFile != null" class="mt-1 text-gray-600">
            Selected file: <span class="font-semibold">{{ selectedFile.name }}</span>
        </p>
        <p class="text-gray-600 text-sm mt-2">2MB maximum image size.</p>
        <image-upload ref="uploader" @fileChange="fileChange" :sendRequest="false"></image-upload>

        <p v-for="selectedFileError in selectedFileErrors" class="text-danger" v-text="selectedFileError"></p>

        <p
            v-if="deleteRequestMessage.length === 0 && deleteRequestErrorMessage.length === 0"
            class="text-danger-light hover:text-danger mb-2 mt-4 no-select cursor-pointer inline-block"
            @click="deleteAccount"
        >
            DELETE MY ACCOUNT
        </p>
        <p v-else-if="deleteRequestMessage.length > 0" v-text="deleteRequestMessage" class="text-green-600 mb-2 mt-4"></p>
        <p v-else-if="deleteRequestErrorMessage.length > 0" v-text="deleteRequestErrorMessage" class="text-danger mb-2 mt-4"></p>

        <p class="text-green-600 mt-2" v-text="successMessage"></p>

        <save-changes-button ref="saveChangesButton" @saveChanges="saveChanges"></save-changes-button>
    </div>
</template>

<script>
import SaveChangesButton from "../assets/save-changes-button";

export default {
    name: "account",
    components: {SaveChangesButton},
    props: ['userConfiguration'],

    data() {
        return {
            username: '',
            usernameErrors: [],
            email: '',
            emailErrors: [],
            selectedFile: null,
            selectedFileErrors: [],
            soundEffects: true,
            animations: true,

            successMessage: '',
            emailVerified: null,

            emailVerifiedMessage: '',
            emailVerifiedErrorMessage: '',

            deleteRequestMessage: '',
            deleteRequestErrorMessage: '',
            deleteRequestActive: false
        }
    },

    created() {
        if (window.user !== null) {
            this.email = window.user.email;
            this.username = window.user.username;
            this.emailVerified = window.user.email_verified_at;
        }
        this.soundEffects = Boolean(this.userConfiguration.sound_effects);
        this.animations = Boolean(this.userConfiguration.animation);
    },

    methods: {

        /**
         * Delete User Account Request
         */
        deleteAccount() {
            if(this.deleteRequestActive) return;
            this.deleteRequestActive = true;
            axios.post('/api/user/deleteRequest').then(result => {
                this.deleteRequestMessage = result.data.message;
            }).catch(error => {
                this.deleteRequestErrorMessage = 'Unexpected error, please try again later.'
            }).finally(() => this.deleteRequestActive = false);
        },

        /**
         * Clear error and success messages.
         */
        clearNotifications() {
            this.emailErrors = [];
            this.selectedFileErrors = [];
            this.usernameErrors = [];
            this.successMessage = '';
        },

        /**
         * Save account changes.
         */
        saveChanges() {
            this.clearNotifications();

            let settings = {headers: {'content-type': 'multipart/form-data'}};
            let data = new FormData();
            if (this.selectedFile != null) {
                data.append('profile_picture_url', this.selectedFile, this.selectedFile.name);
            }
            data.append('username', this.username);
            data.append('email', this.email);
            data.append('sound_effects', 1);
            data.append('animation', 1);

            axios.post('/api/settings/updateAccount', data, settings).then(result => {
                this.$refs.saveChangesButton.loading = false;
                this.$refs.saveChangesButton.disabled = true;
                this.successMessage = result.data.message;
                let profileLinks = document.querySelectorAll('.profileLink');
                for (let i = 0; i < profileLinks.length; i++) {
                    profileLinks[i].href = profileLinks[i].href.substr(0, profileLinks[i].href.lastIndexOf('/'))
                    profileLinks[i].href += '/' + this.username;
                }
            }).catch(error => {
                this.$refs.saveChangesButton.loading = false;
                this.$refs.saveChangesButton.disabled = false;

                if (error.response.data.errors != null) {
                    let errors = error.response.data.errors;
                    this.emailErrors = errors.email != null ? errors.email : [];
                    this.selectedFileErrors = errors.profile_picture_url != null ? errors.profile_picture_url : [];
                    this.usernameErrors = errors.username != null ? errors.username : [];
                }
            })
        },

        /**
         * Send a new verification email
         */
        sendVerificationEmail() {
            this.emailVerified = 'verifying';
            axios.post('/api/email/verification-notification').then(result => {
                this.emailVerifiedMessage = result.data.message;
            }).catch(error => {
                this.emailVerifiedErrorMessage = 'Unexpected error, please try again later.'
            });
        },

        /**
         * Select file.
         * @param file
         */
        fileChange(file) {
            this.selectedFile = file;
            this.$refs.saveChangesButton.disabled = false;
        }

    }
}
</script>
