<template>
    <div class="pb-4 lg:pb-5">
        <div
            v-if="variant != null"
            class="z-10 text-center mt-5 lg:mt-0 pt-4 lg:pb-4 justify-center align-center relative border-t lg:border-t-0 border-b-0 lg:border-b border-gray-200"
        >
            <div class="mx-auto max-w-xl relative">

                <div
                    v-if="variant != null && variants != null"
                    class="flex justify-center align-center z-10 text-center"
                >
                    <button
                        :disabled="requestIsActive"
                        @click="setTrainerVariant(trainerCode, previousVariant)"
                        class="text-base lg:text-xl text-gray-600 cursor-pointer"
                    >
                        <i class="fa fa-arrow-left"></i>
                    </button>

                    <p class="text-gray-700 text-sm lg:text-base mx-8" v-text="'Variant ' + variant.number+' of '+variants.length"></p>

                    <button
                        :disabled="requestIsActive"
                        @click="setTrainerVariant(trainerCode, nextVariant)"
                        class="text-base lg:text-xl text-gray-600 cursor-pointer"
                    >
                        <i class="fa fa-arrow-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="position-container my-2 lg:my-5 xl:my-0" style="height: calc(100% - 180px)">
            <div v-if="variant != null && position != null" class="text-gray-700 mt-3 ml-4">
                <p class="text-gray-700 text-center md:text-left text-sm lg:textbase">Positions: </p>
            </div>

            <div class="my-scroll variant-scroll" v-if="variant != null && position != null">
                <div
                     class="my-4 z-10 grid grid-cols-12 gap-x-1 gap-y-1 lg:gap-x-4 lg:gap-y-4 container w-full max-w-5xl mx-auto">
                    <div
                        @click="redirectToPosition('/trainer/'+trainerCode+'/'+variant.number+'/'+pos.number)"
                        class="
                    hover:text-primary text-gray-400 hover:border-primary-light text-center flex justify-center align-center
                    col-span-3 md:col-span-2 lg:col-span-2 xl:col-span-3 shadow-sm border border-gray-150 cursor-pointer py-1 lg:py-2
                    "
                        :class="{
                            'text-primary border-primary-light': variantNumber === variant.number && position.number === pos.number,
                            'text-gray-400 bg-primary-lighter border-primary-light': pos.user_trainer_positions.length > 0,
                            'text-gray-400': pos.user_trainer_positions.length <= 0,
                            'bg-gray-50 rounded-sm': !subscribed && pos.number > lockFromPosition
                        }"
                        v-for="pos in positions"
                        :key="pos.id"
                    >
                        <svg v-if="!subscribed && pos.number > lockFromPosition" class="my-1.5" height="12pt" width="12pt" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m141.24 247.17v-97.103c0-63.38 51.379-114.76 114.76-114.76s114.76 51.379 114.76 114.76v97.103h35.31v-97.103c0-82.881-67.188-150.07-150.07-150.07s-150.07 67.188-150.07 150.07v97.103h35.31z" fill="#959CB5"/><g fill="#7F8499"><rect x="105.93" y="211.86" width="35.31" height="35.31"/><rect x="370.76" y="211.86" width="35.31" height="35.31"/></g><path d="m88.276 264.83v172.14c0 20.562 11.772 39.474 30.481 48.002 36.964 16.852 84.872 27.029 137.24 27.029s100.28-10.177 137.24-27.029c18.709-8.529 30.481-27.441 30.481-48.002v-172.14c0-19.501-15.809-35.31-35.31-35.31h-264.83c-19.501-1e-3 -35.31 15.808-35.31 35.31z" fill="#FFDC64"/><path d="m291.31 344.28c0-22.262-20.601-39.712-43.78-34.325-12.515 2.909-22.703 12.985-25.754 25.468-3.689 15.089 2.361 28.915 13.055 36.941 2.106 1.581 3.514 3.914 3.514 6.547v26.219c0 8.794 6.009 16.947 14.69 18.358 11.061 1.799 20.62-6.692 20.62-17.415v-27.339c0-2.522 1.348-4.761 3.373-6.266 8.637-6.416 14.282-16.6 14.282-28.188z" fill="#464655"/><g fill="#FFF082"><path d="m384 291.31c7.313 0 13.241-5.929 13.241-13.241v-8.828c0-7.313-5.929-13.241-13.241-13.241-7.313 0-13.241 5.929-13.241 13.241v8.828c0 7.313 5.928 13.241 13.241 13.241z"/><path d="m384 379.59c7.313 0 13.241-5.929 13.241-13.241v-44.138c0-7.313-5.929-13.241-13.241-13.241-7.313 0-13.241 5.929-13.241 13.241v44.138c0 7.312 5.928 13.241 13.241 13.241z"/></g><path d="m170.92 484.97c-12.801-8.529-20.855-27.442-20.855-48.002v-172.14c0-19.501 10.817-35.31 24.16-35.31h-50.643c-19.501 0-35.31 15.809-35.31 35.31v172.14c0 20.562 11.772 39.474 30.482 48.002 36.963 16.852 84.871 27.03 137.24 27.03 1.768 0 3.485-0.104 5.241-0.127-34.418-0.66-65.879-10.619-90.317-26.902z" fill="#FFC850"/></svg>
                        <p v-else class="text-base" v-text="pos.number"></p>
                    </div>
                </div>
            </div>

        </div>

        <div class="w-full h-8"></div>

        <div
            class="border-t border-gray-200 pt-5 pb-0 lg:pb-4 text-gray-500 relative xl:absolute bottom-0 w-full bg- white"
            v-if="variant != null"
        >
            <div class="flex justify-center mt-2 align-center">
                <template v-for="piece in variant.pieces.split('')">
                    <img draggable="false" v-if="piece !== 'x'" :src="'/images/pieces/'+piece+'.svg'"
                         :alt="'labchess Image'" style="margin-top: -10px;" class="h-9">
                    <p v-else-if="piece === 'x'" class="mx-2 text-gray-700 font-bold text-lg">VS.</p>
                </template>
            </div>

        </div>

    </div>
