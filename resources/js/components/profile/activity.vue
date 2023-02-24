<template>
    <div>
        <h2 class="subheader text-gray-700">
            <img :src="'/images/icons/calendar.png'" class="w-6 inline mr-1" style="margin-top: -5px">
            XP Activity
        </h2>
        <div class="w-full">
            <my-linear-chart :array="experience" :names="days"></my-linear-chart>
        </div>
    </div>
</template>

<script>
import {UilCalender} from '@iconscout/vue-unicons';
import MyLinearChart from "../assets/my-linear-chart";

export default {
    name: "activity",
    components: {MyLinearChart, UilCalender},
    props: ['xpActivity'],

    mounted(){
        for(let i = 0; i < this.xpActivity.length; i++){
            this.experience.push(this.xpActivity[i][1]);
            this.days.push(this.xpActivity[i][0]);
        }
    },

    data() {
        return {
            experience: [],
            days: []
        }
    }
}
</script>
