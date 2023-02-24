<template>
    <div class="max-w-xl mx-auto">
        <div v-for="(course, index) in courses" class="mx-3" :class="{
            'my-5': index > 0 && index < courses.length - 1
        }">
            <a href="/register" class="float-left ml-1 cursor-pointer text-gray-700 hover:text-gray-500 transition">{{ course.name }}</a>
            <p class="float-right text-sm mr-1 text-gray-700" v-text="formatPercentage(course.finished_lectures, course.total_lectures) + '%'"></p>
            <my-progress-bar class="clear-both" :percent="100*course.finished_lectures / course.total_lectures" :height="16" :color="getPrimary"></my-progress-bar>
        </div>
        <div class="text-left mt-3">
            <a href="/register" class="text-md sm:text-lg text-blue-500 hover:text-blue-400 transition">
                <uil-plus class="inline" style="margin-top: -3px"></uil-plus> Add courses
            </a>
        </div>
    </div>
</template>

<script>
import percentageMixin from "../assets/mixins/percentageMixin";
import MyProgressBar from "../assets/my-progress-bar";
import { UilPlus } from '@iconscout/vue-unicons';

export default {
    name: "guest-courses",
    mixins: [percentageMixin],
    components: {MyProgressBar, UilPlus},
    data(){
        return {
            courses: [
                {
                    name: "Pawn Endgames",
                    finished_lectures: 45,
                    total_lectures: 57
                },
                {
                    name: "Bishop Endgames",
                    finished_lectures: 30,
                    total_lectures: 36
                },
                {
                    name: "Knight Endgames",
                    finished_lectures: 36,
                    total_lectures: 36
                },
                {
                    name: "Pieces Endgames",
                    finished_lectures: 16,
                    total_lectures: 36
                },
                {
                    name: "Rook Endgames",
                    finished_lectures: 25,
                    total_lectures: 36
                }
            ]
        }
    }
}
</script>
