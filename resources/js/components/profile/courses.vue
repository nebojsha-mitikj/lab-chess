<template>
    <div>
        <h2 class="subheader text-gray-700">
            <img :src="'/images/icons/open-book.png'" class="w-5 inline" style="margin-top: -5px">
            Endgame Courses
        </h2>

        <div class="w-full rounded border-gray-200 mt-3 pb-3 md:pr-6 pr-0">
            <div v-for="course in courses" class="my-5 mx-3">
                <p class="float-left ml-1 cursor-pointer text-gray-700" @click="redirectToCourses">{{ course.name }}</p>
                <p
                    class="float-right text-sm mr-1 text-gray-700"
                    v-text="formatPercentage(course.finished_lectures, course.total_lectures) + '%'"
                ></p>
                <my-progress-bar
                    :percent="100*course.finished_lectures / course.total_lectures"
                    :height="16"
                    :color="getPrimary"
                    class="clear-both"
                ></my-progress-bar>
            </div>
            <div
                v-if="courses.length === 0"
                class="text-center py-4 sm:py-5 mb-1 bg-gray-50 rounded shadow-sm border border-gray-200"
            >
                <p class="text-gray-600 mt-0 mb-1 ml-3">
                    <template v-if="profile.id === user.id">
                        You have not enrolled in any courses yet<br>
                    </template>
                    <template v-else>
                       <span class="font-semibold">{{ profile.username }}</span> has not enrolled in any courses yet
                    </template>
                </p>
                <a href="/courses" v-if="profile.id === user.id" class="button main-button text-sm inline-block">
                    <uil-plus style="margin-top: -2px"  class="inline"></uil-plus> Start learning now
                </a>
            </div>
            <a href="/courses" v-if="profile.id === user.id" class="ml-3 text-md sm:text-lg hover:underline text-blue-500">
                <uil-plus class="inline" style="margin-top: -3px"></uil-plus> Continue learning
            </a>
        </div>
    </div>
</template>

<script>

import MyProgressBar from "../assets/my-progress-bar";
import { UilBookOpen, UilPlus } from '@iconscout/vue-unicons';
import percentageMixin from "../assets/mixins/percentageMixin";

export default {
    name: "courses",

    components: {MyProgressBar, UilBookOpen, UilPlus},

    mixins: [percentageMixin],

    props: ['profile','courses'],

    computed: {
        user: () => window.user,
    },

    methods: {
        /**
         * Redirect to courses page.
         */
        redirectToCourses(){
            window.location.href = '/courses';
        }
    },
}
</script>
