<template>
    <div class="w-full absolute top-16 rounded-lg border-2 border-solid border-gray-200 bg-white z-10">
        <div
            style="width: 20px; height: 20px; left: 50%; margin: -11px auto 0 -10px; transform: rotate(45deg);"
            class="border-2 border-solid border-gray-200 absolute bg-white"
        ></div>
        <div
            style="width: 30px; height: 20px; left: 50%; margin-left: -15px"
            class="absolute bg-white"
        ></div>
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "my-card"
}
</script>
