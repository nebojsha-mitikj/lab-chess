<template>
    <div class="mx-auto my-2">
        <div class="text-gray-600 border-gray-200 border mx-auto rounded text-sm sm:text-base cst-width">
            <div class="grid grid-cols-6 divide-x">
                <button v-for="btn in buttonData" @click="$emit(btn[0])"
                        class="col-span-3 py-2 w-full mx-auto cursor-pointer hover:text-primary hover:bg-gray-50">
                    <span :class="'fa '+btn[1]"></span>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "chess-actions",

    props: ['positionFinished'],

    computed: {
        buttonData() {
            return [
                ['previousMove', 'fa-step-backward'],
                ['nextMove', 'fa-step-forward']
            ];
        }
    }
}
</script>

<style scoped>
.cst-width {
    max-width: 10rem;
}
</style>
