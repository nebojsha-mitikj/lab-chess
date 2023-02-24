<template>
    <div
        class="relative mt-5"
        tabindex="0"
        @focus="setShowSearch(true)"
        @focusout="setShowSearch(false)"
        v-if="profile.id === user.id"
    >
        <!-- Search -->
        <input
            @focus="setShowSearch(true)"
            v-model="search"
            placeholder="Email, name or username"
            ref="searchFriends"
            type="text"
            class="pl-8 rounded-md w-full shadow-sm border-gray-300 focus:border-primary-light focus:ring focus:ring-primary-light focus:ring-opacity-30"
        >
        <uil-search class="absolute text-xl left-2.5 top-2.5 text-gray-400"></uil-search>

        <!-- Search Result -->
        <my-card v-if="showSearch">
            <div
                :class="{'max-h-48 h-48': !$parent.moreThanThree(), 'max-h-64 h-64': $parent.moreThanThree()}"
                class="flex justify-center align-center" v-if="searchProfiles.length === 0"
            >
                <p class="text-gray-600" v-show="!requestIsActive">No results found</p>
                <my-spinner v-show="requestIsActive" :color="getPrimary" size="20px"></my-spinner>
            </div>
            <div
                v-else
                :class="{
                    'overflow-y-scroll': searchProfiles.length > ($parent.moreThanThree() ? 4 : 3),
                    'max-h-48 h-48': !$parent.moreThanThree(),
                    'max-h-64 h-64': $parent.moreThanThree()}"
            >
                <div
                    v-for="searchProfile in searchProfiles"
                    @mousedown="mouseDown(searchProfile.username)"
                    class="flex relative align-center p-3 no-select cursor-pointer hover:bg-gray-100"
                >
                    <img
                        :src="searchProfile.profile_picture_url != null ? searchProfile.profile_picture_url : defaultImage"
                        alt="labchess"
                        class="w-10 h-10 object-cover rounded-full"
                    >
                    <p class="ml-2 text-gray-600">{{searchProfile.username}}</p>
                </div>
            </div>
        </my-card>

    </div>
</template>

<script>

import MyCard from "../assets/my-card";
import { UilSearch } from '@iconscout/vue-unicons';
import { debounce } from '../assets/debounce';
import MySpinner from "../assets/my-spinner";

export default {
    name: "find-friends",
    props: ['profile', 'user'],

    components: {MySpinner, MyCard, UilSearch},

    created() {
        this.search = '';
    },

    watch: {
        search() {
            this.findFriends();
        }
    },

    data(){
        return {
            showSearch: false,
            search: '',
            requestIsActive: false,
            searchProfiles: [],
            defaultImage: 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/LabChessProfile.svg'
        }
    },

    methods: {
        /**
         * Show/Hide search friends section
         * @param {Boolean} val
         */
        setShowSearch(val){
            this.showSearch = val;
        },

        /**
         * Search for friends
         */
        findFriends: debounce(function () {

            if(this.search.length === 0){
                this.searchProfiles = [];
                return;
            }

            this.requestIsActive = true;
            this.searchProfiles = [];

            axios.get('/api/user/search', {
                params: { search: this.search }
            }).then(res => {
                this.searchProfiles = res.data.profiles;
            }).finally(() => {
                this.requestIsActive = false;
            });

        },300),

        /**
         * On Mouse Down hide search and redirect to profile
         * @param {String} username
         */
        mouseDown(username){
            this.showSearch = false;
            this.$emit('redirectToProfile', username)
        }
    }
}
</script>
