<template>
    <div>
        <trainer-position-finished
            v-if="showSuccessWindow"
            @reviewPosition="reviewPosition"
            @nextPosition="goToNextPosition"
            :finishedPositionData="finishedPositionData"
            :nextPosition="nextPosition"
        ></trainer-position-finished>

        <div style="min-height: 100vh;" class="bg-gray-100" v-show="!showSuccessWindow">

            <position-header source="trainer" :trainer="trainer" :variant="variant" class="mb-0 md:mb-8 xl:mb-16">
            </position-header>

            <div class="grid grid-cols-12 gap-x-0 md:gap-x-8 mx-auto px-0 md:px-5 max-width">
                <!-- Navigator -->
                <variant-navigator
                    class="
                    order-3 lg:order-3 xl:order-1 col-span-12 lg:col-span-12 xl:col-span-3 bg-white
                    mt-0 mb-0 md:mb-6 xl:my-0 border-0 md:border md:border-t-0 lg:border-t border-gray-200 w-full relative
                "
                    :trainerCode="trainer.code"
                    :variantNumber="variant.number"
                    :position="position"
                    id="variantNavigator"
                    ref="variantNavigator"
                >
                </variant-navigator>

                <!-- Chess -->
                <div class="order-1 lg:order-1 xl:order-2 col-span-12 lg:col-span-8 xl:col-span-6 board-container mx-auto">
                    <chess-board
                        @positionIsFinished="positionIsFinished"
                        @markPositionAsComplete="markPositionAsComplete"
                        @gameOver="gameOver"
                        class="mb-0 lg:mb-8"
                        :position="position"
                        :source="'trainer'"
                        :userConfiguration="userConfiguration"
                        ref="chessBoard"
                    ></chess-board>
                </div>
                <!-- Helper -->
                <position-helper
                    ref="positionHelper"
                    :position="position"
                    @takeBack="takeBack"
                    @restartPosition="restartPosition"
                    @firstMove="$refs.chessBoard.firstMove()"
                    @previousMove="$refs.chessBoard.previousMove()"
                    @nextMove="$refs.chessBoard.nextMove()"
                    @lastMove="$refs.chessBoard.lastMove()"
                    @startUserAnalysis="startUserAnalysis"
                    id="positionHelper"
                    class="
                    order-2 lg:order-2 xl:order-3 col-span-12 lg:col-span-4 xl:col-span-3 lg:mb-0
                    border-0 md:border md:border-b-0 lg:border-b border-gray-200 relative mt-0 md:mt-5 lg:mt-0 bg-white mb-0 lg:mb-8
                "
                ></position-helper>
            </div>
        </div>
    </div>
</template>

<script>
import ChessBoard from "../chess/chess-board";
import VariantNavigator from "./components/variant-navigator";
import PositionHeader from "./components/position-header";
import PositionHelper from "./components/position-helper";
import TrainerPositionFinished from "../success/trainer-position-finished";

export default {
    name: "trainer-position",
    components: {
        TrainerPositionFinished,
        PositionHelper,
        PositionHeader,
        VariantNavigator,
        ChessBoard
    },

    props: [
        'userConfiguration',
        'trainer',
        'variant',
        'position',
        'nextPosition'
    ],

    mounted(){
        this.updateSidebarMaxHeight();
        window.addEventListener('resize', this.updateSidebarMaxHeight);
    },

    data(){
        return {
            showSuccessWindow: false,
            finishedPositionData: null
        }
    },

    methods: {
        markPositionAsComplete(position){
            this.$refs.variantNavigator.markPositionAsComplete(position);
        },
        startUserAnalysis(gameIsFinished){
            this.$refs.chessBoard.startUserAnalysis(gameIsFinished);
        },
        /**
         * Restart Position.
         */
        restartPosition(){
            this.$refs.chessBoard.restartPosition();
        },
        /**
         * Triggered when user want's to take back move.
         */
        takeBack(){
            this.$refs.chessBoard.takeBack();
        },

        /**
         * Exit trainer-position-finished to review current position
         */
        reviewPosition(){
            this.setPositionHelperSpinner(false);
            this.showSuccessWindow = false;
            this.$nextTick(function() {
                this.updateSidebarMaxHeight();
            });
        },

        /**
         * Sets spinner variable in position helper.
         * @param {bool} value
         */
        setPositionHelperSpinner(value){
            this.$refs.positionHelper.setSpinner(value);
        },

        /**
         * Show success window.
         */
        positionIsFinished(finishedPositionData){
            this.finishedPositionData = finishedPositionData;
            this.showSuccessWindow = true;
        },

        /**
         * Game Over.
         * @param {boolean} playerWon
         */
        gameOver(playerWon){
            this.$refs.positionHelper.gameOver(playerWon);
        },

        /**
         * Go to next position
         */
        goToNextPosition(){
            if(this.nextPosition != null)
                this.redirectToPosition(this.nextPosition.code, this.nextPosition.variant, this.nextPosition.position);
        },

        /**
         * Sets max-height of for sidebars
         */
        updateSidebarMaxHeight(){
            document.querySelector('#variantNavigator').style.maxHeight = this.$refs.chessBoard.$el.clientHeight + 'px';
            document.querySelector('#positionHelper').style.maxHeight = this.$refs.chessBoard.$el.clientHeight + 'px';
        },

        /**
         * Redirect to position.
         * @param {String} code
         * @param {Number} variantNumber
         * @param {Number} positionNumber
         */
        redirectToPosition(code, variantNumber, positionNumber){
            window.location.href = '/trainer/'+code+'/'+variantNumber+'/'+positionNumber;
        },
    }
}
</script>
<style scoped>
    .board-container {
        width: 75vh;
        min-width: 60%;
        max-width: 100%;
    }

    .max-width { max-width: 900px; }
    @media (min-width: 1280px) {
        .max-width { max-width: 1550px; }
    }
</style>
