<template>
    <div class="absolute w-full h-full bg-white z-50">

        <div class="flex h-86 justify-center align-center">
            <div class="text-center">
                <div class="relative p-0 bg-transparent w-full mx-auto mb-3" style="max-width: 250px">
                    <svg viewBox="0 0 36 36">
                        <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                              fill="none"
                              stroke="#E5E7EB"
                              stroke-width="2"
                              stroke-dasharray="100, 100"
                        />
                        <path
                            class="dailyGoalPath"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                            fill="none"
                            stroke="#3D96F2"
                            stroke-linecap="round"
                            stroke-width="2"
                            :stroke-dasharray="percentage+', 100'"
                        />
                    </svg>
                    <div class="absolute" style="top: 50%;left: 50%;transform: translateX(-50%) translateY(-50%);">
                        <p
                            class="text-6xl font-bold no-select"
                            :class="{'text-primary': dailyGoalIsReached, 'text-gray-400': !dailyGoalIsReached}"
                            v-text="goalCount"
                        ></p>
                    </div>
                </div>
                <p class="font-semibold text-xl" v-text="headerText"></p>
                <p class="text-gray-700 text-lg">Position finished <span class="text-primary font-bold">+{{ earnedXp }} XP</span></p>
                <p class="text-gray-700 text-lg" v-if="hasBonus">No take back bonus <span class="text-primary font-bold">+{{ bonusXp }} XP</span></p>
            </div>
        </div>

        <div class="fixed bottom-0 w-full bg-white border-t border-gray-300">
            <div class="my-8 sm:my-10 px-3 relative mx-auto max-w-3xl" :class="{'text-center': nextPosition == null}">
                <template v-if="nextPosition == null">
                    <button class="button main-button" @click="$emit('reviewPosition')">Review Position</button>
                </template>
                <template v-else>
                    <button class="button sub-button" @click="$emit('reviewPosition')">Review Position</button>
                    <button class="button main-button float-right" @click="$emit('nextPosition')">Next Position</button>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
import soundMixin from "../chess/mixins/soundMixin";

export default {
    name: "trainer-position-finished",
    props: ['nextPosition', 'finishedPositionData'],

    mixins: [
        soundMixin
    ],

    computed: {

        dailyGoalIsReached(){
            return this.finishedPositionData.goal_status !== 'not-reached';
        },

        hasBonus(){
            return this.finishedPositionData.added_xp === 15;
        },

        headerText(){
            let xpAwayFromReachingGoal = this.finishedPositionData.needed_xp;
            if (this.finishedPositionData.total_xp >= this.finishedPositionData.needed_xp)
                xpAwayFromReachingGoal = 0;
            else
                xpAwayFromReachingGoal = this.finishedPositionData.needed_xp - this.finishedPositionData.total_xp;
            if(xpAwayFromReachingGoal === 0) return 'You\'ve reached your daily goal';
            return 'You are '+xpAwayFromReachingGoal+' XP away from reaching your daily goal';
        },

        goalCount(){
            return this.finishedPositionData.goal_count;
        }
    },

    mounted(){
        this.finishedPositionData.goal_status === 'just-reached' ? this.sound.goalReached.play() : this.sound.success.play();
        this.animatePercentage();
        this.animateXpIncrement();
    },

    methods: {
        /**
         * Animate progress bar percentage.
         */
        animatePercentage(){
            let addXp = this.finishedPositionData.added_xp;
            let maximumXp = this.finishedPositionData.needed_xp
            let previousXp = this.finishedPositionData.total_xp - addXp;
            if(previousXp < 0) previousXp = 0;
            let animateToXp = maximumXp;
            if((previousXp + addXp) < maximumXp) animateToXp = previousXp + addXp;
            let previousXpPercentage = 100 * previousXp / maximumXp;
            let animateToXpPercentage = 100 * animateToXp / maximumXp;
            this.percentage = previousXpPercentage;
            let self = this;
            let interval = setInterval(function() {
                if (self.percentage >= animateToXpPercentage) clearInterval(interval);
                self.percentage++;
            }, 25);
        },

        /**
         * Animate XP Increment.
         */
        animateXpIncrement(){
            let self = this;
            let count = 0;
            let interval = setInterval(function() {
                if (self.earnedXp >= 10) clearInterval(interval);
                if(self.bonusXp < 5 && ++count % 2 === 0) ++self.bonusXp;
                if(self.earnedXp < 10) ++self.earnedXp;
            }, 100);
        }
    },

    data(){
        return {
            percentage: 0,
            earnedXp: 0,
            bonusXp: 0,
        }
    }
}
</script>

<style scoped>
    .h-86 {
        height: 86%;
    }
</style>
