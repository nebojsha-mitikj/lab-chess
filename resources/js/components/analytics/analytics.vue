<template>
    <div class="max-w-6xl mx-auto py-4 sm:py-8 px-3 text-sm text-gray-700">

        <div class="grid grid-cols-12 mb-6 gap-y-6">

            <div class="col-span-12 mt-1">
                <div class="flex justify-center mt-2 align-center">
                    <p>Days: </p>
                    <template v-for="(days, index) in [7,14,30]">
                        <p class="mx-1 sm:mx-2 inline-block cursor-pointer" @click="selectDays(days)" :class="{'underline': selectedDays === days}">
                            Last {{ days }}
                        </p>
                    </template>
                </div>
            </div>

            <!-- Landing Page Views -->
            <div class="col-span-12">
                <h2>Landing page views - {{totalViews}}</h2>
                <div class="w-full px-2 py-6">
                    <my-linear-chart :array="viewsSum" :names="datesArray" ref="views"></my-linear-chart>
                </div>
            </div>

            <!-- Sign Ups -->
            <div class="col-span-12">
                <h2>Sign ups - {{totalSignUps}}</h2>
                <div class="w-full px-2 py-6">
                    <my-linear-chart :array="userSignUps" :names="datesArray" ref="signUps"></my-linear-chart>
                </div>
            </div>

            <!-- Sign Up Conversion Rate -->
            <div class="col-span-12">
                <h2>Sign up conversion rate</h2>
                <div class="w-full px-2 py-6">
                    <my-linear-chart :array="conversionRate" :names="datesArray" ref="conversionRate"></my-linear-chart>
                </div>
            </div>

            <!-- Experience -->
            <div class="col-span-12">
                <h2>
                    Experience - {{totalXP}}
                </h2>
                <div class="w-full px-2 py-6">
                    <my-linear-chart :array="experienceSum" :names="datesArray" ref="experience"></my-linear-chart>
                </div>
            </div>

        </div>

        <div class="mt-12 mb-3">
            <div class="relative overflow-x-auto sm:rounded-lg mt-8">
                <h2 class="pb-2">
                    Most active users
                </h2>
                <table class="w-full text-left border border-gray-200 text-gray-500 rounded-lg">
                    <thead class="bg-white">
                    <tr>
                        <th scope="col" class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Username</th>
                        <th scope="col" class="px-6 py-3">Goal reach count</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in active" class="bg-white border-b">
                            <td class="px-6 py-4" v-text="index + 1"></td>
                            <td class="px-6 py-4" v-text="user.email"></td>
                            <td class="px-6 py-4">
                                <a :href="'/profile/'+user.username" v-text="user.username"></a>
                            </td>
                            <td class="px-6 py-4" v-text="user.goal_reach_count"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</template>

<script>

import {UilCalender} from '@iconscout/vue-unicons';
import MyLinearChart from "../assets/my-linear-chart";

export default {
    name: "analytics",
    components: {MyLinearChart, UilCalender},

    props: ['users', 'experience', 'active', 'dates', 'registerViews'],

    data(){
        return {
            selectedDays: 30,
            totalSignUps: 0,
            averageConversionRate: 0,
            totalXP: 0,
            totalViews: 0,
            userSignUps: [],
            experienceSum: [],
            viewsSum: [],
            datesArray: [],
            conversionRate: []
        }
    },

    mounted(){
        this.selectDays(this.selectedDays);
    },

    methods: {
        /**
         * Format to graph data.
         * @param {array} data.
         * @returns {array}
         */
        getGraphData(data){
            let graphData = this.addZeroForEmptyDates(this.getDataForLastSelectedDays(data));
            return graphData.sort((a,b) => new Date(a.date).getTime() - new Date(b.date).getTime());
        },

        /**
         * Gets data for selected days days
         * @param {array} data
         * @returns {array}
         */
        getDataForLastSelectedDays(data){
            let graphData = [];
            for(let i = 0; i < data.length; i++){
                let row = data[i];
                let daysSince = (Date.now() - (new Date(row.date)).getTime())/1000/60/60/24;
                daysSince = Math.ceil(daysSince);
                if(daysSince <= this.selectedDays){
                    graphData.push(row);
                }
            }
            return graphData;
        },

        /**
         * Adds zero for empty dates.
         * @param {array} graphData
         * @returns {array}
         */
        addZeroForEmptyDates(graphData){
            for(let i = 0; i < this.selectedDays; i++){
                let currentDate = this.dates[i];
                let currentDateFound = false;
                for(let j = 0; j < graphData.length; j++){
                    if(graphData[j].date === currentDate){
                        currentDateFound = true;
                        break;
                    }
                }
                if(!currentDateFound) graphData.push({
                    date: currentDate,
                    total: 0
                });
            }
            return graphData;
        },

        /**
         * Change days
         * @param {Integer} days
         */
        selectDays(days){
            this.selectedDays = days;
            this.datesArray = this.getGraphData(this.users).map(a => a.date.slice(-2));
            this.totalSignUps = this.totalXP = this.totalViews = this.averageConversionRate = 0;
            this.userSignUps = this.getGraphData(this.users).map(a => a.total);
            this.viewsSum = this.getGraphData(this.registerViews).map(a => a.total);
            this.experienceSum = this.getGraphData(this.experience).map(a => a.total);
            this.conversionRate = [];
            for(let i = 0; i < this.userSignUps.length; i++){
                if(Number.parseInt(this.viewsSum[i]) === 0 || this.userSignUps[i] === 0){
                    this.conversionRate.push(0);
                }else{
                    this.conversionRate.push(Number.parseInt(
                        this.userSignUps[i] * 100 / Number.parseInt(this.viewsSum[i])
                    ));
                }
            }
            for(let i = 0; i < this.conversionRate.length; i++) this.averageConversionRate += this.conversionRate[i];
            for(let i = 0; i < this.userSignUps.length; i++) this.totalSignUps += this.userSignUps[i];
            for(let i = 0; i < this.experienceSum.length; i++) this.totalXP += Number.parseInt(this.experienceSum[i]);
            for(let i = 0; i < this.viewsSum.length; i++) this.totalViews += Number.parseInt(this.viewsSum[i]);
            this.averageConversionRate = Number.parseInt(this.averageConversionRate / this.conversionRate.length);
            this.$nextTick(function () {
                this.$refs.signUps.drawCanvas();
                this.$refs.conversionRate.drawCanvas();
                this.$refs.views.drawCanvas();
                this.$refs.experience.drawCanvas();
            });
        },

    }
}
</script>

<style scoped>
    #tabs p {
        color: #3D96F2;
        cursor: pointer;
        margin-left: 10px;
        margin-right: 10px;
        display: inline-block;
    }
    #tabs p:hover {
        text-decoration: underline;
    }
</style>
