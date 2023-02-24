<template>
    <div class="mx-auto"
         :class="{
            'max-w-xl': lectureProp.type === 'lesson' && !lectureIncludes('chess-exercise'),
            'max-w-5xl': lectureProp.type === 'exercise' || lectureIncludes('chess-exercise')
        }">

        <template v-if="lecture[activeStep] != null">
            <div class="text-center px-4" v-if="lectureProp.type === 'lesson' && !lectureIncludes('chess-exercise')">
                <h1 class="subheader text-gray-700" v-text="lecture[activeStep].title"></h1>
            </div>

            <div class="my-1 text-gray-800">
                <div
                    v-for="(element, index) in lecture[activeStep].body"
                    :key="lecture[activeStep].id + '-' + element.number + '-' + index"
                >

                    <div class="px-4" v-if="element.type === 'html'" v-html="element.content"></div>

                    <div class="px-4" v-if="element.type === 'chess-lesson'">
                        <p class="text-sm text-center" v-if="element.type === 'chess-lesson'">
                            {{ getTurnStringFromFEN(element.content.position) }} - Navigate through the moves to continue
                        </p>
                    </div>

                    <div class="px-4" v-if="element.type === 'warning'">
                        <my-warning>
                            <div v-html="element.content"></div>
                        </my-warning>
                    </div>

                    <div class="px-4" v-if="element.type === 'info'">
                        <my-info>
                            <div v-html="element.content"></div>
                        </my-info>
                    </div>

                    <div
                        class="grid grid-cols-12 gap-x-3"
                        :class="{'mt-2 md:mt-4 lg:mt-8': lectureProp.type === 'lesson' && lectureIncludes('chess-exercise')}"
                        v-if="element.type.includes('chess')"
                    >
                        <div
                            class="board-container mx-auto"
                            :class="{
                                'col-span-12 lg:col-span-8 xl:col-span-9': element.type === 'chess-exercise',
                                'col-span-12': element.type.includes('chess') && element.type !== 'chess-exercise'
                            }"
                        >
                            <chess-board
                                ref="chessBoard"
                                :userConfiguration="userConfiguration"
                                :position="element.content"
                                :source="'course'"
                                :mistakeEvaluation="element.content.mistakeEvaluation"
                                :mistakeDepth="element.content.mistakeDepth"
                                :rotateBoardProp="element.content.rotate"
                                :exclude="element.content.exclude"
                                :positionIsFinishedProp="positionFinished"
                                :sound="sound"
                                :chessState="element.content.chessState"
                                @positionIsFinished="$emit('positionIsFinished')"
                                @userMadeMistake="userMadeMistake"
                                @lastMoveReached="$emit('lastMoveReached')"
                            ></chess-board>
                        </div>

                        <position-helper
                            v-if="element.type === 'chess-exercise'"
                            ref="positionHelper"
                            class="col-span-12 lg:col-span-4 xl:col-span-3 border-b border-0 sm:border border-gray-200 bg-white max-w-xl mx-auto"
                            :position="element.content"
                            :source="'course'"
                            @startUserAnalysis="startUserAnalysis"
                            @restartPosition="restartPosition"
                            @takeBack="$refs.chessBoard[0].takeBack()"
                            @firstMove="$refs.chessBoard[0].firstMove()"
                            @previousMove="$refs.chessBoard[0].previousMove()"
                            @nextMove="$refs.chessBoard[0].nextMove()"
                            @lastMove="$refs.chessBoard[0].lastMove()"
                        ></position-helper>

                        <div class="col-span-12" v-if="element.type === 'chess-lesson'">
                            <chess-actions
                                :positionFinished="positionFinished"
                                @previousMove="$refs.chessBoard[0].previousMove()"
                                @nextMove="$refs.chessBoard[0].nextMove()"
                            ></chess-actions>
                        </div>

                        <chess-answer
                            v-if="element.type === 'chess-question'"
                            :question="element.content.question"
                            :content="element.content"
                            :questionAnswer="questionAnswer"
                            @userAnswer="userAnswer"
                        ></chess-answer>

                    </div>

                </div>
            </div>
        </template>

        <lecture-finished
            v-else-if="lectureIsFinished && activeStep > stepCount && userGoalProgress != null"
            @disableAnimateLastStep="setAnimateLastStep(false)"
            :animateLastStep="animateLastStep"
            :progressData="userGoalProgress"
        ></lecture-finished>

        <div class="hidden italic list-decimal ml-9"></div>
    </div>
</template>

<script>

import MyWarning from "../../assets/my-warning";
import ChessBoard from "../../chess/chess-board";
import TrainerPositionFinished from "../../success/trainer-position-finished";
import LectureFinished from "./lecture-finished";
import {UilRedo} from '@iconscout/vue-unicons';
import ChessActions from "../../chess/components/chess-actions";
import PositionHelper from "../../trainer/components/position-helper";
import ChessAnswer from "../../chess/components/chess-answer";
import MyInfo from "../../assets/my-info";

export default {

    name: "lecture-content",

    props: [
        'activeStep',
        'lecture',
        'lectureProp',
        'lectureIsFinished',
        'questionAnswer',
        'stepCount',
        'userConfiguration',
        'sound',
        'userGoalProgress',
        'chessState',
        'positionFinished'
    ],

    data() {
        return {
            animateLastStep: true
        }
    },

    components: {
        MyInfo,
        ChessAnswer,
        PositionHelper,
        ChessActions,
        LectureFinished,
        TrainerPositionFinished,
        ChessBoard,
        MyWarning,
        UilRedo
    },

    watch: {
        positionFinished(newValue, oldValue) {
            if (this.$refs.positionHelper != null && this.$refs.positionHelper[0] != null) {
                this.$refs.positionHelper[0].setGameIsFinished(newValue);
            }
        }
    },

    methods: {
        /**
         * Returns turn string from FEN code.
         * @param {String} fen
         * @returns String
         */
        getTurnStringFromFEN(fen){
            return fen.split(' ')[1].toLowerCase() === 'b' ? 'Black to move' : 'White to move';
        },

        /**
         * On user answer
         * @param {Boolean} answerData
         */
        userAnswer(answerData){
            this.$emit('userAnswer', answerData);
        },

        /**
         * Check if active lecture contains content with type :key
         */
        lectureIncludes(key) {
            return this.$parent.lectureIncludes(key);
        },

        /**
         * Starts user analysis
         * @param {Boolean} gameIsFinished
         */
        startUserAnalysis(gameIsFinished) {
            this.$refs.chessBoard[0].startUserAnalysis(gameIsFinished);
        },

        /**
         * User made mistake event.
         */
        userMadeMistake(mistakeData) {
            this.$emit('userMadeMistake', mistakeData);
        },

        /**
         * Restart chess position.
         */
        restartPosition() {
            this.$emit('restartPosition', 'content');
            this.$refs.chessBoard[0].restartPosition();
        },

        /**
         * Get last chess state.
         * @returns {null|*}
         */
        getChessState() {
            if (this.$refs.chessBoard != null && this.$refs.chessBoard.length > 0)
                return this.$refs.chessBoard[0].getChessState();
            return null;
        },

        /**
         * Set animate last step
         * @param animateLastStep
         */
        setAnimateLastStep(animateLastStep) {
            this.animateLastStep = animateLastStep;
        }
    }
}
</script>

<style scoped>
.board-container {width: 75vh; min-width: 60%; max-width: 100%;}
</style>
