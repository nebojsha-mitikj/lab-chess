<template>
    <div class="mt-4">
        <button @click="saveChanges" :disabled="disabled" :class="{'sub-button-disabled': disabled}" class="mt-5 float-none sm:float-right button sub-button">
            <my-spinner class="inline float-left mr-1" color="#9CA3AF" size="16px" style="margin-top: 3px" v-if="loading"></my-spinner>
            <uil-save class="inline" style="margin-top: -2px" v-else></uil-save>
            Save Changes
        </button>
    </div>
</template>

<script>
import { UilSave } from '@iconscout/vue-unicons';
import MySpinner from "./my-spinner";

export default {
    name: "save-changes-button",
    components: {MySpinner, UilSave},
    data(){
        return {
            disabled: true,
            loading: false,
        }
    },
    methods: {
        /**
         * Load, disable and emit.
         */
        saveChanges(){
            this.loading = true;
            this.disabled = true;
            this.$emit('saveChanges');
        }
    }
}
</script>

<style scoped>
button:disabled {
    cursor: auto;
}
</style>
