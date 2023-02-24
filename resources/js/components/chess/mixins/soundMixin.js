export default {
    data() {
        return {
            sound: {
                goalReached: new Audio('/sounds/success-trumpet.mp3'),
                success: new Audio('/sounds/success.mp3'),
                move: new Audio('/sounds/m.ogg'),
                check: new Audio('/sounds/m.ogg'),
                capture: new Audio('/sounds/c.ogg'),
                wrong: new Audio('/sounds/error.mp3')
            },
        }
    },

    methods: {
        /**
         * Stop active sound if any and play it from beginning (for sound key).
         * @param key
         */
        makeSound(key){
            this.sound[key].currentTime = 0;
            if(key === 'wrong') this.sound[key].volume = 0.3;
            else this.sound[key].volume = 0.7;
            return this.sound[key].play();
        }
    }
}
