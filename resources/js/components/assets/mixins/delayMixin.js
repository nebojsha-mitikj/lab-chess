export default {
    methods: {
        delay(time) {
            return new Promise(resolve => setTimeout(resolve, time));
        }
    }
}
