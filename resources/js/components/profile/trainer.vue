<template>
    <div>
        <h2 class="subheader text-gray-700">
            <img :src="'/images/icons/target.png'" class="w-6 inline" style="margin-top: -5px">
            Endgame Trainer
        </h2>

        <div class="w-full rounded border-gray-200 mt-3 pb-3 md:pr-6 pr-0">
            <div v-for="trainer in trainers" class="my-5 mx-3">
                <p class="float-left ml-1 cursor-pointer text-gray-700" @click="redirectToSpecificTrainer(trainer)">
                    {{ trainer.name }}
                </p>
                <p
                    class="float-right text-sm mr-1 text-gray-700"
                    v-text="formatPercentage(trainer.finished_positions, trainer.total_positions) + '%'">
                </p>
                <my-progress-bar
                    :percent="100*trainer.finished_positions / trainer.total_positions"
                    :height="16"
                    :color="getPrimary"
                    class="clear-both"
                ></my-progress-bar>
            </div>
            <div
                v-if="trainers.length === 0"
                class="text-center py-4 sm:py-5 mb-1 bg-gray-50 rounded shadow-sm border border-gray-200"
            >
                <p class="text-gray-600 mt-0 mb-1 ml-3">
                    <template v-if="profile.id === user.id">
                        You have not started training yet<br>
                    </template>
                    <template v-else>
                        <span class="font-semibold">{{ profile.username }}</span> has not started training yet<br>
                    </template>
                </p>
                <a href="/trainer" v-if="profile.id === user.id" class="button main-button text-sm inline-block">
                    <uil-plus style="margin-top: -2px"  class="inline"></uil-plus> Start training now
                </a>
            </div>

            <a href="/trainer" v-if="profile.id === user.id" class="ml-3 text-md sm:text-lg hover:underline text-blue-500">
                <uil-plus class="inline" style="margin-top: -3px"></uil-plus> Continue training
            </a>
        </div>
    </div>
</template>

<script>
import MyProgressBar from "../assets/my-progress-bar";
import {UilBookOpen, UilPlus, UilCrosshair} from "@iconscout/vue-unicons";
import percentageMixin from "../assets/mixins/percentageMixin";

export default {
    name: "trainer",
    components: {MyProgressBar, UilBookOpen, UilPlus, UilCrosshair},
    mixins: [percentageMixin],

    props: ['profile','trainers'],

    computed: {
        user: () => window.user,
    },

    mounted(){
        this.$emit('stopspin');
    },

    methods: {
        /**
         * Redirect to specific trainer.
         * @param {Object} trainer
         */
        redirectToSpecificTrainer(trainer){
            window.location.href = '/trainer/'+trainer.code+'/1';
        }
    },
}
</script>
