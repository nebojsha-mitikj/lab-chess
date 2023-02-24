export default {

    data(){
        return {
            boardImageURL: 'https://labchess.s3.eu-central-1.amazonaws.com/LabChessImages/chess-boards/de.png',
            lightColor: '#c9b080',
            darkColor: '#866039',
            lastMoves: []
        }
    },

    methods: {
        /**
         * Rotate board.
         */
        rotateBoard(){
            this.rotate = !this.rotate;
        },

        /**
         * Initialize chess position from FEN.
         * @param {String} fen
         */
        initializePosition(fen){
            for(let i = 1; i <= 64; i++) this.removePieceFromSquare(i);
            let counter = 1;
            let pos = fen.split(' ')[0].replaceAll('/', '')
            for (let i = 0; i < pos.length; i++) {
                let c = pos.charAt(i);
                if (c >= '0' && c <= '9')  counter += parseInt(c);
                else {
                    let el = document.getElementById('square'+counter);
                    el.classList.add('piece');
                    el.classList.add(c);
                    counter++;
                }
            }
        },

        /**
         * Set board theme.
         * @param {Object} theme
         */
        setBoardTheme(theme){
            this.boardImageURL = theme.image_url;
            this.lightColor = theme.light_square;
            this.darkColor = theme.dark_square;
        },

        /**
         * Set piece theme.
         * @param {Object} theme
         */
        setPieceTheme(theme){
            let file = document.createElement('link');
            file.rel = 'stylesheet';
            file.href = '/css/pieces/'+theme.name.toLowerCase()+'.css';
            file.id = 'pieces';
            document.head.appendChild(file);
            if(!this.exclude.includes('chessjs')) this.initializePosition(this.chess.fen());
        },

        /**
         * Highlights available squares and capture squares.
         * @param squareNumber
         */
        highlightAvailableAndCaptureSquaresForSquare(squareNumber){
            this.availableSquares = [];
            this.captureSquares = [];
            let validMoves = this.chess.moves({'square': this.notation[squareNumber]});
            for(let i = 0; i < validMoves.length; i++){
                if(this.chess.get(this.getSquareNotationFromMove(validMoves[i])))
                    this.captureSquares.push(this.number[this.getSquareNotationFromMove(validMoves[i])]);
                else this.availableSquares.push(this.number[this.getSquareNotationFromMove(validMoves[i])]);
            }
        },

        /**
         * Get the color for the highlight located on the square with number squareNum.
         * @param {Integer} squareNum
         * @returns String
         */
        getHighlightColor(squareNum){
            for(let i = 0; i < this.highlightedSquares.length; i++){
                if(this.highlightedSquares[i].square === squareNum){
                    return this.highlightedSquares[i].color;
                }
            }
            return 'transparent';
        },

        /**
         * Highlight square as preMoved.
         */
        preMoveHighlightSquare(squareNumber){
            document.getElementById('square'+squareNumber).classList.add('preMove');
        },

        /**
         * Highlight last move.
         * @param {Integer} squareNumber
         * @param piece
         */
        highlightLastMove(squareNumber, piece = false){
            this.lastMoves.push(squareNumber);
            let arr = [];
            if(piece !== false){
                arr.push('piece');
                arr.push(piece);
            }
            arr.push('last-move');
            document.getElementById('square'+squareNumber).classList.add(...arr);
        },

        /**
         * Make move sound.
         * @param {Object} move
         */
        makeSound(move){
            this.sound[this.getMoveSound(move)].currentTime = 0;
            this.sound[this.getMoveSound(move)].volume = 0.7;
            return this.sound[this.getMoveSound(move)].play();
        },

        /**
         * If chess position in check, highlight the check square.
         */
        highlightCheckSquareIfNeeded(){
            if(this.chess.in_check()){
                let king = this.chess.turn() === 'w' ? 'K' : 'k';
                let squareNumbers = this.getSquaresFromPiece(king);
                if(squareNumbers.length > 0){
                    this.$refs.checkHighlight.set(this.notation[squareNumbers[0]]);
                    return true;
                }
            }
            return false;
        },

        /**
         * Get move sound.
         * @param {Object} move
         * @returns String
         */
        getMoveSound(move){
            if(this.isCapture(move)) return 'capture';
            else if(this.chess.in_check() || this.chess.in_checkmate()) return 'move';
            return 'move';
        }
    }
}
