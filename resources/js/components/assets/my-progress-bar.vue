<template>
    <div class="t" :style="'width: 99%; border-radius: 10px; background-color: #EBEEF5; height: '+height+'px'">
        <div class="t" :style="'border-radius: 10px; background-color: '+color+'; height: '+height+'px; width: '+percent+'%; position: relative'">
            <div class="inner-progress" :style="'border-radius: 10px; background-color: white; height: 4px; opacity: 13%; top: 4px; position: absolute'">
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "my-progress-bar",
    props: ['percent', 'height', 'color']
}
</script>

<style scoped>
    .inner-progress {
        width: -moz-calc(100% - 16px);
        width: -webkit-calc(100% - 16px);
        width: -o-calc(100% - 16px);
        width: calc(100% - 16px);
        margin-left: 8px;
    }
    .t {
      transition:width 1s;
      max-width: 100%;
    }
</style>
