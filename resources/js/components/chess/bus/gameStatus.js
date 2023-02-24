import Vue from "vue";

export const gameStatus = Vue.observable({
    /**
     * Possible example values for evaluation:
     *  0
     *  -12
     *  #-3
     *  +12
     *  #3
     *  -
     */
    evaluation: 0,
    moveCounter: 0,
    rotate: false,
    stockFishLoaded: false,
    engineThinking: false
});
