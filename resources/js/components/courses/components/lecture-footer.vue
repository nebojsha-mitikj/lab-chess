<template>
    <div>
        <div class="h-20 sm:h-32"></div>
        <div class="fixed bottom-0 w-full z-40 border-gray-200"
             :class="{'bg-white border-t': mistakeData === false, 'bg-red-200': mistakeData !== false}">

            <p class="text-red-500 px-5 pt-3 sm:pt-4 text-base sm:text-lg max-w-3xl mx-auto" v-if="mistakeData !== false">
                <template v-if="mistakeData.message != null">
                    {{ mistakeData.message }}
                </template>
                <template v-else>
                    <span class="font-bold">{{ mistakeData.lastMove }}</span> is mistake. <span
                    class="font-bold">{{ mistakeData.bestMove }}</span> was best!
                </template>
            </p>

            <div class="my-2 sm:my-4 max-w-3xl mx-auto flex justify-center align-center px-5">

                <button
                    @click="$emit('previousStep')"
                    v-if="activeStep > 1"
                    v-text="'Back'"
                    class="button mr-auto"
                    :class="{'sub-button': mistakeData === false, 'bg-white text-red-500 border-0': mistakeData !== false}">
                </button>

                <template v-if="mistakeData === false">
                    <button
                        @click="$emit('nextStep')"
                        v-if="stepCount >= activeStep"
                        v-text="'Continue'"
                        :disabled="disableContinue"
                        :class="{'sub-button-disabled': disableContinue}"
                        class="button main-button ml-auto"
                    ></button>

                    <button
                        @click="redirectToCoursesPage"
                        v-if="stepCount < activeStep"
                        v-text="'Finish'"
                        class="button main-button ml-auto"
                    ></button>
                </template>

                <template v-else>
                    <button
                        @click="$emit(mistakeData.message != null ? 'restartQuestion' : 'restartPosition')"
                        v-text="mistakeData.message != null ? 'Try again' : 'Restart'"
                        class="button ml-auto bg-red-500 text-white border border-red-200"
                    ></button>
                </template>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "lecture-footer",

    props: [
        'activeStep',
        'stepCount',
        'mistakeData'
    ],

    data() {
        return {
            disableContinue: false,
        }
    },

    methods: {
        /**
         * Sets disableContinue
         * @param {Boolean} value
         */
        setDisableContinue(value) {
            this.disableContinue = value;
        },
        /**
         * Redirect to courses.
         */
        redirectToCoursesPage() {
            window.location.href = '/courses';
        }
    }
}
</script>
