<template>
    <div>
        <h1 class="header">Password</h1>

        <p class="mt-5">Current password</p>
        <input
            v-model="currentPassword"
            type="password"
            @keydown="$refs.saveChangesButton.disabled = false"
            class="rounded-md shadow-sm border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30"
        >
        <p v-for="currentPasswordError in currentPasswordErrors" class="text-danger" v-text="currentPasswordError"></p>

        <p class="mt-5">New password</p>
        <input
            v-model="newPassword"
            type="password"
            @keydown="$refs.saveChangesButton.disabled = false"
            class="rounded-md shadow-sm border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30"
        >
        <p v-for="newPasswordError in newPasswordErrors" class="text-danger" v-text="newPasswordError"></p>
        <p class="text-green-600 mt-2" v-text="successMessage"></p>
        <save-changes-button ref="saveChangesButton" @saveChanges="updatePassword"></save-changes-button>
    </div>
</template>

<script>
import SaveChangesButton from "../assets/save-changes-button";
export default {
    name: "password",
    components: {SaveChangesButton},

    data(){
        return {
            currentPassword: '',
            currentPasswordErrors: [],
            newPassword: '',
            newPasswordErrors: [],
            successMessage: ''
        }
    },

    methods: {
        /**
         * Clear error and success messages.
         */
        clearNotifications(){
            this.currentPasswordErrors = [];
            this.newPasswordErrors = [];
            this.successMessage = '';
        },

        /**
         * Update password.
         */
        updatePassword(){
            this.clearNotifications();
            axios.put('/api/settings/updatePassword', {
                'current_password': this.currentPassword,
                'new_password': this.newPassword
            }).then(result => {
                this.$refs.saveChangesButton.loading = false;
                this.$refs.saveChangesButton.disabled = true;
                this.successMessage = result.data.message;
            }).catch(error => {
                this.$refs.saveChangesButton.loading = false;
                this.$refs.saveChangesButton.disabled = false;
                if(error.response.data.errors != null){
                    let errors = error.response.data.errors;
                    this.currentPasswordErrors = errors.current_password != null ? errors.current_password : [];
                    this.newPasswordErrors = errors.new_password != null ? errors.new_password : [];
                }
            });
        }
    },
}
</script>
