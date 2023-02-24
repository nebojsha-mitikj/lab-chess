<template>
    <div class="max-w-4xl mx-auto">
        <h1 class="text-center header text-gray-700">
            <!--<img :src="'/images/icons/target.png'" class="w-7 inline" style="margin-top: -5px">-->
            Endgame Trainer
        </h1>

        <div class="mb-1 mt-4 sm:mt-1 max-w-3xl mx-auto">
            <p class="float-left ml-1 text-gray-700">Your Total Progress:</p>
            <p class="float-right mr-1 text-gray-700">{{ totalProgress }}%</p>
            <my-progress-bar :percent="totalProgress" :height="13" class="clear-both" :color="getPrimary">
            </my-progress-bar>
        </div>

        <div class="grid grid-cols-12 mt-6 sm:mt-9 mb-2 sm:mb-4 gap-x-3 gap-y-3 sm:gap-x-5 sm:gap-y-5 md:gap-x-7 md:gap-y-5">

            <a
                :href="'/trainer/'+row.code+'/1'"
                class="col-span-6 cursor-pointer md:col-span-4 hover:bg-gray-100 border-2 border-gray-100 rounded-lg max-w-xs w-full mx-auto"
                v-for="row in trainers" :key="row.title"
            >
                <div class="w-full pt-3 pb-1">
                    <p class="text-center text-sm text-gray-700 font-semibold sm:text-base" v-text="row.name"></p>
                </div>

                <div class="px-2 pb-3">
                    <div class="flex justify-center py-2">
                        <img
                            v-for="image in row.pieces.split('')"
                            :key="image" :src="'/images/pieces/'+image+'.svg'"
                            :alt="'labchess ' + row.name + ' Image'"
                            class="w-14 mt-1"
                        >
                    </div>

                    <div>
                        <p
                            class="text-center sm:text-right mr-1 text-sm text-gray-700"
                            v-text="formatPercentage(row.finished_positions,row.total_positions) + '%'">
                        </p>
                        <my-progress-bar
                            :percent="(row.finished_positions * 100 / row.total_positions)"
                            :height="13"
                            class="clear-both mb-2"
                            :color="getPrimary"
                        ></my-progress-bar>
                    </div>
                </div>

            </a>

        </div>
    </div>
</template>

<script>
import MyProgressBar from "../assets/my-progress-bar";
import percentageMixin from "../assets/mixins/percentageMixin";
import {UilCrosshair} from "@iconscout/vue-unicons";

export default {
    name: "trainer-main",
    components: {MyProgressBar, UilCrosshair},
    mixins: [percentageMixin],
    props: ['trainers'],
    computed: {
        /**
         * Calculate total progress percentage.
         * @returns {string}
         */
        totalProgress(){
            let totalPositions = 0;
            let finishedPositions = 0;
            this.trainers.forEach(e => {
                totalPositions += e.total_positions;
                finishedPositions += e.finished_positions;
            });
            return this.formatPercentage(finishedPositions, totalPositions);
        }
    }
}
</script>
