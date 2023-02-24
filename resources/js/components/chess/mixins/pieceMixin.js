export default {

    data(){
        return {
            // p = Black Pawn, P = White Pawn, r = Black Rook, R = white Rook, etc...
            pieces: ['p','P','r','R','n','N','b','B','q','Q','k','K'],
        }
    },

    methods: {
        /**
         * Check if piece is white.
         * @param {String} piece.
         * @returns Boolean
         */
        isWhite(piece){
            return piece.toUpperCase() === piece;
        },

        /**
         * Gets piece color.
         * @param piece
         * @returns {string}
         */
        getPieceColor(piece){
            return piece.toUpperCase() === piece ? 'w' : 'b';
        },

        /**
         * Get piece from square as string by square number if there is any.
         * @param {Integer} squareNumber
         * @returns string|null (k blackKing,K WhiteKing,etc...)
         */
        getPieceFromSquare(squareNumber){
            let square = this.getSquareElement(squareNumber);
            if(square === null) return null;
            for(let index = 0; index < this.pieces.length; index++)
                if(square.classList.contains(this.pieces[index])) return this.pieces[index];
            return null;
        },

        /**
         * Get the square numbers for the piece.
         * @param piece
         * @returns array
         */
        getSquaresFromPiece(piece){
            let pieces = document.getElementsByClassName(piece);
            let squareNumbers = [];
            for(let i = 0; i < pieces.length; i++)
                squareNumbers.push(parseInt(pieces[i].id.replace('square','')));
            return squareNumbers;
        },

        /**
         * Remove piece from square if there is any.
         * @param {Integer} squareNumber
         * @returns boolean
         */
        removePieceFromSquare(squareNumber){
            let square = this.getSquareElement(squareNumber);
            if(square === null) return false;
            for(let index = 0; index < this.pieces.length; index++)
                if(square.classList.contains(this.pieces[index])) {
                    square.classList.remove(this.pieces[index]);
                    square.classList.remove('piece');
                    return true;
                }
            return false;
        },

        /**
         * Add piece to square.
         * @param {String} piece
         * @param {Integer} squareNumber
         * @param {Boolean} hightlightAsLast
         * @returns boolean
         */
        addPieceToSquare(piece, squareNumber, hightlightAsLast = false){
            if(hightlightAsLast) {
                this.highlightLastMove(squareNumber, piece);
            }else{
                document.getElementById('square'+squareNumber).classList.add(...['piece', piece]);
            }
        },

        /**
         * Create & Add <div> element with piece class at document level.
         * @param {Event} e
         */
        createDocumentLevelPiece(e){
            const HALF_SQUARE_WIDTH = Math.floor(this.squareWidth/2);
            const SCROLL_TOP_POSITION = window.pageYOffset || document.documentElement.scrollTop;
            const DIV_SQUARE = document.createElement('div');

            DIV_SQUARE.style.width = DIV_SQUARE.style.height = this.squareWidth + 'px';
            DIV_SQUARE.style.top = (e.clientY - HALF_SQUARE_WIDTH + SCROLL_TOP_POSITION) + 'px';
            DIV_SQUARE.style.left = (e.clientX - HALF_SQUARE_WIDTH) + 'px';
            DIV_SQUARE.style.pointerEvents = 'none';
            DIV_SQUARE.classList.add(...['piece', this.initialSquareData.piece, 'absolute', 'z-50']);
            DIV_SQUARE.id = 'documentLevelPiece';

            this.documentLevelPiece = DIV_SQUARE;
            document.body.appendChild(DIV_SQUARE);
        },

        /**
         * Remove document level piece.
         */
        removeDocumentLevelPiece(){
            if(document.getElementById("documentLevelPiece") != null)
                document.body.removeChild(document.getElementById("documentLevelPiece"));
        },


        /**
         * Remove pieces from board.
         */
        removePiecesFromBoard(){
            const SQUARE_COUNT = 64;
            for(let squareNumber = 1; squareNumber <= SQUARE_COUNT; squareNumber++){
                this.removePieceFromSquare(squareNumber);
            }
        },

        /**
         * Sets document events.
         */
        setPieceDraggingEvents(){
            document.addEventListener('touchmove', this.dragPieceStart, {passive: true});
            document.addEventListener('mousemove', this.dragPieceStart);
            document.addEventListener('mouseup', this.dragPieceStop);
            document.addEventListener('touchend', this.dragPieceStop);
        },

        /**
         * On Piece Dragging update top and left.
         * @param {Event} e
         */
        dragPieceStart(e){
            if(e.type === 'touchmove') e = e.changedTouches[0];
            const HALF_SQUARE_WIDTH = Math.floor(this.squareWidth/2);
            const SCROLL_TOP_POSITION = window.pageYOffset || document.documentElement.scrollTop;
            this.documentLevelPiece.style.top = (e.clientY - HALF_SQUARE_WIDTH + SCROLL_TOP_POSITION) + 'px';
            this.documentLevelPiece.style.left = (e.clientX - HALF_SQUARE_WIDTH) + 'px';
        },

        /**
         * Checks if from square is different from to square.
         * @returns Boolean
         */
        fromSquareDifferentFromToSquare(){
            if(this.initialSquareData == null || this.toSquareNumber == null) return true;
            const SQUARE_FROM_NUMBER = this.initialSquareData.number;
            const SQUARE_TO_NUMBER = this.toSquareNumber;
            return SQUARE_FROM_NUMBER !== SQUARE_TO_NUMBER || SQUARE_TO_NUMBER !== this.toSquareNumber;
        },

        /**
         * On Piece Dragging stop clear data.
         * @param {Event} e
         */
        dragPieceStop(e){
            e.preventDefault();
            if(this.fromSquareDifferentFromToSquare()) this.clearHighlights();
            if(!this.userAnalysisIsActive && !this.positionUsedForRecording() && !this.isPlayerTurn()) return this.addPreMove();
            this.makeMove();
            if(this.fromSquareDifferentFromToSquare()) this.clearDragVariables();
            this.removeDocumentLevelPiece();
            this.clearDocumentEvents();
        },
    }
}
