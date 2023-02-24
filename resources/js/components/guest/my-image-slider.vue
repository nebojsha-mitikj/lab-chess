<template>
    <div>
        <slider :interval="10000" height="375px">
            <slider-item
                v-for="(i, index) in list"
                :key="index"
                :style="i"
            >
                <p style="line-height: 380px; font-size: 5rem; text-align: center; color: white">Page{{ index + 1 }}</p>
            </slider-item>
        </slider>
    </div>
</template>

<script>

import { Slider, SliderItem } from 'vue-easy-slider';

export default {
    name: "my-image-slider",
    components: {
        Slider,
        SliderItem,
    },
    data() {
        return {
            list: [
                { backgroundColor: '#3D96F2', width: '100%', height: '100%' },
                { backgroundColor: '#3D96F2', width: '100%', height: '100%' },
                { backgroundColor: '#3D96F2', width: '100%', height: '100%' },
            ],
        }
    }
}
</script>
