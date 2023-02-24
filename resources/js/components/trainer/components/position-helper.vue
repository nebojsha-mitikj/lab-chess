<template>
    <div class="w-full flex flex-col">

        <div
            class="text-center py-2 lg:py-4 justify-center align-center flex border-b-none lg:border-b bg-gray-100 md:bg-white border-gray-200">
            <img :src="imageURL" class="h-8 lg:h-10" style="margin-top: -3px;">
            <p class="text-gray-700 text-sm lg:text-base font-normal lg:font-semibold" v-text="targetString"></p>
        </div>

        <position-helper-navigation
            class="block lg:hidden"
            @firstMove="$emit('firstMove')"
            @previousMove="$emit('previousMove')"
            @nextMove="$emit('nextMove')"
            @lastMove="$emit('lastMove')"
        ></position-helper-navigation>

        <!-- Actions -->
        <div class="text-center grid grid-cols-12">
            <div class="border-b border-r lg:border-r-0 border-gray-200 col-span-6 lg:col-span-12">
                <button
                    @click="startUserAnalysis"
                    class="no-select text-sm lg:text-base hover:text-primary hover:bg-gray-50 text-gray-600 py-2 lg:py-4 lg:py-5 w-full"
                    :class="{'bg-gray-50 text-primary cursor-default': userAnalysisIsActive, 'cursor-pointer': !userAnalysisIsActive}">
                    <i class="fa fa-laptop mr-0.5"></i> Analyze Position
                </button>
            </div>
            <div class="border-b border-gray-200 col-span-6 lg:col-span-12">
                <button
                    @click="restartPosition"
                    class="no-select text-sm lg:text-base hover:text-primary hover:bg-gray-50 text-gray-600 cursor-pointer py-2 lg:py-4 lg:py-5 w-full">
                    <i class="fa fa-sync-alt mr-0.5"></i> Restart Position
                </button>
            </div>
            <div class="border-b border-r lg:border-r-0 border-gray-200 col-span-6 lg:col-span-12">
                <button
                    @click="rotateBoard"
                    class="no-select text-sm lg:text-base hover:text-primary hover:bg-gray-50 text-gray-600 cursor-pointer py-2 lg:py-4 lg:py-5 w-full"
                    :class="{'bg-gray-50 text-primary': rotate}">
                    <i class="fas fa-chess-board mr-0.5"></i> Rotate Board
                </button>
            </div>
            <div class="border-b border-gray-200 col-span-6 lg:col-span-12">
                <button
                    :disabled="gameIsFinished || userAnalysisIsActive"
                    @click="$emit('takeBack')"
                    :class="{
                        'cursor-auto text-gray-400 bg-gray-50': gameIsFinished || userAnalysisIsActive,
                        'cursor-pointer text-gray-600 hover:text-primary': !gameIsFinished && !userAnalysisIsActive
                    }"
                    class="no-select text-sm lg:text-base hover:bg-gray-50 py-2 lg:py-4 lg:py-5 w-full">
                    <i class="fa fa-undo mr-0.5"></i> Take Back
                </button>
            </div>
        </div>

        <!-- Analysis -->
        <div class="block lg:flex text-gray-700 align-center justify-center text-center" style="flex-grow: 1">
            <div>
                <my-spinner
                    v-show="engineThinking || spinner || !stockFishLoaded"
                    :color="'#585858'"
                    style="margin-top: 9px" size="17px"
                ></my-spinner>

                <div v-show="!engineThinking && !spinner && stockFishLoaded" style="height: 33px; width: 100%"></div>

                <p
                    class="text-sm lg:text-base"
                    v-if="!gameIsFinished"
                >
                    Position Evaluation: <span class="font-bold">{{ evaluation }}</span>
                </p>

                <template v-else-if="source !== 'course'">
                    <p v-if="playerWon" class="font-bold text-primary">Success!</p>
                    <p class="font-bold text-danger" v-else>Better Luck Next Time!</p>
                </template>
                <p class="text-sm lg:text-base" v-if="!userAnalysisIsActive" :class="{'mb-4': source === 'course'}">
                    Move Count: <span class="font-bold">{{ moveCounter }}</span>
                </p>
            </div>
        </div>

        <position-helper-navigation
            class="hidden lg:block"
            @firstMove="$emit('firstMove')"
            @previousMove="$emit('previousMove')"
            @nextMove="$emit('nextMove')"
            @lastMove="$emit('lastMove')"
        ></position-helper-navigation>

    </div>
