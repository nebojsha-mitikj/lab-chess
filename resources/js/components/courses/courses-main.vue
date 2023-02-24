<template>
    <div class="max-w-4xl mx-auto">

        <h1 class="text-center header flex justify-center align-center text-gray-700">
            <!--<img alt="Course Image" :src="'/images/icons/open-book.png'" class="w-7 inline mr-3" style="margin-top: -5px">-->
            Endgame Courses
        </h1>

        <div class="mb-1 mt-4 sm:mt-1 max-w-3xl mx-auto mb-5">
            <p class="float-left ml-1 text-gray-700">Your Total Progress:</p>
            <p class="float-right mr-1 text-gray-700">{{ totalProgress }}%</p>
            <my-progress-bar :percent="totalProgress" :height="13" class="clear-both" :color="getPrimary"></my-progress-bar>
        </div>

        <!-- Course -->
        <div v-for="course in courses" :key="course.id"
             class="mt-10 px-0 sm:px-4 py-4 border-2 border-gray-100 rounded-lg relative">

            <!-- Toggle Section -->
            <div class="grid grid-cols-12 w-full" @click="toggleCourse(course.id)">
                <div class="col-span-12 sm:col-span-3 md:col-span-2 cursor-pointer">
                    <circular-progress-bar
                        :percent="getFinishedLecturesCount(course.lectures) * 100 / course.lectures.length"
                        :strokeWidth="2"
                        class="mx-auto bg-transparent p-0 text-primary w-24"
                    >
                        <div class="flex justify-center">
                            <template v-for="(url,index) in course.image_url.split(',')">
                                <img
                                    :src="'/images/icons/chess-group-3/'+url"
                                    class="w-full"
                                    :class="{
                                        'mr-two-minus': course.image_url.split(',').length === 2 && index === 0,
                                        'three': course.image_url.split(',').length === 3,
                                        'mr-three-minus': course.image_url.split(',').length === 3 && index === 0,
                                        'ml-three-minus': course.image_url.split(',').length === 3 && index === 2

                                    }"
                                    :alt="course.name"
                                >
                            </template>
                        </div>
                    </circular-progress-bar>
                </div>
                <div
                    class="col-span-12 sm:col-span-9 md:col-span-10 flex align-center justify-center sm:justify-start cursor-pointer">
                    <h2 class="text-gray-700 font-semibold sm:text-lg mt-3 sm:mt-0" v-text="course.name"></h2>
                </div>
            </div>

            <!-- Counter -->
            <div class="cursor-pointer absolute right-14 top-3.5 text-gray-500 text-sm" @click="toggleCourse(course.id)">
                {{ getFinishedLecturesCount(course.lectures) }} / {{ course.lectures.length }}
            </div>

            <!-- Toggle Button -->
            <button class="cursor-pointer text-primary absolute right-2 top-1.5" @click="toggleCourse(course.id)">
                <uil-angle-down class="text-4xl" v-if="openedCourses.includes(course.id)"></uil-angle-down>
                <uil-angle-up class="text-4xl" v-else></uil-angle-up>
            </button>

            <!-- Lessons -->
            <div class="col-span-12" v-show="openedCourses.includes(course.id)">
                <div class="mt-8 mx-6">
                    <div class="grid grid-cols-12 my-6 gap-2 sm:gap-3" v-for="lectureGroup in groupLectures(course.lectures)">
                        <a
                            v-for="lecture in lectureGroup"
                            :href="getLectureURL(course.name, lecture)"
                            class="col-span-6 sm:col-span-4 rounded-lg p-3 cursor-pointer bg-gray-100 hover:bg-gray-50 transition duration-150"
                        >
                            <p
                                class="text-sm font-bold sm:text-base"
                                v-text="getLectureHeader(lecture)"
                                :class="{'text-gray-700': lecture.free || subscribed, 'text-gray-500': !lecture.free && !subscribed}"
                            ></p>
                            <div
                                class="min-h-12 h-12 block font-semibold text-sm"
                                :class="{'text-gray-700': lecture.free || subscribed, 'text-gray-500': !lecture.free && !subscribed}"
                            >
                                <p v-if="lecture.description != null && lecture.type === 'lesson'" v-text="lecture.description"></p>
                            </div>
                            <!-- Locked -->
                            <svg v-if="!lecture.free && !subscribed" height="17pt" viewBox="0 0 512 512" width="17pt" class="float-right" xmlns="http://www.w3.org/2000/svg"><path d="m141.24 247.17v-97.103c0-63.38 51.379-114.76 114.76-114.76s114.76 51.379 114.76 114.76v97.103h35.31v-97.103c0-82.881-67.188-150.07-150.07-150.07s-150.07 67.188-150.07 150.07v97.103h35.31z" fill="#959CB5"/><g fill="#7F8499"><rect x="105.93" y="211.86" width="35.31" height="35.31"/><rect x="370.76" y="211.86" width="35.31" height="35.31"/></g><path d="m88.276 264.83v172.14c0 20.562 11.772 39.474 30.481 48.002 36.964 16.852 84.872 27.029 137.24 27.029s100.28-10.177 137.24-27.029c18.709-8.529 30.481-27.441 30.481-48.002v-172.14c0-19.501-15.809-35.31-35.31-35.31h-264.83c-19.501-1e-3 -35.31 15.808-35.31 35.31z" fill="#FFDC64"/><path d="m291.31 344.28c0-22.262-20.601-39.712-43.78-34.325-12.515 2.909-22.703 12.985-25.754 25.468-3.689 15.089 2.361 28.915 13.055 36.941 2.106 1.581 3.514 3.914 3.514 6.547v26.219c0 8.794 6.009 16.947 14.69 18.358 11.061 1.799 20.62-6.692 20.62-17.415v-27.339c0-2.522 1.348-4.761 3.373-6.266 8.637-6.416 14.282-16.6 14.282-28.188z" fill="#464655"/><g fill="#FFF082"><path d="m384 291.31c7.313 0 13.241-5.929 13.241-13.241v-8.828c0-7.313-5.929-13.241-13.241-13.241-7.313 0-13.241 5.929-13.241 13.241v8.828c0 7.313 5.928 13.241 13.241 13.241z"/><path d="m384 379.59c7.313 0 13.241-5.929 13.241-13.241v-44.138c0-7.313-5.929-13.241-13.241-13.241-7.313 0-13.241 5.929-13.241 13.241v44.138c0 7.312 5.928 13.241 13.241 13.241z"/></g><path d="m170.92 484.97c-12.801-8.529-20.855-27.442-20.855-48.002v-172.14c0-19.501 10.817-35.31 24.16-35.31h-50.643c-19.501 0-35.31 15.809-35.31 35.31v172.14c0 20.562 11.772 39.474 30.482 48.002 36.963 16.852 84.871 27.03 137.24 27.03 1.768 0 3.485-0.104 5.241-0.127-34.418-0.66-65.879-10.619-90.317-26.902z" fill="#FFC850"/></svg>
                            <!-- Checked -->
                            <svg v-else-if="lecture.user_lectures.length" height="17pt" viewBox="0 0 512 512" width="17pt" class="float-right" xmlns="http://www.w3.org/2000/svg"><path d="m512 256c0 141.386719-114.613281 256-256 256s-256-114.613281-256-256 114.613281-256 256-256 256 114.613281 256 256zm0 0" fill="#6dcd01"/><path d="m411.3125 196.6875-48-48c-6.25-6.246094-16.375-6.246094-22.625 0l-116.6875 116.679688-52.6875-52.679688c-6.25-6.246094-16.375-6.246094-22.625 0l-48 48c-6.246094 6.25-6.246094 16.375 0 22.625l96 96c3 3 7.070312 4.6875 11.3125 4.6875h32c4.242188 0 8.3125-1.6875 11.3125-4.6875l160-160c6.246094-6.25 6.246094-16.375 0-22.625zm0 0" fill="#fff"/></svg>
                            <!-- Play -->
                            <svg v-else xmlns="http://www.w3.org/2000/svg" class="float-right" xmlns:xlink="http://www.w3.org/1999/xlink" style="isolation:isolate" viewBox="0 0 512 512" width="17pt" height="17pt"><defs><clipPath id="_clipPath_1vAlG2AGEjLjo2yRZJpqtJLw8oR55KEn"><rect width="512" height="512"/></clipPath></defs><g clip-path="url(#_clipPath_1vAlG2AGEjLjo2yRZJpqtJLw8oR55KEn)"><g><g><g><path d=" M 256 0 C 114.833 0 0 114.844 0 256 C 0 397.156 114.833 512 256 512 C 397.167 512 512 397.156 512 256 C 512 114.844 397.167 0 256 0 Z  M 357.771 264.969 L 208.438 360.969 C 206.688 362.104 204.667 362.667 202.667 362.667 C 200.917 362.667 199.146 362.229 197.563 361.365 C 194.125 359.49 192 355.906 192 352 L 192 160 C 192 156.094 194.125 152.51 197.563 150.635 C 200.938 148.781 205.167 148.895 208.438 151.031 L 357.771 247.031 C 360.813 248.989 362.667 252.375 362.667 256 C 362.667 259.625 360.813 263.01 357.771 264.969 Z " fill="rgb(61,150,242)"/></g></g></g></g></svg>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script>

