<template>
    <input type="file" ref="uploader" @change="onFileChange" accept="image/*" class="hidden">
</template>

<script>
export default {
    name: "image-upload",

    props: ['sendRequest'],

    data(){
        return {
            selectedFile: null,
        }
    },

    methods: {
        /**
         * Emit file and send request to update profile picture if needed.
         * @param e
         */
        onFileChange(e){
            this.$emit('fileChange', e.target.files[0]);
            this.$emit('hidefiletoolarge');
            if(this.sendRequest){
                this.selectedFile = e.target.files[0];
                if(this.selectedFile.size / 1024 / 1024 > 2){
                    this.$emit('showfiletoolarge');
                    return;
                }
                let settings = { headers: { 'content-type': 'multipart/form-data' } };
                let data = new FormData();
                data.append('profile_picture_url', this.selectedFile, this.selectedFile.name)
                axios.post('/api/settings/updateProfilePicture', data, settings).then(res => {
                    if(res.data.status === 'success'){
                        this.$emit('success',res.data.url);
                    }
                });
            }
        },

        /**
         * Upload file.
         */
        click(){
            this.$refs.uploader.click();
        }
    }
}
</script>
