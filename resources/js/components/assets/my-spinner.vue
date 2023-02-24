<template>
    <div class="lds-dual-ring" :style="{'--borderColor': color, '--size': size}"></div>
</template>

<script>
export default {
    name: "my-spinner",
    props: ['color','size']
}
</script>

<style scoped>
.lds-dual-ring {
    display: inline-block;
    width: var(--size);
    height: var(--size);
}
.lds-dual-ring:after {
    content: " ";
    display: block;
    width: var(--size);
    height: var(--size);
    border-radius: 50%;
    border-width: 3px;
    border-style: solid;
    border-color: var(--borderColor) transparent var(--borderColor) transparent;
    animation: lds-dual-ring 1.2s linear infinite;
}
@keyframes lds-dual-ring {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
</style>
