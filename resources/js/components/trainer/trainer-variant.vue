<template>
    <div class="mx-auto max-w-4xl relative">

        <!-- Header -->
        <h1 class="text-center header text-gray-700" v-if="trainer != null" v-text="trainer.name"></h1>

        <!-- Progress Bar -->
        <div class="mb-1 max-w-3xl mx-auto mt-4 sm:mt-1">
            <p class="float-left ml-1 text-gray-700 mb-1" v-if="trainer != null" v-text="trainer.name + ' Progress:'"></p>
            <p class="float-right mr-1 mb-1 text-gray-700">{{ wholeNumberOrWithTwoDecimals(progress) }}%</p>
            <my-progress-bar :percent="progress" :height="13" class="clear-both" :color="getPrimary"></my-progress-bar>
        </div>

        <!-- Variant Navigation -->
        <div class="text-center flex justify-center align-center mt-4" v-if="variant != null">

            <button
                :disabled="requestIsActive"
                @click="readTrainerVariant(previousCode, previousVariant)"
                class="text-4xl cursor-pointer text-primary">
                <uil-arrow-left></uil-arrow-left>
            </button>

            <p class="mx-5 sm:mx-8 text-gray-700" v-text="'Variant '+variant.number+' of '+variants.length"></p>

            <button
                :disabled="requestIsActive"
                @click="readTrainerVariant(nextCode, nextVariant)"
                class="text-4xl cursor-pointer text-primary">
                <uil-arrow-right></uil-arrow-right>
            </button>
        </div>

        <!-- Variant -->
        <div v-if="variant != null" class="flex justify-center align-center mt-6">
            <template v-for="piece in variant.pieces.split('')">
                <img draggable="false" v-if="piece !== 'x'" :src="'/images/pieces/'+piece+'.svg'"
                     :alt="'labchess Image'" style="margin-top: -10px;" class="h-8 sm:h-10">
                <p v-else-if="piece === 'x'" class="mx-2 text-gray-700 font-bold text-base sm:text-xl">VS.</p>
            </template>
        </div>

        <!-- Positions -->
        <div class="text-gray-700 mt-4 mb-5 max-w-4xl">
            <p class="text-base sm:text-lg ml-0 lg:ml-5">Positions:</p>
        </div>

        <div class="mb-4 sm:mb-8 grid grid-cols-12 gap-x-2 gap-y-2 sm:gap-x-4 sm:gap-y-4 max-w-4xl mx-auto mt-5 lg:px-6" v-if="variant != null && positions != null && trainer != null">

            <div
                v-for="position in positions"
                :key="position.id"
                @click="redirectToPosition('/trainer/'+trainer.code+'/'+variant.number+'/'+position.number)"
                :class="{
                    'text-gray-400 bg-primary-lighter border-primary-light': position.user_trainer_positions.length > 0,
                    'text-gray-400': position.user_trainer_positions.length <= 0,
                    'bg-gray-50 rounded-sm': !subscribed && position.number > lockFromPosition
                }"
                class="
                    hover:border-primary-light hover:text-primary text-center py-1 sm:py-3 cursor-pointer
                    col-span-3 sm:col-span-1 border border-gray-200 flex justify-center align-center
            ">
                <svg v-if="!subscribed && position.number > lockFromPosition" class="my-1.5" height="12pt" width="12pt" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m141.24 247.17v-97.103c0-63.38 51.379-114.76 114.76-114.76s114.76 51.379 114.76 114.76v97.103h35.31v-97.103c0-82.881-67.188-150.07-150.07-150.07s-150.07 67.188-150.07 150.07v97.103h35.31z" fill="#959CB5"/><g fill="#7F8499"><rect x="105.93" y="211.86" width="35.31" height="35.31"/><rect x="370.76" y="211.86" width="35.31" height="35.31"/></g><path d="m88.276 264.83v172.14c0 20.562 11.772 39.474 30.481 48.002 36.964 16.852 84.872 27.029 137.24 27.029s100.28-10.177 137.24-27.029c18.709-8.529 30.481-27.441 30.481-48.002v-172.14c0-19.501-15.809-35.31-35.31-35.31h-264.83c-19.501-1e-3 -35.31 15.808-35.31 35.31z" fill="#FFDC64"/><path d="m291.31 344.28c0-22.262-20.601-39.712-43.78-34.325-12.515 2.909-22.703 12.985-25.754 25.468-3.689 15.089 2.361 28.915 13.055 36.941 2.106 1.581 3.514 3.914 3.514 6.547v26.219c0 8.794 6.009 16.947 14.69 18.358 11.061 1.799 20.62-6.692 20.62-17.415v-27.339c0-2.522 1.348-4.761 3.373-6.266 8.637-6.416 14.282-16.6 14.282-28.188z" fill="#464655"/><g fill="#FFF082"><path d="m384 291.31c7.313 0 13.241-5.929 13.241-13.241v-8.828c0-7.313-5.929-13.241-13.241-13.241-7.313 0-13.241 5.929-13.241 13.241v8.828c0 7.313 5.928 13.241 13.241 13.241z"/><path d="m384 379.59c7.313 0 13.241-5.929 13.241-13.241v-44.138c0-7.313-5.929-13.241-13.241-13.241-7.313 0-13.241 5.929-13.241 13.241v44.138c0 7.312 5.928 13.241 13.241 13.241z"/></g><path d="m170.92 484.97c-12.801-8.529-20.855-27.442-20.855-48.002v-172.14c0-19.501 10.817-35.31 24.16-35.31h-50.643c-19.501 0-35.31 15.809-35.31 35.31v172.14c0 20.562 11.772 39.474 30.482 48.002 36.963 16.852 84.871 27.03 137.24 27.03 1.768 0 3.485-0.104 5.241-0.127-34.418-0.66-65.879-10.619-90.317-26.902z" fill="#FFC850"/></svg>
                <p v-else class="text-base sm:text-xl">{{position.number}}</p>
            </div>

        </div>

    </div>
