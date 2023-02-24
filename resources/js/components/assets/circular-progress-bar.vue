<template>
    <div class="relative p-0 bg-transparent">
        <svg viewBox="0 0 36 36">
            <path d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                  fill="none"
                  stroke="#E5E7EB"
                  :stroke-width="strokeWidth"
                  stroke-dasharray="100, 100"
            />
            <path
                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                fill="none"
                :stroke="getPrimary"
                :stroke-width="strokeWidth"
                :stroke-dasharray="percent + ', 100'"
            />
        </svg>
        <div class="absolute center">
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    name: "circular-progress-bar",
    props: {
        percent: {
          type: Number,
          default: 0
        },
        strokeWidth: {
            type: Number,
            default: 1.5
        }
    },
}
</script>

<style scoped>
.center {
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
}
</style>
