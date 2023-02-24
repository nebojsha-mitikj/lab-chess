export default {
    methods: {
        /**
         * Calculate percentage and return the whole number or if any show only 2 decimals.
         * @param number
         * @param total
         * @returns {number|string}
         */
        formatPercentage(number,total){
            return this.wholeNumberOrWithTwoDecimals(number * 100 / total)
        },

        /**
         * Whole number or if any show only 2 decimals.
         * @param num
         * @returns {*|string}
         */
        wholeNumberOrWithTwoDecimals(num){
            return num % 1 === 0 ? num : num.toFixed(2);
        }
    }
}
