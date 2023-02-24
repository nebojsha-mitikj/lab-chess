<template>
    <div class="col-span-12 px-8">
        <div class="mx-auto my-2">
            <p class="text-center text-base mt-3 mb-2">{{ question }}</p>
            <div class="border-gray-200 border mx-auto rounded text-sm sm:text-base max-w-sm" style="max-width: 240px">
                <div class="grid grid-cols-6 divide-x">
                    <button v-for="btn in buttonData" @click="answer(btn[0])" class="col-span-3 py-4 text-base w-full mx-auto"
                        :class="{
                            'bg-gray-50 text-primary': questionAnswer === btn[0],
                            'cursor-pointer hover:text-primary hover:bg-gray-50': questionAnswer === null
                        }"
                    >
                        <span>{{ btn[1] }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "chess-answer",

    props: ['questionAnswer', 'question', 'content'],

    mounted(){
        if(this.content != null && this.content.answers != null){
            this.buttonData = [
                [true, this.content.answers[0]],
                [false, this.content.answers[1]]
            ];
        }
    },

    data(){
        return {
            buttonData: [
                [true, 'Yes'],
                [false,'No']
            ]
        }
    },

    methods: {
        /**
         * On user answer.
         * @param val
         */
        answer(val){
            if(this.questionAnswer === null){
                this.$emit('userAnswer',val)
            }
        }
    }
}
</script>
