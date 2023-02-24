import Chess from "chess.js";

export default {

    data(){
        return {
            playerColor: 'w',
            userTakeBackIsActive: false,
            userRequestedTakeBack: false
        }
    },

    methods: {

        /**
         * Use last position for recording videos (for now).
         */
        positionUsedForRecording(){
            return this.position.number === 3 && this.position.variant_id === 129;
        },

        /**
         * Checks game status.
         */
        checkGameStatus(){
            this.inCheckmate();
            this.inCheck();
            this.inDraw();
        },

        /**
         * Checks if game is finished.
         * @returns {boolean}
         */
        gameIsFinished(){
            return this.chess.in_checkmate() || this.chess.in_draw();
        },

        /**
         * Checks if the game is finished both when analysis/game is active.
         * @returns {boolean}
         */
        isGameFinishedEvenIfAnalyzing(){
            return !this.userAnalysisIsActive && this.chess.game_over() ||
            this.userAnalysisIsActive && this.gameIsFinishedExceptRepetition();
        },

        /**
         * Checks if the game is active and if the user is currently looking at move history.
         * @returns {boolean}
         */
        gameIsActiveAndUserLooksMoveHistory(){
            return !this.userAnalysisIsActive && this.movePointer + 1 !== this.moveHistory.length;
        },

        /**
         * Checks if the square piece cannot be moved.
         */
        squarePieceCannotBeMoved(squareNumber){
            let pieceFromSquare = this.getPieceFromSquare(squareNumber);
            return this.userAnalysisIsActive &&
            this.initialSquareData == null &&
            pieceFromSquare != null &&
            this.getPieceColor(pieceFromSquare) !== this.chess.turn();
        },

        /**
         * Checks if square with number has piece.
         * @param {integer} squareNumber
         * @returns {boolean}
         */
        squareHasPiece(squareNumber){
            return this.getPieceFromSquare(squareNumber) != null;
        },

        /**
         * Checks if two pieces have same color.
         * @param {string} firstPiece
         * @param {string} secondPiece
         * @returns {boolean}
         */
        piecesHaveSameColor(firstPiece, secondPiece){
            return this.isWhite(firstPiece) === this.isWhite(secondPiece)
        },

        /**
         * Checks if the game is finished without checking threefold repetition.
         * @returns {boolean}
         */
        gameIsFinishedExceptRepetition(){
            return this.chess.in_checkmate() || this.chess.in_stalemate() || this.chess.insufficient_material();
        },

        /**
         * Checkmate action.
         */
        inCheckmate(){
            return this.chess.in_checkmate();
        },

        /**
         * Check action.
         */
        inCheck(){
            if(this.chess.in_check() && this.movePointerPointsToLastMove()){
                this.$refs.checkHighlight.set(this.notation[this.getCheckSquareNumberForFen(this.chess.fen())]);
            }
            return this.chess.in_check();
        },

        /**
         * Get Check Square Number
         */
        getCheckSquareNumberForFen(fen){
            let innerChess = new Chess(fen);
            if(innerChess.in_check()){
                let king = innerChess.turn() === 'w' ? 'K' : 'k';
                let board = innerChess.board();
                let checkSquareNumber = 0;

                for(let rowIndex = 0; rowIndex < board.length; rowIndex++){
                    let row = board[rowIndex];
                    for(let colIndex = 0; colIndex < row.length; colIndex++){
                        ++checkSquareNumber;
                        let squarePiece = row[colIndex];
                        if(squarePiece != null){
                            squarePiece = squarePiece.color === 'w' ? squarePiece.type.toUpperCase() : squarePiece.type;
                            if(squarePiece === king) return checkSquareNumber;
                        }
                    }
                }
            }
            return null;
        },

        /**
         * Draw action.
         */
        inDraw(){
            if(this.chess.in_draw() && this.position.target === 'd' && !this.userAnalysisIsActive)
                this.positionFinishedSuccessfullyRequest();
            return this.chess.in_draw();
        },

        /**
         * Checks if it's player's turn.
         * @returns {boolean}
         */
        isPlayerTurn(){
            return this.chess.turn() === this.playerColor;
        },

        /**
         * Checks if player's target is to win.
         * @returns {boolean}
         */
        playerTargetIsToWin(){
            return this.position.target === 'w';
        },

        /**
         * Checks if player's target is to draw.
         * @returns {boolean}
         */
        playerTargetIsToDraw(){
            return this.position.target === 'd';
        },

        /**
         * Check if player is white.
         * @returns {boolean}
         */
        playerIsWhite(){
            return this.playerColor === 'w';
        },

        /**
         * Check if player is black.
         * @returns {boolean}
         */
        playerIsBlack(){
            return this.playerColor === 'b';
        },

        /**
         * Check if the square piece color is same as turn color.
         * @param {Number} squareNumber
         * @returns {boolean}
         */
        squarePieceColorSameAsTurnColor(squareNumber){
            return (this.isWhite(this.getPieceFromSquare(squareNumber)) ? 'w' : 'b') === this.chess.turn();
        },

        /**
         * Check if the square piece color is same as player color.
         * @param {Number} squareNumber
         * @returns {boolean}
         */
        squarePieceColorSameAsPlayerColor(squareNumber){
            let squarePiece = this.getPieceFromSquare(squareNumber);
            if(squarePiece == null) return false;
            return (this.isWhite(squarePiece) ? 'w' : 'b') === this.playerColor;
        },

        /**
         * Get chess state.
         * @returns {Object}
         */
        getChessState(){
            return {
                rotate: this.rotate,
                userAnalysisIsActive: this.userAnalysisIsActive,
                moveHistory: this.moveHistory,
                movePointer: this.movePointer
            }
        },
    }
}
