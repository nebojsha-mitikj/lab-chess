<template>
    <div class="container mt-5 mb-12 max-w-xl mx-auto">

        <p class="text-gray-800">Your email address<span class="text-red-500">*</span></p>
        <input
            type="email"
            v-model="email"
            ref="email"
            class="rounded-md border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30 w-full"
        >
        <p v-for="emailError in emailErrors" class="text-red-500 text-sm" v-text="emailError"></p>

        <p class="mt-4 text-gray-800">Subject<span class="text-red-500">*</span></p>
        <input
            type="text"
            v-model="subject"
            class="rounded-md border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30 w-full"
        >
        <p v-for="subjectError in subjectErrors" class="text-red-500 text-sm" v-text="subjectError"></p>

        <p class="mt-4 text-gray-800">Message<span class="text-red-500">*</span></p>
        <textarea rows="4" v-model="message" class="rounded-md min-w-full border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30"></textarea>
        <p v-for="messageError in messageErrors" class="text-red-500 text-sm" v-text="messageError"></p>

        <p class="mt-4">
            <span class="text-gray-800">Attachment</span>
            <button @click="$refs.uploader.click()" class="w-full bg-white button sub-button">
                Choose File
            </button>
            <label class="text-gray-600 text-sm">5MB maximum size.</label>
        </p>
        <p v-if="selectedFile != null" class="mt-1">
            Selected file: <span class="font-semibold">{{selectedFile.name}}</span>
        </p>
        <p v-for="selectedFileError in selectedFileErrors" class="text-red-500 text-sm" v-text="selectedFileError"></p>
        <input type="file" ref="uploader" @change="onFileChange" accept="image/*" class="hidden">

        <button class="button w-full main-button mt-8 flex justify-center align-center" :class="{'main-button-disabled': requestIsActive}" :disabled="requestIsActive" @click="submit">
            <my-spinner class="inline float-left mr-3" color="white" size="16px" v-if="requestIsActive"></my-spinner>
            Submit Message
            <uil-message class="ml-3"></uil-message>
        </button>
        <p class="text-green-600 mt-2" v-text="successMessage" v-if="successMessage.length > 0"></p>

    </div>
</template>

<script>
import MySpinner from "../assets/my-spinner";
export default {
    name: "contact",
    components: {MySpinner},
    data(){
        return {
            successMessage: '',
            email: '',
            emailErrors: [],
            subject: '',
            subjectErrors: [],
            message: '',
            messageErrors: [],
            selectedFile: null,
            selectedFileErrors: [],
            requestIsActive: false,
        }
    },

    mounted(){
        this.$refs.email.focus();
    },

    methods: {
        /**
         * Add attachment.
         * @param e
         */
        onFileChange(e){
            if(e.target.files[0] == null) return;
            this.selectedFileErrors = [];

            if((e.target.files[0].size / (1024*1024)) > 5){
                this.$refs.uploader.value = '';
                this.selectedFile = null;
                this.selectedFileErrors = ['File size is too large.'];
                return;
            }

            this.selectedFile = e.target.files[0];
        },

        /**
         * Submit Contact.
         */
        submit(){
            this.successMessage = [];
            this.emailErrors = [];
            this.subjectErrors = [];
            this.messageErrors = [];
            this.selectedFileErrors = [];

            let errors = false;

            if(this.email.length === 0){
                this.emailErrors = ['Email address is required.'];
                errors = true;
            }

            if(this.subject.length === 0){
                this.subjectErrors = ['Subject is required.'];
                errors = true;
            }

            if(this.message.length === 0){
                this.messageErrors = ['Message is required.'];
                errors = true;
            }

            if(this.email.length > 0 && !this.emailIsValid(this.email)){
                this.emailErrors = ['Invalid email address.'];
                errors = true;
            }

            if(errors) return;

            let settings = { headers: { 'content-type': 'multipart/form-data' }};
            let data = new FormData();

            if(this.selectedFile != null){
                data.append('attachment', this.selectedFile, this.selectedFile.name);
            }

            data.append('email', this.email);
            data.append('subject', this.subject);
            data.append('message', this.message);

            this.requestIsActive = true;

            axios.post('/api/support/contact', data, settings).then(result => {
                this.successMessage = result.data.message;
            }).catch(error => {
                if(error.response.data.errors != null){
                    let errors = error.response.data.errors;
                    this.emailErrors = errors.email != null ? errors.email : [];
                    this.subjectErrors = errors.subject != null ? errors.subject : [];
                    this.messageErrors = errors.message != null ? errors.message : [];
                    this.selectedFileErrors = errors.attachment != null ? errors.attachment : [];
                }
            }).finally(() => this.requestIsActive = false);
        },

        /**
         * Check if email is valid.
         * @param {String} email
         */
        emailIsValid(email){
            const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(String(email).toLowerCase());
        },
    }
}
</script>
