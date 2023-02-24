export default {

    data(){
        return {
            lastTouchedSquare: null,
        }
    },

    mounted(){
        for(let squareNumber = 1; squareNumber <= 64; squareNumber++){
            let square = document.getElementById('square'+squareNumber);
            let self = this;
            square.addEventListener('touchstart', function(e) {
                self.touchStart(e);
            }, {passive: true});
            square.addEventListener('touchend', function(e) {
                self.touchEnd(e);
            });
            square.addEventListener('touchmove', function(e) {
                self.touchMove(e);
            }, {passive: true});

        }
    },

    methods: {
        /**
         * On Touch Start (mouseDown)
         * @param {Event} e
         */
        touchStart(e){
            let touchSquare = this.getTouchSquareFromEvent(e);
            if(touchSquare != null){
                this.mouseDown({
                    ctrlKey: e.ctrlKey,
                    which: 1,
                    clientX: e.changedTouches[0].clientX,
                    clientY: e.changedTouches[0].clientY,
                    touch: true
                }, touchSquare);
            }
        },

        /**
         * On touch move.
         * @param {Event} e
         */
        touchMove(e){
            let touchSquare = this.getTouchSquareFromEvent(e);
            if(touchSquare != null && touchSquare !== this.lastTouchedSquare){
                this.lastTouchedSquare = touchSquare;
                this.mouseEnter(e,touchSquare);
            }
        },

        /**
         * On Touch End (mouseUp)
         * @param {Event} e
         */
        touchEnd(e){
            e.preventDefault();
            let touchSquare = this.getTouchSquareFromEvent(e);
            if(touchSquare != null) this.mouseUp(e, touchSquare);
        },

        /**
         * Gets square number from touch event.
         * @param e
         * @returns {null|number}
         */
        getTouchSquareFromEvent(e){
            let touch = e.changedTouches[0];
            let touchedElement = document.elementFromPoint(touch.clientX, touch.clientY);
            if(touchedElement != null && touchedElement.id != null &&  touchedElement.id.includes('square')){
                return parseInt(touchedElement.id.replace('square', ''));
            }
            return null;
        },
    }
}
