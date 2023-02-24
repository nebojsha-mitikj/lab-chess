<template>
    <div>
        <p
            v-if="emailVerifiedMessage.length === 0 && emailVerifiedErrorMessage.length === 0"
            class="text-danger inline-block text-md mb-2 no-select underline cursor-pointer"
            @click="sendVerificationEmail"
        >
            VERIFY YOUR EMAIL
        </p>
        <p v-else-if="emailVerifiedMessage.length > 0" class="text-green-600 inline-block mb-2">
            {{emailVerifiedMessage}}
        </p>
        <p v-else class="text-danger inline-block mb-2">
            {{emailVerifiedErrorMessage}}
        </p>
    </div>
</template>

<script>
export default {
    name: "email-verification",

    data(){
        return {
            requestActive: false,
            emailVerifiedMessage: '',
            emailVerifiedErrorMessage: ''
        }
    },

    methods: {
        /**
         * Send a new verification email
         */
        sendVerificationEmail() {
            if(this.requestActive) return;
            this.requestActive = true;
            axios.post('/api/email/verification-notification').then(result => {
                this.emailVerifiedMessage = result.data.message;
            }).catch(error => {
                this.emailVerifiedErrorMessage = 'Unexpected error, please try again later.'
            }).finally(() => this.requestActive = false);
        },
    }
}
</script>