import {UilAngleDown, UilAngleUp} from '@iconscout/vue-unicons';
import MyProgressBar from "../assets/my-progress-bar";
import percentageMixin from "../assets/mixins/percentageMixin";

export default {
    name: "courses-main",
    components: {
        MyProgressBar,
        UilAngleDown,
        UilAngleUp
    },

    props: ['courses'],
    mixins: [percentageMixin],

    data() {
        return {
            toggle: false,
            subscribed: false,
            openedCourses: []
        }
    },

    mounted(){
        if (window.subscribed !== null) this.subscribed = window.subscribed;
        this.openActiveCourse();
    },

    computed: {
        totalProgress(){
            let total = 0;
            let finished = 0;
            for(let i = 0; i < this.courses.length; i++){
                total += this.courses[i].lectures.length;
                finished += this.getFinishedLecturesCount(this.courses[i].lectures);
            }
            return this.wholeNumberOrWithTwoDecimals(finished * 100 / total);
        }
    },

    methods: {
        /**
         * Opens active course.
         */
        openActiveCourse(){
            for(let i = 0; i < this.courses.length; i++){
                let totalLectures = this.courses[i].lectures.length;
                let finishedLectures = this.getFinishedLecturesCount(this.courses[i].lectures);
                if(totalLectures !== finishedLectures){
                    this.openedCourses.push(this.courses[i].id);
                    break;
                }
            }
        },

        /**
         * Show/Hide course lectures.
         * @param {Number} courseId
         */
        toggleCourse(courseId) {
            let courseIdIndex = this.openedCourses.indexOf(courseId);
            courseIdIndex !== -1 ? this.openedCourses.splice(courseIdIndex, 1) : this.openedCourses.push(courseId);
        },

        /**
         * Groups lectures into multiple arrays by lecture number.
         * @param {array} lectures
         * @returns Object
         */
        groupLectures(lectures) {
            let data = {};
            for (let i = 0; i < lectures.length; i++) {
                let lecture = lectures[i];
                let lectureNumber = Math.floor(lecture.number);
                if (data[lectureNumber] == null) data[lectureNumber] = [];
                data[lectureNumber].push(lecture);
            }
            return data;
        },

        /**
         * How many lectures are finished.
         * @param {array} lectures
         * @returns Number
         */
        getFinishedLecturesCount(lectures){
            let finished = 0;
            for(let i = 0; i < lectures.length; i++)
                if(lectures[i].user_lectures.length > 0)
                    ++finished;
            return finished;
        },

        /**
         * Gets Lecture header.
         * @param {Object} lecture
         * @returns string
         */
        getLectureHeader(lecture) {
            return lecture.type.charAt(0).toUpperCase() + lecture.type.slice(1) + ' ' + lecture.number;
        },

        /**
         * Get Lecture URL
         * @param {String} courseName
         * @param {Object} lecture
         * @returns string
         */
        getLectureURL(courseName, lecture){
            if(!lecture.free && !this.subscribed) return '/premium';
            return '/courses/' + courseName.toLowerCase().replace(/ /g,'-') + '/' + lecture.number;
        }
    }
}
</script>

<style scoped>
.mr-two-minus {
    margin-right: -22px;
}
.mr-three-minus {
    margin-right: -18px;
}
.ml-three-minus {
    margin-left: -18px;
}
.three {
    height: 80%;
    width: 80%;
    margin-top: 10%
}
</style>
