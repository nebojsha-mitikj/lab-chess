export default {
    data() {
        return {
            lastSquareClickedWithRightClick: 1,
            rightClickDown: false,
            ctrlKeyPressed: false,
        }
    },
    methods: {
        /**
         * When mouse enters square.
         * @param {Event} e
         * @param {Integer} squareNum
         */
        mouseEnter(e, squareNum) {
            this.toSquareNumber = squareNum;
            if (this.rightClickDown && (squareNum !== this.lastSquareClickedWithRightClick)) {
                if (this.$refs.arrows.getTempArrowAdded()) {
                    this.$refs.arrows.setSecondSquareForLastArrow(squareNum);
                } else if(this.lastSquareClickedWithRightClick != null) {
                    this.$refs.arrows.pushArrow({
                        id: this.$uuid.v1(),
                        firstSquare: this.lastSquareClickedWithRightClick,
                        secondSquare: squareNum,
                        color: this.ctrlKeyPressed ? this.getOrange : this.getYellow
                    });
                    this.$refs.arrows.setTempArrowAdded(true);
                }
            }
        },

        /**
         * Checks if left click is clicked.
         * @param which
         * @returns {boolean}
         */
        isLeftClick(which) {
            return which === 1;
        },

        /**
         * Checks if right click is clicked.
         * @param which
         * @returns {boolean}
         */
        isRightClick(which) {
            return which === 3;
        },

        /**
         * On Mouse Down.
         * @param {Event} e
         * @param {Integer} num
         */
        mouseDown(e, num) {
            if (num !== null){
                this.toSquareNumber = num;
            }

            this.ctrlKeyPressed = e.ctrlKey;
            this.$refs.arrows.setTempArrowAdded(false);
            this.rightClickDown = false;

            this.deletePreMoveIfNeeded();

            if (this.isRightClick(e.which)) {
                if(this.exclude.includes('right-click')) return;
                this.lastSquareClickedWithRightClick = num;
                this.rightClickDown = true;

                if(this.userAnalysisIsActive || this.movePointerPointsToLastMove()){
                    if(this.documentLevelPiece !== null){
                        this.lastSquareClickedWithRightClick = null;
                    }
                    this.stopPieceMovement();
                }

            }

            if (this.isLeftClick(e.which)) {
                if(this.exclude.includes('left-click') && this.exclude.includes('right-click')) return;
                this.clearArrows();
                this.clearHighlights();
                if(this.exclude.includes('left-click') || (this.positionIsFinishedProp && !this.userAnalysisIsActive)) return;
                if (this.squarePieceCannotBeMoved(num)) return;
                if (this.isGameFinishedEvenIfAnalyzing()) return;
                if (this.gameIsActiveAndUserLooksMoveHistory()) return;

                if (!this.userAnalysisIsActive && this.isPreMove()) {
                    let preMoveAdded = false;
                    if (this.initialSquareData !== null) preMoveAdded = this.addPreMove();
                    if (this.squarePieceColorSameAsPlayerColor(num) && !preMoveAdded) return this.preMoveDragStart(num, e);
                }

                if (
                    (!this.squareHasPiece(num) || (
                        this.initialSquareData != null && !this.piecesHaveSameColor(this.getPieceFromSquare(num), this.initialSquareData.piece)
                    )) && this.initialSquareData != null && this.fromSquareDifferentFromToSquare()
                ) {
                    this.makeMove();
                    this.clearDragVariables();
                    this.clearDocumentEvents();
                    return;
                }

                if (this.squareHasPiece(num)) {
                    this.squareWidth = this.getSquareElement(num).clientWidth;
                    if (!this.userAnalysisIsActive && (!this.isPlayerTurn() || !this.squarePieceColorSameAsPlayerColor(num))) return;
                    this.initialSquareData = {number: num, piece: this.getPieceFromSquare(num)};
                    this.highlightAvailableAndCaptureSquaresForSquare(num);
                    this.createDocumentLevelPiece(e);
                    this.removePieceFromSquare(num);
                    this.setPieceDraggingEvents();
                }
            }
        },

        /**
         * Mouse Up Event on board.
         * @param {Event} e
         * @param {Integer} clickedSquare
         */
        mouseUp(e, clickedSquare) {
            this.toSquareNumber = clickedSquare;
            this.rightClickDown = false;
            this.$refs.arrows.setTempArrowAdded(false);

            if (this.isRightClick(e.which)) {
                if (clickedSquare === this.lastSquareClickedWithRightClick) {
                    for (let i = 0; i < this.highlightedSquares.length; i++) {
                        if (this.highlightedSquares[i].square === clickedSquare) {
                            let copy = JSON.parse(JSON.stringify(this.highlightedSquares[i]));
                            this.highlightedSquares.splice(i, 1);
                            if (copy.color === (this.ctrlKeyPressed ? this.getOrange : this.getYellow)) return;
                        }
                    }
                    this.highlightedSquares.push({
                        square: clickedSquare,
                        color: this.ctrlKeyPressed ? this.getOrange : this.getYellow
                    });
                } else {
                    let item = {firstSquare: this.lastSquareClickedWithRightClick, secondSquare: clickedSquare};
                    let indexes = this.$refs.arrows.indexesOfArrowInArrows(item);
                    if (indexes.length > 1) {
                        let loopCount = null;
                        if (this.$refs.arrows.getArrow(indexes[0]).color === this.$refs.arrows.getArrow(indexes[1]).color) {
                            loopCount = 2;
                        } else {
                            loopCount = 1;
                        }
                        for (let i = 0; i < loopCount; i++) {
                            let index = this.$refs.arrows.indexOfArrowInArrows(item);
                            if (index !== -1) this.$refs.arrows.deleteArrow(index);
                        }
                    }
                }
            }
        }
    }
}