</template>

<script>
import MyProgressBar from "../assets/my-progress-bar";
import { UilArrowRight, UilArrowLeft } from '@iconscout/vue-unicons';
import percentageMixin from "../assets/mixins/percentageMixin";

export default {
    name: "trainer-variant",

    components: {MyProgressBar, UilArrowLeft, UilArrowRight},
    mixins: [percentageMixin],

    props: ['trainerProp', 'positionsProp', 'progressProp', 'variantsProp', 'variantIdProp', 'trainers'],

    computed: {

        /**
         * Gets lock from position number.
         */
        lockFromPosition(){
            return Math.floor(this.positions.length / 2) > 20 ? 20 : Math.floor(this.positions.length / 2);
        },

        /**
         * Get the next variant number.
         * @returns Number
         */
        nextVariant(){
            let variantNumber = this.variant.number+1;
            if(variantNumber > this.variants.length) variantNumber = 1;
            return variantNumber;
        },

        /**
         * Get the previous variant number.
         * @returns Number
         */
        previousVariant(){
            let index = this.trainers.findIndex((el) => el.code === this.trainer.code);
            let variantNumber = this.variant.number-1;
            if(variantNumber <= 0) {
                if(--index < 0) index = this.trainers.length-1;
                variantNumber = this.trainers[index].variants_count;
            }
            return variantNumber;
        },

        /**
         * Get the next variant code.
         * @returns String
         */
        nextCode(){
            let index = this.trainers.findIndex((el) => el.code === this.trainer.code);
            if((this.variant.number+1) > this.variants.length && ++index >= this.trainers.length) index = 0;
            return this.trainers[index].code;
        },

        /**
         * Get the previous variant code.
         * @returns String
         */
        previousCode(){
            let index = this.trainers.findIndex((el) => el.code === this.trainer.code);
            if((this.variant.number-1) <= 0 && --index < 0) index = this.trainers.length-1;
            return this.trainers[index].code;
        }
    },

    mounted(){
        if (window.subscribed !== null) this.subscribed = window.subscribed;
        this.progress = this.progressProp;
        this.trainer = this.trainerProp;
        this.variantId = this.variantIdProp;
        this.variants = this.variantsProp;
        this.setVariant();
        this.trainerPositions = this.positionsProp;
        this.setVariantPositions();
        let self = this;
        document.addEventListener('keydown', function(e) {
            if(e.key === 'ArrowLeft'){
                self.readTrainerVariant(self.previousCode, self.previousVariant);
            }else if(e.key === 'ArrowRight'){
                self.readTrainerVariant(self.nextCode, self.nextVariant);
            }
        });

    },

    data(){
        return {
            subscribed: false,
            progress: null,
            trainer: null,
            variant: null,
            trainerPositions: null,
            positions: null,
            variantId: null,
            requestIsActive: false
        }
    },

    methods: {
        /**
         * Reads trainer variant.
         * @param {String} code
         * @param {Integer} variantNumber
         */
        readTrainerVariant(code, variantNumber){
            if(code !== this.trainer.code){
                this.requestNewTrainerVariant(code, variantNumber);
            }else{
                for(let i = 0; i < this.variants.length; i++){
                    if(this.variants[i].number === variantNumber){
                        this.variantId = this.variants[i].id;
                        break;
                    }
                }
                this.setVariant();
                this.setVariantPositions();
            }
        },

        /**
         * Requests new trainer variant.
         * @param {String} code
         * @param {Integer} variantNumber
         */
        requestNewTrainerVariant(code, variantNumber){
            this.requestIsActive = true;
            axios.get('/api/trainer/'+code+'/'+variantNumber).then(result => {
                this.progress = result.data.progress;
                this.trainer = result.data.trainer;
                this.variantId = result.data.variantId;
                this.variants = result.data.variants;
                this.setVariant();
                this.trainerPositions = result.data.positions;
                this.setVariantPositions();

                window.history.replaceState('Trainer Variant', 'Trainer Variant', '/trainer/'+code+'/'+variantNumber);
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
         * Redirect to specific course page.
         * @param url
         */
        redirectToPosition(url){
            window.location.href = url;
        }
    }
}
</script>
