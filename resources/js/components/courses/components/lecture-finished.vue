<template>
    <div class="flex h-65vh justify-center align-center">
        <div class="text-center">
            <div class="relative mb-3 progress-width mx-auto">
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
            <p class="text-gray-700 text-lg">Lecture finished <span class="text-primary font-bold">+{{ earnedXp }} XP</span>
            </p>
        </div>
    </div>
</template>

<script>
export default {
    name: "lecture-finished",

    props: ['progressData', 'animateLastStep'],

    mounted(){
        this.animatePercentage();
        this.animateXpIncrement();
        this.$emit('disableAnimateLastStep');
    },

    computed: {

        /**
         * Is daily goal reached.
         * @returns boolean
         */
        dailyGoalIsReached(){
            return this.progressData.goal_status !== 'not-reached';
        },

        /**
         * Header text.
         */
        headerText(){
            let xpAwayFromReachingGoal = this.progressData.needed_xp;
            if (this.progressData.total_xp >= this.progressData.needed_xp)
                xpAwayFromReachingGoal = 0;
            else
                xpAwayFromReachingGoal = this.progressData.needed_xp - this.progressData.total_xp;
            if(xpAwayFromReachingGoal === 0) return 'You\'ve reached your daily goal';
            return 'You are '+xpAwayFromReachingGoal+' XP away from reaching your daily goal';
        },

        /**
         * Get goal count.
         * @returns integer
         */
        goalCount(){
            return this.progressData.goal_count;
        }

    },

    methods: {

        /**
         * Animate progress bar percentage.
         */
        animatePercentage(){
            let addXp = this.progressData.added_xp;
            let maximumXp = this.progressData.needed_xp
            let previousXp = this.progressData.total_xp - addXp;
            if(previousXp < 0) previousXp = 0;
            let animateToXp = maximumXp;
            if((previousXp + addXp) < maximumXp) animateToXp = previousXp + addXp;
            let previousXpPercentage = 100 * previousXp / maximumXp;
            let animateToXpPercentage = 100 * animateToXp / maximumXp;

            if(this.animateLastStep){
                this.percentage = previousXpPercentage;
                let self = this;
                let interval = setInterval(function() {
                    if (self.percentage >= animateToXpPercentage) clearInterval(interval);
                    self.percentage++;
                }, 25);
            }else{
                this.percentage = animateToXpPercentage;
            }

        },

        /**
         * Animate XP Increment.
         */
        animateXpIncrement(){
            let self = this;
            if(this.animateLastStep){
                let interval = setInterval(function() {
                    if (self.earnedXp >= 10) clearInterval(interval);
                    if(self.earnedXp < 10) ++self.earnedXp;
                }, 100);
            }else{
                this.earnedXp = 10;
            }
        }
    },

    data(){
        return {
            percentage: 0,
            earnedXp: 0
        }
    }

}
</script>

<style scoped>
.h-65vh {
    height: 65vh;
}
.progress-width {
    max-width: 250px;
    width: 100%;
}
</style>
