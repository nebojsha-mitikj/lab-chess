export default {

    data(){
        return {
            squareWidth: 0,
            notation: {
                1: "a8", 2: "b8", 3: "c8", 4: "d8", 5: "e8",
                6: "f8", 7: "g8", 8: "h8", 9: "a7", 10: "b7",
                11: "c7", 12: "d7", 13: "e7", 14: "f7", 15: "g7",
                16: "h7", 17: "a6", 18: "b6", 19: "c6", 20: "d6",
                21: "e6", 22: "f6", 23: "g6", 24: "h6", 25: "a5",
                26: "b5", 27: "c5", 28: "d5", 29: "e5", 30: "f5",
                31: "g5", 32: "h5", 33: "a4", 34: "b4", 35: "c4",
                36: "d4", 37: "e4", 38: "f4", 39: "g4", 40: "h4",
                41: "a3", 42: "b3", 43: "c3", 44: "d3", 45: "e3",
                46: "f3", 47: "g3", 48: "h3", 49: "a2", 50: "b2",
                51: "c2", 52: "d2", 53: "e2", 54: "f2", 55: "g2",
                56: "h2", 57: "a1", 58: "b1", 59: "c1", 60: "d1",
                61: "e1", 62: "f1", 63: "g1", 64: "h1"
            },
            number: {
                a1: 57, a2: 49, a3: 41, a4: 33, a5: 25,
                a6: 17, a7: 9, a8: 1, b1: 58, b2: 50,
                b3: 42, b4: 34, b5: 26, b6: 18, b7: 10,
                b8: 2, c1: 59, c2: 51, c3: 43, c4: 35,
                c5: 27, c6: 19, c7: 11, c8: 3, d1: 60,
                d2: 52, d3: 44, d4: 36, d5: 28, d6: 20,
                d7: 12, d8: 4, e1: 61, e2: 53, e3: 45,
                e4: 37, e5: 29, e6: 21, e7: 13, e8: 5,
                f1: 62, f2: 54, f3: 46, f4: 38, f5: 30,
                f6: 22, f7: 14, f8: 6, g1: 63, g2: 55,
                g3: 47, g4: 39, g5: 31, g6: 23, g7: 15,
                g8: 7, h1: 64, h2: 56, h3: 48, h4: 40,
                h5: 32, h6: 24, h7: 16, h8: 8
            }
        }
    },

    methods: {
        /**
         * Get <div> element by square number.
         * @param {Integer} squareNumber
         * @returns Element|null
         */
        getSquareElement(squareNumber){
            return document.getElementById('square'+squareNumber);
        },

        /**
         * Get square notation from move.
         * Nc3 => c3, Bxc4 = c4, Qxg2# => g2,
         * @param {String} move
         * @returns String
         */
        getSquareNotationFromMove(move){
            if(move.includes('=')) move = move.substring(0, move.length-2);
            if(move === 'O-O') return this.initialSquareData.number === 61 ? this.notation[63] : this.notation[7];
            if(move === 'O-O-O') return this.initialSquareData.number === 61 ? this.notation[59] : this.notation[3];
            ['+','#'].forEach(e => move = move.replaceAll(e,''));
            return move.slice(-2);
        },
    }
}
