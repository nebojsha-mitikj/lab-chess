<template>
    <div class="rounded-lg w-full my-4 px-4 py-3 relative bg-orange p-4">
        <svg class="absolute right-4 t-0" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
             style="isolation:isolate" viewBox="0 0 286.054 286.054" width="18pt" height="18pt">
            <defs>
                <clipPath id="_clipPath_6eRgI5WyvN9i8jxfUAHSwipsGnMm6KsI">
                    <rect width="286.054" height="286.054"/>
                </clipPath>
            </defs>
            <g clip-path="url(#_clipPath_6eRgI5WyvN9i8jxfUAHSwipsGnMm6KsI)">
                <path
                    d=" M 143.027 0 C 64.04 0 0 64.04 0 143.027 C 0 222.023 64.04 286.054 143.027 286.054 C 222.023 286.054 286.054 222.032 286.054 143.027 C 286.054 64.04 222.022 0 143.027 0 Z  M 143.027 259.236 C 78.844 259.236 26.818 207.21 26.818 143.027 C 26.818 78.844 78.844 26.818 143.027 26.818 C 207.21 26.818 259.236 78.844 259.236 143.027 C 259.236 207.21 207.21 259.236 143.027 259.236 Z  M 143.036 62.726 C 132.792 62.726 125.041 68.072 125.041 76.707 L 125.041 155.908 C 125.041 164.552 132.791 169.88 143.036 169.88 C 153.03 169.88 161.031 164.329 161.031 155.908 L 161.031 76.707 C 161.03 68.277 153.03 62.726 143.036 62.726 Z  M 143.036 187.723 C 133.194 187.723 125.184 195.733 125.184 205.583 C 125.184 215.416 133.194 223.426 143.036 223.426 C 152.878 223.426 160.879 215.416 160.879 205.583 C 160.878 195.732 152.878 187.723 143.036 187.723 Z "
                    fill="#c2410c"/>
            </g>
        </svg>
        <p class="font-bold mb-0.5 text-orange-dark">Heads Up!</p>
        <slot></slot>
    </div>
</template>

<script>
export default {
    name: "my-warning"
}
</script>
