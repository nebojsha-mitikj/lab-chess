<template>
    <div>
        <h1 class="header">Daily Goal</h1>

        <div class="mt-5 w-full lg:w-2/4 border border-gray-200">
            <div
                v-for="(dailyGoal,index) in dailyGoals"
                @click="changeGoal(dailyGoal)"
                class="block clear-both p-3 grid grid-cols-2  cursor-pointer border-gray-200 no-select"
                :class="{
                    'bg-primary text-white': dailyGoal.level === selectedGoal,
                    'hover:bg-gray-100': dailyGoal.level !== selectedGoal,
                    'border-b': index+1 !== dailyGoals.length
                }"
            >
                <p class="text-left font-semibold" v-text="dailyGoal.level"></p>
                <p class="text-right" v-text="dailyGoal.experience + ' XP per day'"></p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'coach',

    props: ['dailyGoal', 'userConfiguration'],

    data(){
        return {
            selectedGoal: null,
            dailyGoals: []
        }
    },

    created(){
        this.dailyGoals = this.dailyGoal;
        this.selectedGoal = this.dailyGoals.filter(item => {
            return item.id === this.userConfiguration.daily_goal_id
        })[0].level;
    },

    methods: {
        /**
         * Change daily goal for user.
         * {object}
         * @param dailyGoal
         */
        changeGoal(dailyGoal){
            this.selectedGoal = dailyGoal.level;
            axios.put('/api/settings/updateGoal',{'daily_goal_id': dailyGoal.id});
            this.changeDailyGoalSeriesData(dailyGoal);
        },

        /**
         * Change daily goal series data.
         * @param {object} dailyGoal
         */
        changeDailyGoalSeriesData(dailyGoal){
            let level = document.querySelectorAll('.level');
            let experience = document.querySelectorAll('.experience');
            let userXP = document.querySelector('#userxp');
            let dailyGoalPath = document.querySelectorAll('.dailyGoalPath');
            let userExperience = Number.parseInt(userXP.getAttribute('userxp'));
            for(let i = 0; i <  level.length; i++) level[i].innerText = dailyGoal.level;
            for(let i = 0; i <  experience.length; i++) experience[i].innerText = dailyGoal.experience + ' XP';
            let temp = 0;
            if(userExperience >= dailyGoal.experience) temp = 100;
            else temp = Math.floor(userExperience * 100 / dailyGoal.experience);
            for(let i = 0; i <  dailyGoalPath.length; i++){
                dailyGoalPath[i].setAttribute('stroke-dasharray',temp+', 100');
            }
        }
    }
}
</script>