</template>

<script>

import {UilArrowLeft, UilArrowRight} from '@iconscout/vue-unicons';
import MySpinner from "../../assets/my-spinner";

export default {

    name: "variant-navigator",
    props: ['trainerCode', 'variantNumber', 'position'],

    computed: {
        /**
         * Get the next variant number.
         * @returns Number
         */
        nextVariant() {
            let variantNumber = this.variant.number + 1;
            if (variantNumber > this.variants.length) variantNumber = 1;
            return variantNumber;
        },

        /**
         * Get the previous variant number.
         * @returns Number
         */
        previousVariant() {
            let variantNumber = this.variant.number - 1;
            if (variantNumber <= 0) variantNumber = this.variants.length;
            return variantNumber;
        },

        /**
         * Gets lock from position number.
         */
        lockFromPosition(){
            return Math.floor(this.positions.length / 2) > 20 ? 20 : Math.floor(this.positions.length / 2);
        },
    },

    mounted() {
        if (window.subscribed !== null) this.subscribed = window.subscribed;
        this.requestTrainerVariant(this.trainerCode, this.variantNumber);
    },

    components: {MySpinner, UilArrowLeft, UilArrowRight},

    data() {
        return {
            subscribed: false,
            requestIsActive: false,
            trainerPositions: null,
            variantId: null,
            variant: null,
            variants: null,
            positions: null,
        }
    },

    methods: {
        /**
         * Sets trainer variant.
         * @param {String} code
         * @param {Integer} variantNumber
         */
        setTrainerVariant(code, variantNumber){
            for(let i = 0; i < this.variants.length; i++){
                if(this.variants[i].number === variantNumber){
                    this.variantId = this.variants[i].id;
                    break;
                }
            }
            this.setVariant();
            this.setVariantPositions();
        },

        /**
         * Requests trainer variant.
         * @param {String} code
         * @param {Integer} variantNumber
         */
        requestTrainerVariant(code, variantNumber) {
            this.requestIsActive = true;
            axios.get('/api/trainer/' + code + '/' + variantNumber).then(result => {
                this.variantId = result.data.variantId;
                this.variants = result.data.variants;
                this.trainerPositions = result.data.positions;
                this.setVariant();
                this.setVariantPositions();
            }).finally(() => this.requestIsActive = false);
        },

        /**
         * Sets variant positions from trainer positions.
         */
        setVariantPositions(){
            let positions = [];
            for(let i = 0; i < this.trainerPositions.length; i++) {
                if(this.trainerPositions[i].variant_id === this.variantId) {
                    positions.push(this.trainerPositions[i]);
                }
            }
            this.positions = positions;
        },

        /**
         * Set variant from variants.
         */
        setVariant(){
            for(let i = 0; i < this.variants.length; i++){
                if(this.variants[i].id === this.variantId) {
                    this.variant = this.variants[i];
                    break;
                }
            }
        },

        /**
         * Marks position with id as complete.
         * @param {integer} position
         */
        markPositionAsComplete(position){
            for(let i = 0; i < this.positions.length; i++){
                if(this.positions[i].id === position.id){
                    this.positions[i].user_trainer_positions.push('');
                    break;
                }
            }
        },

        /**
         * Redirect to specific course page.
         * @param url
         */
        redirectToPosition(url) {
            window.location.href = url;
        }
    }
}
</script>

<style scoped>
.position-container {
    max-width: 750px;
    margin-left: auto;
    margin-right: auto;
}

.my-scroll {
    overflow-y: auto;
    height: 100%;
}

.variant-scroll::-webkit-scrollbar {
    -webkit-appearance: none;
}
.variant-scroll::-webkit-scrollbar:vertical {
    width: 9px;
}
.variant-scroll::-webkit-scrollbar:horizontal {
    height: 9px;
}
.variant-scroll::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, .2);
    border-radius: 10px;
    border: 2px solid #ffffff;
}
.variant-scroll::-webkit-scrollbar-track {
    border-radius: 10px;
    background-color: #ffffff;
}

</style>
