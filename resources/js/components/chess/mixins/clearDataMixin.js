import Chess from "chess.js";

export default {
    methods: {

        /**
         * Clears extra move data.
         */
        clearExtraMoveData(){
            this.extraMovePointer = 0;
            this.extraMoveHistory = [];
        },

        /**
         * Clear arrows.
         */
        clearArrows(){
            this.$refs.arrows.setArrows([]);
        },

        /**
         * Clear highlighted squares
         */
        clearHighlights(){
            this.highlightedSquares = [];
            this.availableSquares = [];
            this.captureSquares = [];
        },

        /**
         * Stops active piece movement.
         */
        stopPieceMovement(){
            this.clearDragVariables();
            this.clearDocumentEvents();
            this.clearAvailableAndCaptureSquares();
            this.removeDocumentLevelPiece();
            this.initializePosition(this.chess.fen());
        },

        /**
         * Clears available and capture squares.
         */
        clearAvailableAndCaptureSquares(){
            this.availableSquares = [];
            this.captureSquares = [];
        },

        /**
         * Clear document events.
         */
        clearDocumentEvents(){
            document.removeEventListener('touchmove', this.dragPieceStart,{passive: true});
            document.removeEventListener('mousemove', this.dragPieceStart);
            document.removeEventListener('mouseup', this.dragPieceStop);
            document.removeEventListener('touchend', this.dragPieceStop);
        },

        /**
         * Clear last move highlight.
         */
        clearLastMoveHighlights(clearPreMove = false){
            this.lastMoves = [];
            for(let i = 1; i <= 64; i++){
                document.getElementById('square'+i).classList.remove('last-move');
                if(clearPreMove) document.getElementById('square'+i).classList.remove('preMove');
            }
        },

        /**
         * Clear pre move square highlight.
         */
        clearPreMoveHighlight(){
            for(let i = 1; i <= 64; i++) document.getElementById('square'+i).classList.remove('preMove');
        },

        /**
         * Clear variables related to dragging.
         */
        clearDragVariables(){
            this.initialSquareData = null;
            this.documentLevelPiece = null;
        },

        /**
         * Clear to square number.
         */
        clearToSquareNumber(){
            this.toSquareNumber = null;
        },

        /**
         * Clears history data.
         */
        clearHistory(){
            this.moveHistory = [];
            this.movePointer = 0;
            this.lastMoveData = null;
        },

        /**
         * Clears preMove data.
         */
        clearPreMoveData(){
            this.preMove = null;
            this.preMovePromoteTo = null;
        },

        /**
         * Stop Dragging.
         */
        stopDragging(){
            this.clearDragVariables();
            this.clearDocumentEvents();
            this.removeDocumentLevelPiece();
        },

        /**
         * Clears all.
         */
        clearAll(){
            this.clearToSquareNumber();
            this.clearArrows();
            this.$refs.arrows.deleteArrowById('engine-arrow');
            this.clearDocumentEvents();
            this.clearLastMoveHighlights(true);
            this.clearHighlights();
            this.clearDragVariables();
            this.clearHistory();
            this.clearPreMoveData();
        },

        /**
         * Restart Position.
         */
        restartPosition(){
            this.restartPositionIsActive = true;
            this.userAnalysisIsActive = false;
            this.bestMove = null;
            this.positionMarkedAsFinish = false;
            this.chess = new Chess(this.position.position);
            this.analyzePosition(this.chess.fen());
            this.initializePosition(this.chess.fen());
            this.rotate = this.playerColor === 'b';
            this.previousInitialSquareData = null;
            this.clearAll();
            this.addToMoveHistory(this.chess.fen(), null);
            this.clearExtraMoveData();
            this.$refs.checkHighlight.clear();
            this.highlightCheckSquareIfNeeded();
            this.moveCounter = 0;
        },
    }
};
