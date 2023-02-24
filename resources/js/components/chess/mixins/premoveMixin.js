export default {

    data() {
        return {
            preMove: null,
            preMovePromoteTo: null
        }
    },

    methods: {

        /**
         * Check if user tries to make a preMove.
         * @returns {boolean}
         */
        isPreMove(){
            return !this.isPlayerTurn() && this.preMove == null
        },

        /**
         * Handle PreMove drag start.
         * @param num
         * @param e
         */
        preMoveDragStart(num, e){
            this.initialSquareData = {number: num, piece: this.getPieceFromSquare(num)};
            this.removePieceFromSquare(num);
            let validPreMoveSquareNumbers = this.getValidPreMoves();
            for(let i = 0; i < validPreMoveSquareNumbers.length; i++){
                if(this.getPieceFromSquare(validPreMoveSquareNumbers[i]) == null)
                    this.availableSquares.push(validPreMoveSquareNumbers[i]);
                else this.captureSquares.push(validPreMoveSquareNumbers[i]);
            }
            this.squareWidth = this.getSquareElement(num).clientWidth;
            this.createDocumentLevelPiece(e);
            this.removePieceFromSquare(num);
            this.setPieceDraggingEvents();
        },

        /**
         * Adds preMove.
         */
        addPreMove(){
            this.removeDocumentLevelPiece();
            this.clearDocumentEvents();
            if(!this.getValidPreMoves().includes(this.toSquareNumber)) {
                if(this.initialSquareData != null && this.initialSquareData.number !== this.toSquareNumber)
                    this.initialSquareData = null;
                this.initializePosition(this.chess.fen());
                return false;
            }
            let isPromotion = this.isPromotion(this.initialSquareData.number, this.toSquareNumber);
            if(isPromotion && this.preMovePromoteTo == null){
                this.$refs.promotion.show(this.initialSquareData, this.toSquareNumber);
                return true;
            }
            let initialSquareData = this.initialSquareData;
            let fromSquareDifferentFromToSquare = this.fromSquareDifferentFromToSquare();
            this.initialSquareData = null;
            if(!fromSquareDifferentFromToSquare){
                this.addPieceToSquare(initialSquareData.piece, initialSquareData.number);
            }else{
                this.preMove = {
                    initialSquareData: initialSquareData,
                    toSquareNumber: this.toSquareNumber,
                };
                if(this.preMovePromoteTo != null) this.preMove.promoteTo = this.preMovePromoteTo;
                this.highlightLastMove(initialSquareData.number);
                let piece = this.preMovePromoteTo != null ? this.preMovePromoteTo : initialSquareData.piece;
                this.preMovePromoteTo = null;
                if(initialSquareData.piece === initialSquareData.piece.toUpperCase()) piece = piece.toUpperCase();
                this.initializePosition(this.chess.fen());
                this.preMoveHighlightSquare(this.toSquareNumber);
            }
            return true;
        },

        /**
         * Make a move from the preMove data.
         * @returns boolean
         */
        makePreMoveMove(){
            if(this.preMove != null){
                document.getElementById('square'+this.preMove.toSquareNumber).classList.remove('preMove');
                if(this.gameIsFinished()) {
                    this.preMove = null;
                    return false;
                }
                let objectHelper = null;
                this.initialSquareData = this.preMove.initialSquareData;
                this.toSquareNumber = this.preMove.toSquareNumber;
                if(this.preMove.promoteTo != null)
                    objectHelper = {
                        initialSquareData: this.initialSquareData,
                        promotionPiece: this.preMove.promoteTo
                    };
                this.preMove = null;
                this.makeMove(objectHelper,false);
                return true;
            }
            return false;
        },

        delay(time){
            return new Promise(resolve => setTimeout(resolve, time));
        },

        /**
         * Removes active preMove if the game is finished.
         */
        removeActivePreMoveIfGameIsFinished(){
            if(this.chess.game_over()){
                this.initializePosition(this.chess.fen());
                this.stopDragging();
                this.clearAvailableAndCaptureSquares();
                return true;
            }
            return false;
        },

        /**
         * Deletes preMove if needed.
         */
        deletePreMoveIfNeeded(){
            if(this.preMove != null){
                document.getElementById('square'+this.preMove.toSquareNumber).classList.remove('preMove');
                this.preMove = null;
                this.clearHighlights();
                this.initializePosition(this.chess.fen());
            }
        },

        /**
         * Get valid pre-moves.
         */
        getValidPreMoves(){
            let squareNumber = this.initialSquareData.number;
            let piece = this.initialSquareData.piece;
            let matrix = [];
            let value = 1;
            let rowIndex = null;
            let colIndex = null;
            for(let row = 0; row < 8; row++){
                matrix.push([]);
                for(let col = 0; col < 8; col++){
                    matrix[row].push(value);
                    if(squareNumber === value){
                        rowIndex = row;
                        colIndex = col;
                    }
                    ++value;
                }
            }
            if(rowIndex != null && colIndex != null){
                if(piece.toLowerCase() === 'k') return this.getValidPreMovesForKing(matrix, rowIndex, colIndex);
                if(piece.toLowerCase() === 'b') return this.getValidPreMovesForBishop(matrix, rowIndex, colIndex);
                if(piece.toLowerCase() === 'q') return this.getValidPreMovesForQueen(matrix, rowIndex, colIndex);
                if(piece.toLowerCase() === 'r') return this.getValidPreMovesForRook(matrix, rowIndex, colIndex);
                if(piece.toLowerCase() === 'n') return this.getValidPreMovesForKnight(matrix, rowIndex, colIndex);
                if(piece.toLowerCase() === 'p') return this.getValidPreMovesForPawn(matrix, rowIndex, colIndex, piece);
            }
            return [];
        },

        /**
         * Get Valid PreMove Squares For King.
         * @param matrix
         * @param rowIndex
         * @param colIndex
         * @returns {*[]}
         */
        getValidPreMovesForKing(matrix, rowIndex, colIndex){
            let validSquareNumbers = [];
            for(let i = -1; i < 2; i++){
                for(let j = -1; j < 2; j++){
                    if(i === 0 && j === 0) continue;
                    let newRow = rowIndex + i;
                    let newCol = colIndex + j;
                    if(newRow >= 0 && newRow <= 7 && newCol >= 0 && newCol <= 7)
                        validSquareNumbers.push(matrix[newRow][newCol]);
                }
            }
            return validSquareNumbers;
        },

        /**
         * Get Valid PreMove Squares For Bishop.
         * @param matrix
         * @param rowIndex
         * @param colIndex
         * @returns {*[]}
         */
        getValidPreMovesForBishop(matrix, rowIndex, colIndex){
            let validSquareNumbers = [];
            for(let i = 1; i < 8; i++){
                let rows = [rowIndex + i, rowIndex - i];
                let columns = [colIndex + i, colIndex - i];
                rows.forEach(newRow => {
                    columns.forEach(newCol => {
                        if(newRow >= 0 && newRow <= 7 && newCol >= 0 && newCol <= 7){
                            validSquareNumbers.push(matrix[newRow][newCol]);
                        }
                    });
                });
            }
            return validSquareNumbers;
        },

        /**
         * Get Valid PreMove Squares For Queen.
         * @param matrix
         * @param rowIndex
         * @param colIndex
         * @returns {*[]}
         */
        getValidPreMovesForQueen(matrix, rowIndex, colIndex){
            let validSquareNumbers = [];
            for(let i = 1; i < 8; i++){
                let rows = [rowIndex ,rowIndex + i, rowIndex - i];
                let columns = [colIndex, colIndex + i, colIndex - i];
                rows.forEach(newRow => {
                    columns.forEach(newCol => {
                        if(newRow !== rowIndex || newCol !== colIndex)
                            if(newRow >= 0 && newRow <= 7 && newCol >= 0 && newCol <= 7)
                                validSquareNumbers.push(matrix[newRow][newCol]);
                    });
                });
            }
            return validSquareNumbers;
        },

        /**
         * Get Valid PreMove Squares For Rook.
         * @param matrix
         * @param rowIndex
         * @param colIndex
         * @returns {*[]}
         */
        getValidPreMovesForRook(matrix, rowIndex, colIndex){
            let validSquareNumbers = [];
            for(let i = 1; i < 8; i++){
                let rows = [rowIndex + i,rowIndex - i];
                let columns = [colIndex + i,colIndex - i];
                rows.forEach(newRow => {
                    columns.forEach(newCol => {
                        if(newCol >= 0 && newCol <= 7) validSquareNumbers.push(matrix[rowIndex][newCol]);
                        if(newRow >= 0 && newRow <= 7) validSquareNumbers.push(matrix[newRow][colIndex]);
                    });
                });
            }
            return [...new Set(validSquareNumbers)];
        },

        /**
         * Get Valid PreMove Squares For Knight.
         * @param matrix
         * @param rowIndex
         * @param colIndex
         * @returns {*[]}
         */
        getValidPreMovesForKnight(matrix, rowIndex, colIndex){
            let validSquareNumbers = [];
            let twos = [2,-2];
            let ones = [1,-1];
            twos.forEach(two => {
                ones.forEach(one => {
                    if(rowIndex+two >=0 && rowIndex+two <= 7 && colIndex+one >= 0 && colIndex+one <= 7){
                        validSquareNumbers.push(matrix[rowIndex+two][colIndex+one]);
                    }
                    if(colIndex+two >=0 && colIndex+two <= 7 && rowIndex+one >= 0 && rowIndex+one <= 7){
                        validSquareNumbers.push(matrix[rowIndex+one][colIndex+two]);
                    }
                });
            });
            return validSquareNumbers;
        },

        /**
         * Get Valid PreMove Squares For Pawn.
         * @param matrix
         * @param rowIndex
         * @param colIndex
         * @param piece
         * @returns {*[]}
         */
        getValidPreMovesForPawn(matrix, rowIndex, colIndex, piece){
            let validSquareNumbers = [];
            let isWhite = piece === piece.toUpperCase();
            let rows = isWhite ? [-1] : [1];
            if (rowIndex === 6) isWhite ? rows.push(-2) : rows.push(2);
            rows.forEach(row => {
                if (rowIndex + row >= 0 && rowIndex + row <= 7)
                    validSquareNumbers.push(matrix[rowIndex + row][colIndex]);
            });
            let myIndex = isWhite ? -1 : 1;
            if (rowIndex + myIndex >= 0 && rowIndex + myIndex <= 7) {
                if (colIndex + 1 >= 0 && colIndex + 1 <= 7)
                    validSquareNumbers.push(matrix[rowIndex + myIndex][colIndex + 1])
                if (colIndex - 1 >= 0 && colIndex - 1 <= 7)
                    validSquareNumbers.push(matrix[rowIndex + myIndex][colIndex - 1])
            }
            return validSquareNumbers;
        }
    }
}
