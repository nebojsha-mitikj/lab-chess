<template>
    <div>
        <label
            v-if="!follows"
            @click="follow"
            class="ml-3 text-sm cursor-pointer button main-button inline-block"
            :class="{'main-button-disabled': requestIsActive}"
        >
            <my-spinner class="pt-1" color="white" size="16px" v-if="requestIsActive"></my-spinner>
            Follow
        </label>
        <label
            v-else
            @click="unfollow"
            class="ml-3 text-sm cursor-pointer button sub-button inline-block"
            :class="{'sub-button-disabled': requestIsActive}"
        >
            <my-spinner class="pt-0.5" color="#9CA3AF" size="16px" v-if="requestIsActive"></my-spinner>
            Following
        </label>
    </div>
</template>

<script>
import MySpinner from "../assets/my-spinner";
export default {
    name: "follow-unfollow-buttons",
    components: {MySpinner},
    props: ['profile', 'userFollows'],

    created(){
        this.user = window.user;
        this.follows = this.userFollows;
    },

    data(){
        return {
            user: null,
            follows: false,
            requestIsActive: false,
        }
    },

    methods: {
        /**
         * Follow the active profile.
         */
        follow(){
            if(this.requestIsActive) return;
            this.requestIsActive = true;
            axios.post('/api/user/follow', {'username': this.profile.username}).then(res => {
                this.follows = true;
                this.$emit('follow');
            }).finally(() => this.requestIsActive = false);
        },

        /**
         * Unfollow the active profile.
         */
        unfollow(){
            if(this.requestIsActive) return;
            this.requestIsActive = true;
            axios.post('/api/user/unfollow', {'username': this.profile.username}).then(res => {
                this.follows = false;
                this.$emit('unfollow');
            }).finally(() => this.requestIsActive = false);
        }
    }
}
</script>
