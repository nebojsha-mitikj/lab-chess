<template>
    <div
        :class="{'check-highlight': square != null}"
        :style="'left: ' + 12.5 * x + '%; top: ' + 12.5 * y + '%;'"
    ></div>
</template>

<script>
export default {
    name: "check-highlight",

    methods: {
        set(square){
            let columns = ['a','b','c','d','e','f','g','h'];
            let rows = ['8','7','6','5','4','3','2','1'];
            this.square = square;
            this.x = columns.indexOf(square.charAt(0));
            this.y = rows.indexOf(square.charAt(1));
        },

        clear(){
            this.square = null;
            this.x = 0;
            this.y = 0;
        }
    },

    data(){
        return {
            square: null,
            x: 0,
            y: 0
        }
    },
}
</script>

<style scoped>
.check-highlight {
    width: 12.5%;
    height: 12.5%;
    background: radial-gradient(red, transparent 80%);
    position: absolute;
}
</style>
