<template>
    <div class="max-w-6.5xl mx-auto container" style="margin-top: -1.25rem">
        <!--<h1 class="text-center header mb-2 flex justify-center align-center text-gray-700">
            <img :src="'/images/icons/trophy.png'" class="w-7 inline mr-1" style="margin-top: -5px">
            Leaderboard
        </h1>-->
        <div class="rounded border-2 border-gray-100 bg-white w-full block mx-auto max-w-xl md:max-w-7xl px-6 py-4 mt-7 sm:mt-0">
            <div class="grid grid-cols-12 gap-y-3">
                <div class="col-span-12 md:col-span-4 flex">
                    <div class="m-auto">
                        <div class="w-28 h-28 mx-auto">
                            <img
                                ref="profileImage"
                                class="h-full w-full m-auto object-cover rounded-full border border-gray-200"
                                :src="this.user.profile_picture_url != null ? this.user.profile_picture_url : defaultImage"
                            />
                        </div>
                        <p class="text-center text-gray-700 text-lg font-semibold">{{ this.user.username }}</p>
                        <p class="text-center text-primary-dark text-sm">Level {{userLevel.level}} - {{userLevel.name}}</p>
                        <p class="text-center text-gray-500 text-sm" v-if="userLevels.length > userLevel.level">
                            {{xpNeededUntilLevelUp}}xp until level up
                        </p>
                        <p class="text-center text-gray-500 text-sm">Rank: #{{userRank}}</p>
                    </div>
                </div>

                <div class="col-span-12 sm:col-span-6 md:col-span-4" v-for="(halfLevels, halfLevelIndex) in dividedLevels">
                    <div
                        v-for="(levelObject, index) in halfLevels"
                        class="flex"
                        :class="{
                            'mt-3 mb-0 sm:mt-3 sm:mb-3': halfLevelIndex === 0 && index === halfLevels.length - 1,
                            'mt-0 mb-3 sm:mt-3 sm:mb-3': halfLevelIndex === 1 && index === 0,
                            'mt-3 mb-3': (halfLevelIndex !== 0 || index !== halfLevels.length - 1) &&  (halfLevelIndex !== 1 || index !== 0)
                        }"
                    >
                        <div class="my-auto mx-2 bg-gray-100 rounded-full w-8 h-8 flex" :class="{'bg-primary': levelObject.level === userLevel.level}">
                            <div
                                v-if="levelObject.level === userLevel.level || levelObject.level < userLevel.level"
                                class="m-auto"
                                :class="{
                                    'text-white': levelObject.level === userLevel.level,
                                    'text-gray-700': levelObject.level < userLevel.level
                                }"
                            >
                                {{ levelObject.level }}
                            </div>
                            <img v-else :src="'/images/icons/padlock.png'" class="h-6 m-auto" alt="Padlock">
                        </div>
                        <div class="mt-auto">
                            <p
                                style="margin-bottom: -5px;" class="my-0 text-gray-700" :class="{
                                    'text-primary-dark': levelObject.level === userLevel.level
                                }"
                            >
                                Level {{ levelObject.level }} - {{ levelObject.name }}
                            </p>
                            <span class="text-sm text-gray-500">{{ levelObject.user_percentage }}% of members</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-x-7 gap-y-7 place-items-center mt-6 sm:mt-8">
            <div class="col-span-12 lg:col-span-4 max-w-xl bg-white w-full h-full rounded border-2 border-gray-100" v-for="board in boards">
                <div class="w-full py-3">
                    <h2 class="px-2 text-gray-700 text-center" v-text="board.name"></h2>
                </div>

                <div class="w-1/4 mx-auto border-t border-gray-200"></div>

                <a :href="'/profile/'+leader.username" v-for="(leader,index) in board.leaders">
                    <div class="flex align-center p-3 cursor-pointer hover:bg-gray-100">

                        <template v-if="index+1 <= 3">
                            <img :src="'/images/icons/medals/'+ (index+1) +'.png'" class="w-7" alt="Medal">
                        </template>

                        <p v-else class="text-gray-500 text-sm mx-2 mt-0.5" v-text="index + 1"></p>

                        <img
                            :src="leader.profile_picture_url != null ? leader.profile_picture_url : defaultImage"
                            alt="Profile Image"
                            class="w-10 h-10 object-cover rounded-full"
                        >
                        <p class="ml-2 text-gray-700" v-text="leader.username"></p>
                        <p class="ml-auto text-gray-500 text-sm" v-text="leader.experience + 'xp'"></p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "leaderboard",

    props: ['boards', 'userLevels', 'userRank'],

    computed: {
        user: () => window.user,
        dividedLevels(){
            const middleIndex = Math.ceil(this.userLevels.length / 2);
            return [this.userLevels.splice(0, middleIndex), this.userLevels.splice(-middleIndex)]
        }
    },

    created(){
        let previousUserLevel = null;
        for(let i = this.userLevels.length - 1; i >= 0; i--){
            if(this.user.experience >= this.userLevels[i].required_xp){
                this.userLevel = this.userLevels[i];
                break;
            }
            previousUserLevel = this.userLevels[i];
        }
        if(previousUserLevel != null){
            this.xpNeededUntilLevelUp = previousUserLevel.required_xp - this.user.experience;
        }
    },

    data() {
        return {
            defaultImage: 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/LabChessProfile.svg',
            userLevel: null,
            xpNeededUntilLevelUp: 0,
        }
    }
}
</script>
