<template>
    <div class="w-full h-full absolute" :class="{'rotate': rotate}">
        <span
            v-for="(rank,index) in getRows"
            :key="'A'+index"
            v-text="rank" id="row"
            class="absolute no-select z-10 text-sm md:text-base"
            :style="'top: '+index*12.5+'%; color: '+ ((index % 2 === 0) ? darkColor : lightColor)"
        ></span>
        <span
            v-for="(file,index) in getColumns"
            :key="'B'+index"
            v-text="file"
            id="col"
            class="absolute no-select text-right bottom-0 text-sm md:text-base"
            :style="'left: '+index*12.5+'%; color: '+ ((index % 2 !== 0) ? darkColor : lightColor)"
        ></span>
    </div>
</template>

<script>
export default {
    name: "coordinates",
    props: ['lightColor', 'darkColor', 'rotate'],
    computed: {
        /**
         * Get columns.
         */
        getColumns(){
            return this.rotate ? ['a','b','c','d','e','f','g','h'].reverse() : ['a','b','c','d','e','f','g','h'];
        },

        /**
         * Get rows.
         */
        getRows(){
            return this.rotate ? [8,7,6,5,4,3,2,1].reverse() : [8,7,6,5,4,3,2,1];
        }
    }
}
</script>

<style scoped>
#row {
    left: 0;
    padding-left: 2px;
}
#col {
    bottom: 0;
    padding-right: 2px;
    width: 12.5%;
}
</style>
