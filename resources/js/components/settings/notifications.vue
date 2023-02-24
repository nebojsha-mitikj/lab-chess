<template>
    <div>
        <h1 class="header">Notifications</h1>

        <p class="mt-5">Send me email notifications when:</p>

        <div class="pl-4 mt-3">
            <!--
            <div class="mt-5 block grid grid-cols-12 md:block">
                <toggle-button
                    class="ml-1 mt-1 col-span-2"
                    v-model="productUpdate"
                    @change="updateNotification('product_update',productUpdate)"
                    :color="getPrimaryLight"
                />
                <span class="ml-3 col-span-10 pl-2 sm:pl-0">There is a product update or learning tip</span>
            </div>
            -->

            <div class="mt-5 block grid grid-cols-12 md:block">
                <toggle-button
                    class="ml-1 mt-1 col-span-2"
                    v-model="newFollow"
                    @change="updateNotification('new_follow',newFollow)"
                    :color="getPrimaryLight"
                />
                <span class="ml-3 col-span-10 pl-2 sm:pl-0">Somebody follows me</span>
            </div>

            <!--
            <div class="mt-5 block grid grid-cols-12 md:block">
                <toggle-button
                    class="ml-1 mt-1 col-span-2"
                    v-model="weeklyProgress"
                    @change="updateNotification('weekly_report',weeklyProgress)"
                    :color="getPrimaryLight"
                />
                <span class="ml-3 col-span-10 pl-2 sm:pl-0">Weekly progress report</span>
            </div>
            -->

        </div>
    </div>
</template>

<script>

export default {
    name: "notifications",

    props: ['userNotification'],


    data(){
        return {
            productUpdate: true,
            newFollow: true,
            weeklyProgress: true
        }
    },

    created(){
        this.productUpdate = Boolean(this.userNotification.product_update);
        this.newFollow = Boolean(this.userNotification.new_follow);
        this.weeklyProgress = Boolean(this.userNotification.weekly_report);
    },

    methods: {
        /**
         * Update clicked notification.
         * @param field
         * @param value
         */
        updateNotification(field,value){
            axios.put('/api/settings/updateNotification', {
               'field': field,
               'value': value
            });
        }
    }
}
</script>
