import Chess from "chess.js";

export default {

    data() {
        return {
            moveHistory: [],
            movePointer: 0,
            extraMoveHistory: [],
            extraMovePointer: 0,
            lastMoveData: null,
            positionMarkedAsFinish: false,
        }
    },

    mounted() {
        this.addToMoveHistory(this.position.position, null);
    },

    methods: {
        /**
         * Add move to move history.
         * @param {String} fen
         * @param {Object} lastMove
         */
        addToMoveHistory(fen, lastMove) {
            let lastMoves = [];
            if(lastMove != null) lastMoves = [this.number[lastMove.from],this.number[lastMove.to]]
            let turn = null;
            let pointerPointsToLastMove = this.movePointerPointsToLastMove();
            if (this.chess === null) turn = this.playerColor;
            else turn = this.chess.turn();

            let moveObject = {
                fen: fen,
                sound: lastMove === null ? 'move' : this.getMoveSound(lastMove),
                lastMoves: lastMoves,
                checkSquare: this.getCheckSquareNumberForFen(fen),
                arrows: [],
                highlightedSquares: [],
                toMove: turn === this.playerColor ? 'player' : 'engine'
            };

            if(!pointerPointsToLastMove && this.userAnalysisIsActive) return this.addToExtraMoveHistory(fen, moveObject);
            this.moveHistory.push(moveObject);
            if(pointerPointsToLastMove) this.movePointer = this.moveHistory.length - 1;
        },

        /**
         * Add move to extra move history.
         * @param fen
         * @param moveObject
         */
        addToExtraMoveHistory(fen, moveObject){
            let moveAlreadyExistsInMoveHistory = false;

            for(let i = 0; i < this.moveHistory.length; i++){
                if(this.moveHistory[i].fen === fen){
                    this.movePointer = i;
                    moveAlreadyExistsInMoveHistory = true;
                    this.extraMoveHistory = [];
                    this.extraMovePointer = 0;
                    break;
                }
            }

            if(!moveAlreadyExistsInMoveHistory){
                let extraPointerPointsToLastMove = this.extraMovePointer + 1 === this.extraMoveHistory.length;
                if(this.extraMoveHistory.length === 0 && this.extraMovePointer === 0) extraPointerPointsToLastMove = true;
                if(!extraPointerPointsToLastMove) this.extraMoveHistory.length = this.extraMovePointer + 1;
                this.extraMoveHistory.push(moveObject);
                this.extraMovePointer = this.extraMoveHistory.length - 1;
            }
        },

        /**
         * Checks if move is capture.
         * @param {Object} move
         * @returns boolean
         */
        isCapture(move) {
            return move != null && move.captured != null;
        },

        /**
         * Checks if move is en-passant.
         * @param {Object} move
         * @returns boolean
         */
        isEnPassant(move) {
            return move != null && move.flags === 'e';
        },

        /**
         * Check if move is valid.
         * @param {String|Null} move
         * @returns Boolean
         */
        isInvalid(move) {
            return move === null;
        },

        /**
         * Checks if move is a promotion.
         * @param {Integer} fromSquareNumber.
         * @param {Integer} toSquareNumber
         * @returns Boolean
         */
        isPromotion(fromSquareNumber, toSquareNumber) {
            let piece = this.chess.get(this.notation[fromSquareNumber]);
            if(piece == null) return false;
            piece = piece.type.toLowerCase();
            return piece === 'p' && ['8', '1'].includes(this.notation[toSquareNumber].charAt(1));
        },

        /**
         * Move the rook if castle.
         * @param {String} san
         * @param {Integer} squareNumber
         */
        moveRookOnCastle(san, squareNumber) {
            if (san === 'O-O') {
                if (squareNumber === 61) {
                    this.removePieceFromSquare(64);
                    this.addPieceToSquare('R', 62);
                } else {
                    this.removePieceFromSquare(8);
                    this.addPieceToSquare('r', 6);
                }
            } else if (san === 'O-O-O') {
                if (squareNumber === 61) {
                    this.removePieceFromSquare(57);
                    this.addPieceToSquare('R', 60);
                } else {
                    this.removePieceFromSquare(1);
                    this.addPieceToSquare('r', 4);
                }
            }
        },

        /**
         * Try to make a move.
         * @param {Object|Null} objectHelper
         * @param {Boolean} engineMove
         */
        makeMove(objectHelper = null, engineMove = false) {
            if (objectHelper !== null) {
                this.initialSquareData = objectHelper.initialSquareData;
                if(objectHelper.toSquareNumber != null) this.toSquareNumber = objectHelper.toSquareNumber;
            }
            this.restartPositionIsActive = false;
            const COLOR_TRYING_TO_MOVE = this.chess.turn();
            const SQUARE_FROM_NUMBER = this.initialSquareData.number;
            const DRAGGED_PIECE = this.initialSquareData.piece;
            const SQUARE_TO_NUMBER = this.toSquareNumber != null ? this.toSquareNumber : SQUARE_FROM_NUMBER;
            const IS_PROMOTION = this.isPromotion(SQUARE_FROM_NUMBER, SQUARE_TO_NUMBER);
            let moveData = {};

            if (IS_PROMOTION && objectHelper === null) {
                let moveTry = this.notation[SQUARE_TO_NUMBER] + '=Q';
                let validMoves = this.chess.moves({'square': this.notation[SQUARE_FROM_NUMBER]});
                let moveIsValid = false;
                for (let i = 0; i < validMoves.length; i++) {
                    if (validMoves[i].includes(moveTry)) {
                        moveIsValid = true;
                        break;
                    }
                }
                if (!moveIsValid){
                    if(!this.userAnalysisIsActive && !this.movePointerPointsToLastMove()) return;
                    return this.initializePosition(this.chess.fen());
                }
                return this.$refs.promotion.show(this.initialSquareData, SQUARE_TO_NUMBER);
            }

            if (IS_PROMOTION) moveData.promotion = objectHelper.promotionPiece;
            moveData.from = this.notation[SQUARE_FROM_NUMBER];
            moveData.to = this.notation[SQUARE_TO_NUMBER];
            const MOVE = this.chess.move(moveData);
            this.lastMoveData = MOVE;

            if (this.isInvalid(MOVE)){
                if(!this.userAnalysisIsActive && !this.movePointerPointsToLastMove()) return;
                return this.initializePosition(this.chess.fen());
            }

            if (
                !this.userAnalysisIsActive &&
                !(!engineMove && this.inCheckmate() || (this.chess.in_draw() && this.position.target === 'd'))
            )
                this.makeSound(MOVE);

            if (this.isCapture(MOVE) && (this.movePointerPointsToLastMove() || this.userAnalysisIsActive)) {
                if (this.isEnPassant(MOVE)) this.removePieceFromSquare(this.number[MOVE.to.charAt(0) + MOVE.from.charAt(1)]);
                else this.removePieceFromSquare(SQUARE_TO_NUMBER);
            }

            if(this.userAnalysisIsActive || this.movePointerPointsToLastMove()){
                if (IS_PROMOTION) {
                    this.clearLastMoveHighlights();
                    this.highlightLastMove(SQUARE_FROM_NUMBER);
                    this.addPieceToSquare(
                        (this.isWhite(DRAGGED_PIECE) ? objectHelper.promotionPiece.toUpperCase() : objectHelper.promotionPiece.toLowerCase()),
                        SQUARE_TO_NUMBER,
                        true
                    );
                } else {
                    this.clearLastMoveHighlights();
                    this.highlightLastMove(SQUARE_FROM_NUMBER);
                    this.addPieceToSquare(DRAGGED_PIECE, SQUARE_TO_NUMBER, true);
                }
            }

            if (COLOR_TRYING_TO_MOVE === this.playerColor && !this.userAnalysisIsActive) ++this.moveCounter;

            if(this.userAnalysisIsActive || this.movePointerPointsToLastMove()){
                this.removePieceFromSquare(SQUARE_FROM_NUMBER);
                this.moveRookOnCastle(MOVE.san, SQUARE_FROM_NUMBER);
                this.$refs.checkHighlight.clear();
                let checkSquareNumber = this.getCheckSquareNumberForFen(this.chess.fen());
                if (checkSquareNumber != null) this.$refs.checkHighlight.set(this.notation[checkSquareNumber]);
            }

            this.checkGameStatus();
            this.removeActivePreMoveIfGameIsFinished();
            this.initialSquareData = this.previousInitialSquareData;
            this.clearToSquareNumber();

            this.addToMoveHistory(this.chess.fen(), MOVE);

            if (!engineMove && this.chess.game_over() && !this.inCheckmate() && !this.userAnalysisIsActive && !this.playerTargetIsToDraw()){
                this.$emit('userMadeMistake', this.getMistakeData());
            }

            if (!engineMove && !this.chess.game_over() && !this.userAnalysisIsActive) {
                this.postMessageToAnalysis('quit');
                this.findBestEngineMove();
            }

            let playerWon = false;
            if (!engineMove && this.inCheckmate()) {
                this.evaluation = '-';
                if(!this.userAnalysisIsActive) {
                    this.positionFinishedSuccessfullyRequest();
                    playerWon = true;
                }
            }

            this.handleMoveIfAnalysisIsActive({fen: this.chess.fen(), newMove: true});

            if(!this.userAnalysisIsActive) {
                if(this.chess.in_draw() && this.position.target === 'd') playerWon = true;
                if(this.chess.game_over()){
                    this.evaluation = '-';
                    this.$emit('gameOver', playerWon);
                    this.postMessageToAnalysis('quit');
                }
            }
        },

        /**
         * Mark position as complete.
         */
        positionFinishedSuccessfullyRequest() {
            if(this.source !== 'trainer'){
                this.$emit('positionIsFinished');
                return this.makeSound(this.lastMoveData);
            }
            if(!this.userAnalysisIsActive && !this.positionMarkedAsFinish) {
                this.positionMarkedAsFinish = true;
                this.makeSound(this.lastMoveData).then(res => {
                    const START_EXECUTION_TIME = performance.now()
                    const MINIMUM_EXECUTION_TIME = 1500;

                    axios.post('/api/trainer/complete/' + this.position.uuid, {
                        'takeBack': this.userRequestedTakeBack
                    }).then(result => {
                        let self = this;
                        let executionTime = performance.now() - START_EXECUTION_TIME;
                        let timeout = 0;
                        if (executionTime < MINIMUM_EXECUTION_TIME) timeout = MINIMUM_EXECUTION_TIME - executionTime;
                        this.$emit('markPositionAsComplete', this.position);
                        window.setTimeout(() => {
                            self.$emit('positionIsFinished', result.data);
                        }, timeout);
                    });
                });
            }
        },

        /**
         * Gets TakeBack position index if any.
         * @returns {null|integer}
         */
        getTakeBackPositionIndex() {
            let playerToMoveCounter = 0;
            for (let i = this.moveHistory.length - 1; i >= 0; i--) {
                if (this.moveHistory[i].toMove === 'player') ++playerToMoveCounter;
                if (this.isPlayerTurn() && playerToMoveCounter === 2 || !this.isPlayerTurn() && playerToMoveCounter === 1)
                    return i;
            }
            return null;
        },

        /**
         * Triggered when user want's to take back move.
         */
        takeBack() {
            let moveDataIndex = this.getTakeBackPositionIndex();
            if(moveDataIndex != null){
                this.userRequestedTakeBack = true;
                if(!this.isPlayerTurn()){
                    this.engineSearchingForBestMove(false);
                    this.userTakeBackIsActive = true;
                }
                let moveData = this.moveHistory[moveDataIndex];
                this.movePointer = moveDataIndex;
                this.moveHistory.length = moveDataIndex+1;
                --this.moveCounter;
                this.chess = new Chess(moveData.fen)
                this.analyzePosition(moveData.fen);
                this.$refs.checkHighlight.clear();
                let checkSquareNumber = this.getCheckSquareNumberForFen(moveData.fen);
                if (checkSquareNumber != null) this.$refs.checkHighlight.set(this.notation[checkSquareNumber]);
                this.initializePosition(moveData.fen);
                this.clearHighlights();
                this.clearArrows();
                this.clearLastMoveHighlights(true);
                this.initialSquareData = null;
                if(moveData.lastMoves != null) moveData.lastMoves.forEach((move) => this.highlightLastMove(move));
            }
        },

        /**
         * Make a promotion move or add promotion preMove.
         * @param {Object} objectHelper
         * @returns {*}
         */
        promoteTo(objectHelper){
            if(this.isPlayerTurn() || this.userAnalysisIsActive) return this.makeMove(objectHelper);
            this.initialSquareData = objectHelper.initialSquareData;
            this.toSquareNumber = objectHelper.toSquareNumber;
            this.preMovePromoteTo = objectHelper.promotionPiece;
            this.addPreMove();
        },

        /**
         * Go to first move.
         */
        firstMove() {
            if ((this.movePointer > 0 || this.extraMoveHistory.length > 0) && this.moveHistory.length > 0){
                this.clearExtraMoveData();
                this.goToMove(0);
            }
        },

        /**
         * Go to previous move.
         */
        previousMove() {
            if(this.extraMoveHistory.length > 0){
                if(this.extraMovePointer === 0){
                    this.clearExtraMoveData();
                    this.goToMove(this.movePointer);
                }else{
                    this.goToMove(this.extraMovePointer - 1, true);
                }
            }else if (this.movePointer > 0 && this.moveHistory.length > 0){
                this.goToMove(this.movePointer - 1)
            }
        },

        /**
         * Go to next move.
         */
        nextMove() {
            if(this.extraMoveHistory.length > 0){
                if ((this.extraMovePointer + 1) < this.extraMoveHistory.length && this.extraMoveHistory.length > 0){
                    this.goToMove(this.extraMovePointer + 1, true);
                }
            }else{
                if ((this.movePointer + 1) < this.moveHistory.length && this.moveHistory.length > 0){
                    this.goToMove(this.movePointer + 1);
                }
            }

            if(this.movePointer + 1 === this.moveHistory.length){
                this.$emit('lastMoveReached');
            }
        },

        /**
         * Go to last move.
         */
        lastMove() {
            if(this.extraMoveHistory.length > 0){
                if ((this.extraMovePointer + 1) !== this.extraMoveHistory.length){
                    this.goToMove(this.extraMoveHistory.length - 1, true);
                }
            }else{
                if ((this.movePointer + 1) !== this.moveHistory.length && this.moveHistory.length > 0) {
                    this.goToMove(this.moveHistory.length - 1);
                }
            }
        },

        movePointerPointsToLastMove(){
            return this.movePointer + 1 === this.moveHistory.length;
        },

        /**
         * Go to move with index indexOfMove
         * @param {Integer} indexOfMove
         * @param {Boolean} extraMove
         * @param {Boolean} playSound
         */
        goToMove(indexOfMove, extraMove = false, playSound = true) {
            this.deletePreMoveIfNeeded();
            this.$refs.promotion.clearAndHide();
            let movePointer = extraMove ? this.extraMovePointer : this.movePointer;
            let moveHistory = extraMove ? this.extraMoveHistory : this.moveHistory;
            let myMove = moveHistory[indexOfMove];
            moveHistory[movePointer].arrows = this.$refs.arrows.getArrows();
            moveHistory[movePointer].highlightedSquares = this.highlightedSquares;
            this.clearHighlights();
            this.clearArrows();
            this.$refs.arrows.setArrows(myMove.arrows);
            this.highlightedSquares = myMove.highlightedSquares;
            this.initializePosition(myMove.fen);
            if(playSound){
                this.sound[myMove.sound].currentTime = 0;
                this.sound[myMove.sound].volume = 0.7;
                this.sound[myMove.sound].play();
            }
            if(extraMove){
                this.extraMovePointer = indexOfMove;
                this.extraMoveHistory = moveHistory;
            }else{
                this.movePointer = indexOfMove;
                this.moveHistory = moveHistory;
            }
            this.clearLastMoveHighlights();
            myMove.lastMoves.forEach((move) => this.highlightLastMove(move));
            this.$refs.checkHighlight.clear();
            if (myMove.checkSquare !== null) this.$refs.checkHighlight.set(this.notation[myMove.checkSquare]);
            myMove.newMove = false;
            this.handleMoveIfAnalysisIsActive(myMove);
        },

        /**
         * Handles move if analysis is active.
         */
        handleMoveIfAnalysisIsActive(move){
            if(this.userAnalysisIsActive){

                if(move.newMove) this.makeSound(this.lastMoveData);
                this.chess = new Chess(move.fen);

                if(this.gameIsFinishedExceptRepetition()){
                    this.$refs.arrows.deleteArrowById('engine-arrow');
                    this.postMessageToAnalysis('quit');
                    this.evaluation = '-';
                }else{
                    this.analyzePosition(move.fen);
                }
            }
        }

    }
}
