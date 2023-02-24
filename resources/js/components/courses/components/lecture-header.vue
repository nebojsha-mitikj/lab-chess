<template>
    <div>
        <position-header
            v-if="lecture.type === 'exercise'"
            class="mb-4 sm:mb-8"
            :source="'course'"
            :trainer="{name: lecture.description}"
        ></position-header>

        <template v-else>
            <div class="fixed top-0 w-full z-40 bg-white">
                <div class="mb-2 xl:mb-3 mx-auto flex justify-center align-center mt-4 xl:mt-7 max-w-4xl">
                    <a href="/courses" class="text-3xl text-gray-400">
                        <uil-times style="margin-left: 4px"></uil-times>
                    </a>
                    <div class="w-full px-3">
                        <my-progress-bar
                            :percent="(highestReachedStep - 1) * 100 / stepCount"
                            :height="16"
                            :color="getPrimary">
                        </my-progress-bar>
                    </div>
                </div>
            </div>
            <div class="h-16 sm:h-20"></div>
        </template>

    </div>
</template>

<script>

import MyProgressBar from "../../assets/my-progress-bar";
import {UilTimes} from '@iconscout/vue-unicons';
import PositionHeader from "../../trainer/components/position-header";

export default {
    name: "lecture-header",
    props: ['highestReachedStep', 'stepCount', 'lecture'],
    components: {PositionHeader, MyProgressBar, UilTimes}
}

</script>