</template>

<script>

import {
    UilForward,
    UilBackward,
    UilSkipForward,
    UilStepBackward,
    UilRedo,
    UilLightbulbAlt
} from '@iconscout/vue-unicons';
import MySpinner from "../../assets/my-spinner";
import Chess from '../../../../../node_modules/chess.js/chess';
import {gameStatus} from "../../chess/bus/gameStatus";
import PositionHelperNavigation from "./position-helper-navigation";

export default {
    name: "position-helper",
    components: {
        PositionHelperNavigation,
        MySpinner,
        UilForward,
        UilBackward,
        UilSkipForward,
        UilStepBackward,
        UilRedo,
        UilLightbulbAlt
    },

    props: {
        position: {
            type: Object,
            default: () => null
        },
        source: {
            type: String,
            default: () => 'trainer'
        },
    },

    mounted() {
        let chess = new Chess(this.position.position);
        if (chess.turn() === 'w') {
            this.targetString = 'White to';
            this.imageURL = '/images/pieces/K.svg'
        } else {
            this.targetString = 'Black to';
            this.imageURL = '/images/pieces/k.svg'
        }
        if (this.position.target === 'w') this.targetString += ' Win';
        else this.targetString += ' Draw';
    },

    data() {
        return {
            userAnalysisIsActive: false,
            gameIsFinished: false,
            playerWon: false,
            spinner: false,
            targetString: '',
            imageURL: ''
        }
    },

    computed: {
        evaluation: {
            get() {
                return gameStatus.evaluation
            }
        },
        moveCounter: {
            get() {
                return gameStatus.moveCounter
            },
            set(value) {
                gameStatus.moveCounter = value
            }
        },
        rotate: {
            get() {
                return gameStatus.rotate
            },
            set(value) {
                gameStatus.rotate = value
            }
        },
        stockFishLoaded: {
            get() {
                return gameStatus.stockFishLoaded
            },
            set(value) {
                gameStatus.stockFishLoaded = value
            }
        },
        engineThinking: {
            get() {
                return gameStatus.engineThinking
            },
            set(value) {
                gameStatus.engineThinking = value
            }
        }
    },

    methods: {
        /**
         * User want's to analyze the position.
         */
        startUserAnalysis() {
            if (!this.userAnalysisIsActive) {
                this.$emit('startUserAnalysis', this.gameIsFinished);
                this.userAnalysisIsActive = true;
                this.gameIsFinished = false;
                this.spinner = false;
                this.playerWon = false;
            }
        },

        /**
         * Spinner setter.
         * @param {boolean} boolValue
         */
        setSpinner(boolValue) {
            this.spinner = boolValue;
        },

        /**
         * Game Over.
         */
        gameOver(playerWon) {
            this.gameIsFinished = true;
            this.playerWon = playerWon;
            this.spinner = playerWon;
        },

        /**
         * gameIsFinished Setter.
         */
        setGameIsFinished(gameIsFinished) {
            this.gameIsFinished = gameIsFinished;
        },

        /**
         * Rotate Board.
         */
        rotateBoard() {
            this.rotate = !this.rotate;
        },

        /**
         * Reset data and emit restartPosition event to parent component.
         */
        restartPosition() {
            this.engineThinking = false;
            this.gameIsFinished = false;
            this.rotate = false;
            this.playerWon = false;
            this.spinner = false;
            this.userAnalysisIsActive = false;
            this.$emit('restartPosition');
        }
    }
}
</script>
