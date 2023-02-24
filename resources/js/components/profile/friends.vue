<template>
    <div>
        <h2 class="subheader text-gray-700">
            <img :src="'/images/icons/friendship.png'" class="w-6 inline mr-1" style="margin-top: -5px">
            Friends
        </h2>

        <find-friends :profile="profile" :user="user" @redirectToProfile="redirectToProfile"></find-friends>

        <div class="w-full border-solid border-2 rounded border-gray-200 mt-7">
            <div class="grid grid-cols-2 text-center no-select">
                <p
                    :class="{
                        'border-solid border-b-2 border-primary text-primary': active === 'following',
                        'my-border': active !== 'following'
                    }"
                    class="text-gray-600 font-semibold py-3 cursor-pointer br-custom"
                    @click="active = 'following'"
                >
                    Following
                </p>
                <p
                    :class="{
                        'border-solid border-b-2 border-primary text-primary': active === 'followers',
                        'my-border': active !== 'followers'
                    }"
                    class="text-gray-600 font-semibold py-3 cursor-pointer bl-custom"
                    @click="active = 'followers'"
                >
                    Followers
                </p>
            </div>

            <!-- Following -->
            <div v-if="active === 'following'">
                <p class="text-gray-600 text-center py-7" v-if="following.length === 0 && !followingRequestIsActive">
                    <template v-if="profile.id === user.id">
                        You are not following anyone.
                    </template>
                    <template v-else>
                        {{ profile.username }} is not following anyone.
                    </template>
                </p>
                <div
                    class="max-h-48"
                    :class="{'overflow-y-scroll': following.length > 3}"
                    v-if="following.length > 0 || followingRequestIsActive"
                    @scroll="onScroll('following', $event)"
                >
                    <div
                        @click="redirectToProfile(follow.username)"
                        class="flex align-center p-3 no-select cursor-pointer hover:bg-gray-100"
                        v-for="follow in following"
                    >
                        <img
                            alt="labchess"
                            :src="follow.profile_picture_url != null ? follow.profile_picture_url : defaultImage"
                            class="w-10 h-10 object-cover rounded-full"
                        >
                        <p class="ml-2 text-gray-600">{{follow.username}}</p>
                    </div>
                    <div class="pb-0.5 pt-2.5 w-full mx-auto text-center" v-if="followingRequestIsActive">
                        <my-spinner :color="getPrimary" class="mx-auto" size="23px"></my-spinner>
                    </div>
                </div>
            </div>

            <!-- Followers -->
            <div v-if="active === 'followers'">
                <p class="text-gray-600 text-center py-7" v-if="followers.length === 0 && !followersRequestIsActive">
                    <template v-if="profile.id === user.id">
                        No one is following you yet.
                    </template>
                    <template v-else>
                        No one is following {{ profile.username }} yet.
                    </template>
                </p>
                <div
                    class="max-h-48"
                    :class="{'overflow-y-scroll': followers.length > 3}"
                    v-if="followers.length > 0 || followersRequestIsActive"
                    @scroll="onScroll('followers', $event)"
                >
                    <div
                        @click="redirectToProfile(follower.username)"
                        class="flex align-center p-3 no-select cursor-pointer hover:bg-gray-100"
                        v-for="follower in followers"
                    >
                        <img
                            :src="follower.profile_picture_url != null ? follower.profile_picture_url : defaultImage"
                            alt="labchess"
                            class="w-10 h-10 object-cover rounded-full"
                        >
                        <p class="ml-2 text-gray-600">{{follower.username}}</p>
                    </div>
                    <div class="pb-0.5 pt-2.5 w-full mx-auto text-center" v-if="followersRequestIsActive">
                        <my-spinner :color="getPrimary" class="mx-auto" size="23px"></my-spinner>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>
import { UilUsersAlt } from '@iconscout/vue-unicons';
import MySpinner from "../assets/my-spinner";
import FindFriends from "./find-friends";

export default {
    name: "friends",
    components: {FindFriends, MySpinner, UilUsersAlt},
    props: ['profile'],

    computed: {
        user: () => window.user,
    },

    /**
     * Get the first N followers and following
     */
    created() {
        this.read('following');
        this.read('followers');
    },

    data(){
        return {
            active: 'following',

            followers: [],
            followersPage: 1,
            followersRequestIsActive: false,

            following: [],
            followingPage: 1,
            followingRequestIsActive: false,

            defaultImage: 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/LabChessProfile.svg'
        }
    },

    methods: {
        /**
         * Check if there are more than 3 followers/following
         * Called from find-friends.vue component.
         * @returns boolean
         */
        moreThanThree(){
            return this.active === 'following' ? this.following.length >= 3 : this.followers.length >= 3;
        },

        /**
         * Refresh followers.
         */
        refreshFollowers(){
            this.followersPage = 1;
            this.followers = [];
            this.read('followers');
        },

        /**
         * Redirect to specific profile
         * @param username
         */
        redirectToProfile(username){
            window.location.href = '/profile/'+username;
        },

        /**
         * User is scrolling.
         * @param type
         */
        onScroll(type, { target: {scrollTop, clientHeight, scrollHeight}}){
            if (scrollTop + clientHeight >= scrollHeight) {
                if(type === 'following' && !this.followingRequestIsActive && this.followingPage != null){
                    this.read(type);
                }else if(type === 'followers' && !this.followersRequestIsActive && this.followersPage != null){
                    this.read(type);
                }
            }
        },

        /**
         * Read followers/following.
         * @param {String} type
         */
        read(type){
            if(type === 'followers') this.followersRequestIsActive = true;
            if(type === 'following') this.followingRequestIsActive = true;

            axios.get('/api/user/'+type, {
                params: {
                    username: this.profile.username,
                    page: type === 'following' ? this.followingPage : this.followersPage
                }
            }).then(res => {
                if(res.data.hasMore === true){
                    type === 'following' ? ++this.followingPage : ++this.followersPage;
                }else{
                    type === 'following' ? this.followingPage = null : this.followersPage = null;
                }
                if(type === 'following') {
                    this.following = this.following.concat(res.data.following.data);
                }else if(type === 'followers'){
                    this.followers = this.followers.concat(res.data.followers.data);
                }
            }).finally(() => {
                if(type === 'following') {
                    this.followingRequestIsActive = false;
                }else if(type === 'followers'){
                    this.followersRequestIsActive = false;
                }
            });
        }
    }
}
</script>
<style scoped>
    .active {border-bottom: 2px solid #4F46E5 !important;}
    .my-border {border-bottom: 2px solid #E5E7EB;}
    .br-custom {border-right: 1px solid #E5E7EB !important;}
    .bl-custom {border-left: 1px solid #E5E7EB !important;}
</style>
