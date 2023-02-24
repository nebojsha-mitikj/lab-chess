<template>
    <div class=pointer-none style="width: 100%; height: 100%; position: absolute; top: 0; left: 0;">
        <svg height="100%" width="100%" class="absolute top-0 left-0 z-30 pointer-none" viewBox="0 0 100 100">
            <defs>
                <marker
                    v-for="color in [getOrange, getYellow, '#484848']"
                    :id="color"
                    orient="auto"
                    markerWidth="4"
                    markerHeight="8"
                    refX="2.05"
                    refY="2.01"
                >
                    <path d="M0,0 V4 L3,2 Z" :fill="color"></path>
                </marker>
            </defs>
            <line
                v-for="arrow in arrows"
                :key="arrow.id"
                :x1="(x(arrow.firstSquare)) + '%'"
                :y1="(y(arrow.firstSquare)) + '%'"
                :x2="(x(arrow.secondSquare) + getDecrease(arrow,'x')) + '%'"
                :y2="(y(arrow.secondSquare) + getDecrease(arrow,'y')) + '%'"
                :style="'stroke:'+arrow.color+';stroke-width:1.8'"
                stroke-linecap="round"
                opacity="0.8"
                :marker-end="'url(#'+arrow.color+')'"
            />
        </svg>
    </div>
</template>

<script>
export default {
    name: "arrows",

    props: ['positionUsedForRecording'],

    data(){
        return {
            arrows: [],
            tempArrowAdded: false
        }
    },

    methods: {
        /**
         * Get row number for square number.
         * @param {Integer} squareNum
         * @returns {Integer}
         */
        getRow(squareNum) {
            return Math.ceil(squareNum / 8);
        },

        /**
         * Get column number for square number
         * @param {Integer} squareNum
         * @returns {Integer}
         */
        getCol(squareNum) {
            if (squareNum % 8 === 0) return 8;
            return squareNum % 8;
        },

        /**
         * Get X.
         * @param {Number} el
         * @returns Number
         */
        x(el) {
            return (((this.getCol(el) - 1) * 12.5) + 6.25)
        },

        /**
         * Get Y.
         * @param {Number} el
         * @returns Number
         */
        y(el) {
            return (((this.getRow(el) - 1) * 12.5) + 6.25);
        },

        /**
         * Decrease arrow.
         * @param {Object} arrow
         * @param {String} type
         * @returns {Number}
         */
        getDecrease(arrow, type) {
            let DECREASE_ARROW_PERCENTAGE = 2;

            if(this.getCol(arrow.firstSquare) !== this.getCol(arrow.secondSquare) && this.getRow(arrow.firstSquare) !== this.getRow(arrow.secondSquare))
                DECREASE_ARROW_PERCENTAGE += 1.5;

            let x = this.x(arrow.firstSquare) - this.x(arrow.secondSquare);
            let y = this.y(arrow.firstSquare) - this.y(arrow.secondSquare);

            let xSign = x > 0;
            let ySign = y > 0;

            x = Math.abs(x);
            y = Math.abs(y);

            let xPercentage = x * 100 / (x + y);
            let yPercentage = y * 100 / (x + y);

            x = xPercentage * DECREASE_ARROW_PERCENTAGE / 100;
            y = yPercentage * DECREASE_ARROW_PERCENTAGE / 100;

            x = xSign ? x : -x;
            y = ySign ? y : -y;

            return type === 'x' ? x : y;
        },

        /**
         * Get tempArrowAdded variable.
         * @returns boolean
         */
        getTempArrowAdded(){
            return this.tempArrowAdded;
        },

        /**
         * Get tempArrowAdded variable.
         * @param {Boolean} value
         */
        setTempArrowAdded(value){
            this.tempArrowAdded = value;
        },

        /**
         * Set arrows array.
         */
        setArrows(value){
            let engineArrow = null;

            for(let i = 0; i < this.arrows.length; i++){
                if(this.arrows[i].id === 'engine-arrow'){
                    engineArrow = this.arrows[i];
                    break;
                }
            }
            this.arrows = value;
            if(engineArrow != null) this.arrows.unshift(engineArrow);
        },

        /**
         * Get arrow by index.
         * @param {Integer} index
         * returns Object
         */
        getArrow(index){
            return this.arrows[index];
        },

        /*
         * Get arrows
         * @returns Array
         */
        getArrows(){
            let allArrowsWithoutEngineArrow = [];

            for(let i = 0; i < this.arrows.length; i++){
                if(this.arrows[i].id !== 'engine-arrow'){
                    allArrowsWithoutEngineArrow.push(this.arrows[i]);
                }
            }

            return allArrowsWithoutEngineArrow;
        },

        /**
         * Delete arrow by index.
         * @param {Integer} index
         */
        deleteArrow(index){
            this.arrows.splice(index, 1)
        },

        /**
         * Deletes arrow by id
         * @param {string} id
         * @returns boolean
         */
        deleteArrowById(id){
            for (let i = 0; i < this.arrows.length; i++) {
                if (this.arrows[i].id === id) {
                    this.arrows.splice(i, 1);
                    return true;
                }
            }
            return false;
        },

        /**
         * Push new arrow object to the arrows array.
         * @param {Object} arrow
         */
        pushArrow(arrow){
            if(this.positionUsedForRecording === true && arrow.id === 'engine-arrow') return;
            this.arrows.push(arrow)
        },

        /**
         * Set secondSquare for last arrow.
         * @param {Integer} value
         */
        setSecondSquareForLastArrow(value){
            this.arrows[this.arrows.length-1].secondSquare = value;
        },

        /**
         * Get indexes for arrow.
         * @param {Object} arrow
         * @returns array
         */
        indexesOfArrowInArrows(arrow){
            let indexes = [];
            for(let i = 0; i < this.arrows.length; i++) {
                if(this.arrows[i].id !== 'engine-arrow' && arrow.firstSquare === this.arrows[i].firstSquare && arrow.secondSquare === this.arrows[i].secondSquare){
                    indexes.push(i);
                }
            }
            return indexes;
        },

        /**
         * Get index for arrow.
         * @param {Object} arrow
         */
        indexOfArrowInArrows(arrow){
            for(let i = 0; i < this.arrows.length; i++) {
                if(this.arrows[i].id !== 'engine-arrow' && arrow.firstSquare === this.arrows[i].firstSquare && arrow.secondSquare === this.arrows[i].secondSquare){
                    return i;
                }
            }
            return -1;
        }

    }
}
</script>

<style scoped>
.pointer-none {
    pointer-events: none;
}
</style>
