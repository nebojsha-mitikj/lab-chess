<template>
    <div class="w-full">
        <div class="
            border-t border-gray-200 py-2 lg:py-5 lg:py-8 grid-cols-12 border-b lg:border-b-0 border-gray-200
            grid text-gray-600 bg-white px-8 lg:px-0
            ">
            <button @click="$emit('firstMove')" class="col-span-3 text-base mx-auto lg:text-xl cursor-pointer">
                <span class="fa fa-fast-backward"></span>
            </button>
            <button @click="$emit('previousMove')" class="col-span-3 text-base mx-auto lg:text-xl cursor-pointer">
                <span class="fa fa-step-backward"></span>
            </button>
            <button @click="$emit('nextMove')" class="col-span-3 mx-auto text-base lg:text-xl cursor-pointer">
                <i class="fa fa-step-forward"></i>
            </button>
            <button @click="$emit('lastMove')" class="col-span-3 mx-auto text-base lg:text-xl cursor-pointer">
                <i class="fa fa-fast-forward"></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: "position-helper-navigation"
}
</script>
