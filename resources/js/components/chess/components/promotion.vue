<template>
    <div>
        <template v-if="promotionIsActive">
            <div class="z-40 promotion" :style="'left: '+ 12.5*column +'%;'">
                <div
                    @click="promoteTo(promotionPiece)"
                    v-for="promotionPiece in promotionOptions"
                    :class="promotionPiece + ' promotion-piece cursor-pointer promotion-piece-hover'"
                ></div>
                <div
                    @click="exitPromotion()"
                    class="promotion-cancel content-center align-center justify-center text-gray-500 hover:text-gray-900">
                    <uil-times class="text-xl"></uil-times>
                </div>
            </div>
            <div class="promotion-layer no-select"></div>
        </template>
    </div>
</template>

<script>

import { UilTimes } from '@iconscout/vue-unicons';
import squareMixin from "../mixins/squareMixin";

export default {
    name: "promotion",
    components: {UilTimes},
    mixins: [squareMixin],
    data(){
        return {
            promotionOptions: ['Q','N','R','B'],
            promotionIsActive: false,
            column: 0,
            initialSquareData: null,
            toSquareNumber: null
        }
    },

    methods: {
        /**
         * Show promotion window on column.
         * @param {Object} initialSquareData
         * @param {Integer} toSquareNumber
         */
        show(initialSquareData, toSquareNumber){
            this.toSquareNumber = parseInt(toSquareNumber);
            this.initialSquareData = initialSquareData;
            let isWhite = initialSquareData.piece.toUpperCase() === initialSquareData.piece;
            this.column = ['a','b','c','d','e','f','g','h'].indexOf(this.notation[this.toSquareNumber].charAt(0));
            this.setPromotionOptions(isWhite);
            this.promotionIsActive = true;
        },

        /**
         * Checks if promotion window is active.
         */
        getPromotionIsActive(){
            return this.promotionIsActive;
        },

        /**
         * Exit promotion.
         * Hides and emits exitPromotion event to parent component.
         */
        exitPromotion(){
            this.$emit('exitPromotion', {'piece': this.initialSquareData.piece, 'number': this.initialSquareData.number});
            this.clearAndHide();
        },

        /**
         * Show promotion window on column.
         * @param {Boolean} isWhite.
         */
        setPromotionOptions(isWhite){
            this.promotionOptions = isWhite ? ['Q','N','R','B'] : ['q','n','r','b'];
        },

        /**
         * Promote to piece.
         * @param {String} promotionPiece.
         */
        promoteTo(promotionPiece){
            this.$emit('promoteTo', {
                'promotionPiece': promotionPiece.toLowerCase(),
                'initialSquareData': this.initialSquareData,
                'toSquareNumber': this.toSquareNumber
            });
            this.clearAndHide();
        },

        /**
         * Clear data and hide promotion window.
         */
        clearAndHide(){
            this.initialSquareData = null;
            this.promotionIsActive = false;
        },
    }
}
</script>

<style scoped>
.promotion {
    border-radius: 2px;
    width: 12.5%;
    height: 54.40%;
    background: white;
    outline: 1px solid #E8E8E8;
    position: absolute;
    top: 0;
    box-shadow: 1px 1px 15px -4px rgba(0,0,0,0.8);
    -webkit-box-shadow: 1px 1px 15px -4px rgba(0,0,0,0.8);
    -moz-box-shadow: 1px 1px 15px -4px rgba(0,0,0,0.8);
}
.promotion-cancel {
    width: 100%;
    display: flex;
    cursor: pointer;
    height: 8%;
    background: #E8E8E8;
}
.promotion-piece {
    width: 100%;
    height: 23%;
    background-repeat: no-repeat;
    background-size: 100%;
}

.promotion-piece-hover:hover {
    background-color: #F0F0F0;
}
.promotion-layer {
    position: absolute;
    z-index: 35;
    width: 100%;
    height: 100%;
    background: rgba(1,1,1,0.2);
}
</style>
