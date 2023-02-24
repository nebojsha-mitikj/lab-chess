<template>
    <div
        id="chess-board"
        class="w-full flex flex-wrap relative b-radius"
        :class="{'rotate': rotate}"
        @mouseleave="toSquareNumber = null" @contextmenu="(e) => e.preventDefault()"
        :style="'background-image: url('+boardImageURL+');'"
        :draggable="false"
    >
        <promotion ref="promotion" @exitPromotion="(e) => addPieceToSquare(e.piece, e.number)"
                   @promoteTo="promoteTo"></promotion>
        <coordinates :rotate="rotate" :lightColor="lightColor" :darkColor="darkColor"></coordinates>
        <!-- Squares -->
        <div
            v-for="num in 64"
            :key="'C'+num"
            class="z-20 square relative no-select"
            :class="{'validSquare': num === 1}"
            :draggable="false"
            :id="'square'+num"
            :ref="'square'+num"
            @mousedown="mouseDown($event, num)"
            @mouseenter="mouseEnter($event,num)"
            @mouseup="mouseUp($event, num)"
        >
            <div
                v-if="highlightedSquares.length > 0"
                :style="'border-color: '+getHighlightColor(num)"
                :class="{'highlight': highlightedSquares.filter(e => e.square === num).length > 0}">
            </div>
            <div
                v-if="availableSquares.length > 0 || captureSquares.length > 0"
                :class="{
                    'available': availableSquares.filter(e => e === num).length > 0,
                    'capture': captureSquares.filter(e => e === num).length > 0
                }"
            ></div>
            <div
                v-if="initialSquareData != null && toSquareNumber === num &&
                (availableSquares.includes(toSquareNumber) || captureSquares.includes(toSquareNumber))"
                class="dragged-over"
            ></div>
        </div>
        <check-highlight ref="checkHighlight"></check-highlight>
        <arrows :positionUsedForRecording="positionUsedForRecording()" ref="arrows"></arrows>
    </div>
</template>

<script>

// Components
import Arrows from "./components/arrows";
import Coordinates from "./components/coordinates";
import Promotion from "./components/promotion";
import CheckHighlight from "./components/check-highlight";

// Mixins
import clearDataMixin from './mixins/clearDataMixin';
import pieceMixin from './mixins/pieceMixin'
import moveMixin from './mixins/moveMixin';
import gameStatusMixin from "./mixins/gameStatusMixin";
import touchEventMixin from "./mixins/touchEventMixin";
import mouseEventMixin from "./mixins/mouseEventMixin";
import squareMixin from "./mixins/squareMixin";
import premoveMixin from "./mixins/premoveMixin";
import boardMixin from "./mixins/boardMixin";
import stockfishMixin from "./mixins/stockfishMixin";
import Chess from '../../../../node_modules/chess.js/chess';
import {gameStatus} from "./bus/gameStatus";

export default {
    name: "chess-board",

    components: {
        CheckHighlight,
        Promotion,
        Coordinates,
        Arrows
    },

    mixins: [
        clearDataMixin,
        pieceMixin,
        moveMixin,
        gameStatusMixin,
        touchEventMixin,
        mouseEventMixin,
        premoveMixin,
        boardMixin,
        squareMixin,
        stockfishMixin
    ],

    props: {
        mistakeEvaluation: {
            type: Number,
            default: () => 0
        },
        mistakeDepth: {
            type: Number,
            default: () => 20
        },
        positionIsFinishedProp: {
            type: Boolean,
            default: () => false
        },
        rotateBoardProp: {
            default: () => null
        },
        chessState: {
            type: Object,
            default: () => null
        },
        userConfiguration: {
            type: Object,
            default: () => {}
        },
        position: {
            type: Object,
            default: () => {}
        },
        exclude: {
            type: Array,
            default: () => []
        },
        source: {
            type: String,
            default: () => 'undefined'
        },
        sound: {
            type: Object,
            default: function () {
                return {
                    goalReached: new Audio('/sounds/success-trumpet.mp3'),
                    success: new Audio('/sounds/success.mp3'),
                    move: new Audio('/sounds/m.ogg'),
                    check: new Audio('/sounds/m.ogg'),
                    capture: new Audio('/sounds/c.ogg')
                }
            }
        }
    },

    computed: {
        /**
         * Read the chess position.
         */
        readPosition() {
            return this.position.position.split(' ')[0].replaceAll('/', '');
        },

        /**
         * Move counter.
         */
        moveCounter: {
            get() {
                return gameStatus.moveCounter
            },
            set(value) {
                gameStatus.moveCounter = value
            }
        },

        /**
         * Rotate Board.
         */
        rotate: {
            get() {
                return gameStatus.rotate
            },
            set(value) {
                gameStatus.rotate = value
            }
        },
        /**
         * Engine currently thinking.
         */
        engineThinking: {
            get() {
                return gameStatus.engineThinking
            },
            set(value) {
                gameStatus.engineThinking = value
            }
        }
    },

    mounted() {
        this.chess = new Chess(
            this.chessState == null ? this.position.position : this.chessState.moveHistory[this.chessState.movePointer].fen
        );
        if (this.chessState == null) {
            this.playerColor = this.chess.turn();
        } else {
            this.playerColor = (new Chess(this.chessState.moveHistory[0].fen)).turn();
        }

        if (this.chessState != null && this.chessState.rotate != null) {
            this.rotate = this.chessState.rotate;
        }else{
            this.rotate = this.playerColor === 'b';
        }

        if(this.rotateBoardProp !== null) this.rotate = this.rotateBoardProp;

        this.initializePosition(this.chess.fen());
        this.highlightCheckSquareIfNeeded();

        this.setPieceTheme(this.userConfiguration.piece_theme);
        this.setBoardTheme(this.userConfiguration.board_theme);

        if (this.chessState != null) {
            this.moveHistory = this.chessState.moveHistory;
            let arrows = this.chessState.moveHistory[0].arrows;
            let highlights = this.chessState.moveHistory[0].highlightedSquares;
            this.goToMove(this.chessState.movePointer, false, false);

            if(arrows != null && arrows.length > 0){
                if(this.chessState.movePointer === 0) this.$refs.arrows.setArrows(arrows);
                this.chessState.moveHistory[0].arrows = arrows;
            }
            if(highlights != null && highlights.length > 0){
                if(this.chessState.movePointer === 0) this.highlightedSquares = highlights;
                this.chessState.moveHistory[0].highlightedSquares = highlights;
            }
        }

        if (!this.exclude.includes('engine')) {
            this.startStockfishEngine();
            if(this.chessState != null && this.chessState.userAnalysisIsActive){
                this.startUserAnalysis(false);
            }else if (this.playerColor !== this.chess.turn() && !this.chess.game_over()) {
                this.findBestEngineMove();
            }
        }
    },

    data() {
        return {
            chess: null,
            captureSquares: [],
            availableSquares: [],
            toSquareNumber: null,
            highlightedSquares: [],
            initialSquareData: null,
            documentLevelPiece: null,
            previousInitialSquareData: null,
        }
    }
}
</script>
