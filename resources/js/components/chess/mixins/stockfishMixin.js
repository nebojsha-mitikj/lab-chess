import Chess from "chess.js";
import {gameStatus} from "../bus/gameStatus";

export default {

    data(){
        return {
            stockfishWorkerForConstantAnalysis: null,
            stockfishWorkerForOpponent: null,
            userAnalysisIsActive: false,
            bestMove: null,
            analyzingForColor: null,
            addNewArrows: false,
            previousBestMove: null,
            previousBestMoveFlag: false
        }
    },

    computed: {
        evaluation: {
            get() {return gameStatus.evaluation},
            set(value){gameStatus.evaluation  = value}
        },
        stockFishLoaded: {
            get() {return gameStatus.stockFishLoaded},
            set(value){gameStatus.stockFishLoaded  = value}
        }
    },

    methods: {
        /**
         * Starts StockFish engine.
         */
        startStockfishEngine(){
            this.stockfishWorkerForConstantAnalysis = new Worker(window.location.origin+'/js/stockfish.js');
            this.stockfishWorkerForOpponent = new Worker(window.location.origin+'/js/stockfish.js');
            this.postMessageToAnalysis('setoption name Threads value ' + navigator.hardwareConcurrency);
            this.postMessageToOpponent('setoption name Threads value ' + navigator.hardwareConcurrency);
            this.analyzePosition(this.chess.fen());
        },

        /**
         * Post pessage to the stockfishWorkerForOpponent Worker.
         * @param {String} str
         */
        postMessageToOpponent(str){
            this.stockfishWorkerForOpponent.postMessage(str);
        },

        /**
         * Post message to the stockfishWorkerForConstantAnalysis Worker.
         * @param {String} str
         */
        postMessageToAnalysis(str){
            this.stockfishWorkerForConstantAnalysis.postMessage(str);
        },

        /**
         * Analyze position.
         * @param {String} fen
         */
        analyzePosition(fen){
            if(this.userAnalysisIsActive){
                this.$refs.arrows.deleteArrowById('engine-arrow');
                this.addNewArrows = true;
            }
            let self = this;
            let goDepth = 'go depth ' + (this.userAnalysisIsActive ? '30 movetime 4500' : '40');
            this.postMessageToAnalysis('quit');
            this.postMessageToAnalysis('uci');
            this.postMessageToAnalysis('position fen '+fen);
            this.postMessageToAnalysis(goDepth);
            let tempChess = new Chess(self.chess.fen());
            let currentAnalysedChess = new Chess(fen);
            this.previousBestMoveFlag = false;
            this.stockfishWorkerForConstantAnalysis.onmessage = function(event){
                if(!self.userAnalysisIsActive && self.chess.game_over() || self.userAnalysisIsActive && self.gameIsFinishedExceptRepetition()) {
                    self.evaluation = '-';
                    self.$refs.arrows.deleteArrowById('engine-arrow');
                    self.postMessageToAnalysis('quit');
                }else{
                    let message = event.data ? event.data : event;

                    if(message === 'uciok') {
                        self.analyzingForColor = tempChess.turn();
                        if(self.userAnalysisIsActive) self.addNewArrows = true;
                    }

                    let turn = self.userAnalysisIsActive ? self.analyzingForColor : fen.split(' ')[1];

                    if (self.isString(message)) {
                        if(!self.stockFishLoaded) self.stockFishLoaded = true;

                        self.getBestMoveFromMessage(message, currentAnalysedChess);
                        self.getEvaluationFromMessage(message, turn);
                        if(self.getDepthFromMessage(message) > self.mistakeDepth){
                            self.detectUserMistake();
                        }else if(self.playerTargetIsToWin() && currentAnalysedChess.in_draw()){
                            if((self.userAnalysisIsActive || self.positionIsFinishedProp || self.moveHistory.length === 1)) return;
                            self.$emit('userMadeMistake', {
                                bestMove: self.bestMove,
                                lastMove: self.getLastMove()
                            });
                        }

                    }
                }
            };
        },

        /**
         * Finds the best move from StockFish message.
         * @param {string} message
         * @param {Object|Null} tempChess
         */
        getBestMoveFromMessage(message, tempChess = null){
            let indexOfPv = message.indexOf(' pv ');

            if(!this.userAnalysisIsActive && indexOfPv !== -1){
                if(tempChess != null && !tempChess.game_over() && this.previousBestMoveFlag === false){
                    this.previousBestMove = this.bestMove;
                    this.previousBestMoveFlag = true;
                }
                this.bestMove = message.slice(indexOfPv).split(' ').slice(2,3)[0];
            }

            if(this.userAnalysisIsActive && indexOfPv !== -1){
                let previousBestMove = this.bestMove;
                this.bestMove = message.slice(indexOfPv).split(' ').slice(2,3)[0];

                if(previousBestMove !== this.bestMove && this.addNewArrows){
                    this.$refs.arrows.deleteArrowById('engine-arrow');
                    this.$refs.arrows.pushArrow({
                        id: 'engine-arrow',
                        firstSquare: this.number[this.bestMove.substring(2,0)],
                        secondSquare: this.number[this.bestMove.substring(4,2)],
                        color: '#484848'
                    });
                }
            }

        },

        /**
         * Gets depth from StockFish output.
         * @param {String} message
         * @returns Number
         */
        getDepthFromMessage(message){
            let indexOfDepth = message.indexOf(' depth ');
            return indexOfDepth !== -1 ? parseInt(message.slice(indexOfDepth).split(' ')[2]) : -1;
        },

        /**
         * Evaluates position score.
         * @param {string} turn
         * @param {string} message
         */
        getEvaluationFromMessage(message, turn){
            let indexOfScore = message.indexOf('score ');

            if(indexOfScore !== -1){

                let indexOfPv = message.indexOf(' pv ');

                if(indexOfPv !== -1) {
                    let bestMove = message.slice(indexOfPv).split(' ').slice(2, 3)[0];
                    let pieceData = this.chess.get(bestMove.substring(2, 0));
                    if (pieceData != null && pieceData.color != null) turn = pieceData.color;
                }

                let score = message.slice(indexOfScore).split(' ').slice(1, 3);
                if(score[0] === 'cp'){
                    this.evaluation = this.getScoreBasedOnTurn((score[1] / 100), turn);
                }else if(score[0] === 'mate'){
                    this.evaluation = '#'+this.getScoreBasedOnTurn(score[1], turn).replace('+','');
                }
            }
        },

        /**
         * Gets Score Based On Turn.
         * @param score
         * @param turn
         * @returns {string}
         */
        getScoreBasedOnTurn(score, turn){
            if(score === 0 || score === '0' || score === '-0') return '0';
            let symbol = score > 0 ? '+' : '-';
            if(turn === 'b' && symbol === '-') symbol = '+';
            else if(turn === 'b' && symbol === '+') symbol = '-';
            return symbol + Math.abs(score);
        },

        /**
         * Check if string
         */
        isString(something){
            return typeof something === 'string' || something instanceof String
        },

        /**
         * Find Best Engine Move.
         */
        findBestEngineMove(){
            const START_EXECUTION_TIME = performance.now()
            const MINIMUM_EXECUTION_TIME = 1000;
            let self = this;
            this.engineSearchingForBestMove(true);

            let commands = ['stop', 'uci', 'position fen '+this.chess.fen(), 'go depth 35 movetime 6500', 'd'];
            for(let i = 0; i < commands.length; i++) this.postMessageToOpponent(commands[i]);

            this.stockfishWorkerForOpponent.onmessage = function(event){
                if(self.userAnalysisIsActive) return;
                let message = event.data ? event.data : event;
                if((typeof message === 'string' || message instanceof String) && message.indexOf('bestmove ') === 0){
                    let bestMove = message.split(' ')[1];
                    let tempChess = new Chess(self.chess.fen());
                    tempChess.move({
                        from: bestMove.substring(0,2),
                        to: bestMove.substring(2,4),
                    });
                    self.analyzePosition(tempChess.fen());
                    let executionTime = performance.now() - START_EXECUTION_TIME;
                    let timeout = 0;
                    if(executionTime < MINIMUM_EXECUTION_TIME) timeout = MINIMUM_EXECUTION_TIME - executionTime;
                    window.setTimeout(() => {self.makeEngineMove(bestMove)},timeout);
                }
            };
        },

        /**
         * Emits engineSearchingForBestMove event with bool parameter.
         * @param {Boolean} value
         */
        engineSearchingForBestMove(value){
            this.engineThinking = value;
        },

        /**
         * The engine makes move.
         * @param bestMove
         */
        makeEngineMove(bestMove){
            if(this.userTakeBackIsActive) return this.userTakeBackIsActive = false;
            if(this.restartPositionIsActive) return this.restartPositionIsActive = false;
            if(this.userAnalysisIsActive) return;

            let fromSquareNumber = this.number[bestMove.substring(0,2)];
            let toSquareNumber = this.number[bestMove.substring(2,4)];

            if(this.getSquareElement(fromSquareNumber) == null) return;

            this.squareWidth = this.getSquareElement(fromSquareNumber).clientWidth;
            this.toSquareNumber = toSquareNumber;

            if(this.$refs.promotion == null) return;
            if(this.$refs.promotion.getPromotionIsActive()) this.previousInitialSquareData = null;
            else this.previousInitialSquareData = this.initialSquareData;

            this.initialSquareData = {
                number: fromSquareNumber,
                piece: this.getPieceFromSquare(fromSquareNumber)
            };
            let objectHelper = null;
            let lastChar = bestMove.charAt(bestMove.length - 1);

            if(lastChar.toLowerCase() !== lastChar.toUpperCase()){
                objectHelper = {
                    initialSquareData: this.initialSquareData,
                    promotionPiece: lastChar
                };
            }
            this.engineSearchingForBestMove(false);
            this.makeMove(objectHelper, true);
            this.makePreMoveMove();
            if((this.availableSquares.length > 0 || this.captureSquares.length > 0) && this.initialSquareData != null){
                this.highlightAvailableAndCaptureSquaresForSquare(this.initialSquareData.number);
            }
        },

        /**
         * Start User Analysis.
         * On Analyze Position Button Click.
         */
        startUserAnalysis(gameIsFinished){
            this.userAnalysisIsActive = true;
            this.postMessageToOpponent('stop');
            this.postMessageToOpponent('quit');
            this.engineSearchingForBestMove(false);
            let positionToAnalyze = null;
            this.clearPreMoveData();
            this.clearPreMoveHighlight();
            this.$refs.promotion.clearAndHide();

            this.bestMove = null;

            if(gameIsFinished){
                positionToAnalyze = this.moveHistory[0];
                this.movePointer = 0;
                this.goToMove(0);
            }else{
                positionToAnalyze = this.moveHistory[this.movePointer];
                this.chess = new Chess(positionToAnalyze.fen);
                this.analyzePosition(positionToAnalyze.fen);
            }
            this.initializePosition(positionToAnalyze.fen);
        },

        /**
         * Detect user mistake.
         * @returns {boolean}
         */
        detectUserMistake(){
            if(this.userAnalysisIsActive || this.positionIsFinishedProp || this.moveHistory.length === 1){
                return false;
            }
            if(this.evaluation.length > 0){
                const firstChar = this.evaluation.charAt(0);
                const secondChar = this.evaluation.length > 1 ? this.evaluation.charAt(1) : false;

                let evaluationNumber = this.evaluation.match(/[0-9].[0-9]/g);
                if(evaluationNumber != null && Array.isArray(evaluationNumber) && evaluationNumber.length > 0){
                    evaluationNumber = parseFloat(evaluationNumber[0]);
                }else{
                    evaluationNumber = false;
                }

                if(this.playerIsWhite() && this.playerTargetIsToWin()){
                    if(this.evaluation === '0' || firstChar === '-' || firstChar === '#' && secondChar === '-' || this.evaluation.includes('+0.')){
                        this.$emit('userMadeMistake', this.getMistakeData());
                        return true;
                    }
                }else if(this.playerIsBlack() && this.playerTargetIsToDraw()){
                    if(firstChar === '#' && secondChar !== '-' || firstChar === '+' && evaluationNumber > this.mistakeEvaluation){
                        this.$emit('userMadeMistake', this.getMistakeData());
                        return true;
                    }
                }
            }
            return false;
        },

        /**
         * Get last move.
         */
        getLastMove(){
            let lastMove = '';
            for(let i = this.moveHistory.length - 1; i >= 0; i--){
                if(this.moveHistory[i].toMove === 'engine'){
                    for(let j = 0; j < this.moveHistory[i].lastMoves.length; j++){
                        let squareNumber = this.moveHistory[i].lastMoves[j];
                        lastMove += this.notation[squareNumber];
                    }
                    break;
                }
            }
            return lastMove;
        },

        /**
         * Get Mistake Data.
         * @returns {Object} {{lastMove: string, bestMove: string}}
         */
        getMistakeData(){
            return {
                bestMove: this.gameIsFinishedExceptRepetition() ? this.bestMove : this.previousBestMove,
                lastMove: this.getLastMove()
            };
        }
    },
}
