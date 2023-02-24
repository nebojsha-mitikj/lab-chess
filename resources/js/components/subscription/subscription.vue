<template>
    <div>
        <div class="flex justify-center align-center max-w-3xl mx-auto mb-3 sm:mb-0 mt-3 sm:mt-3">
            <div class="w-full py-3 px-1 sm:px-6 mb-4">

                <h1 class="text-center header flex justify-center align-center text-gray-700">
                    Subscription
                </h1>

                <p class="font-bold text-gray-700 mt-8 mb-2 text-sm sm:text-base">Subscription:</p>
                <div class="grid grid-cols-12 divide-x-2 border border-gray-200 mb-8 text-sm sm:text-base text-center text-gray-700">
                    <div class="col-span-4 px-2 py-3">
                        <p class="font-bold">Status:</p>
                        <p>{{ subscription.status }}</p>
                    </div>
                    <div class="col-span-4 px-2 py-3">
                        <p class="font-bold">Per Month:</p>
                        <p>{{ subscription.amount }}</p>
                    </div>
                    <div class="col-span-4 px-2 py-3">
                        <p class="font-bold">Next Billing:</p>
                        <p>{{ subscription.next }}</p>
                    </div>
                </div>

                <template v-if="billing != null && canceled.length === 0">
                    <p class="font-bold text-gray-700 mt-8 sm:mt-12 text-sm sm:text-base mb-2">Billing Information:</p>
                    <div class="grid grid-cols-12 divide-y-2 border border-gray-200 mb-4 text-sm sm:text-base">
                        <div class="col-span-12">
                            <div class="grid grid-cols-12 divide-x-2">
                                <div class="col-span-4 pr-4 text-right py-1 text-gray-700">
                                    Email
                                </div>
                                <div class="col-span-8 pl-4 py-1 text-gray-700">
                                    {{ billing.email }}
                                </div>
                            </div>
                        </div>
                        <div class="col-span-12">
                            <div class="grid grid-cols-12 divide-x-2">
                                <div class="col-span-4 pr-4 text-right py-1 text-gray-700">
                                    Method
                                </div>
                                <div class="col-span-8 pl-4 py-1 text-gray-700">
                                    {{ billing.method }}
                                </div>
                            </div>
                        </div>
                        <template v-if="billing.method === 'card'">
                            <div class="col-span-12">
                                <div class="grid grid-cols-12 divide-x-2">
                                    <div class="col-span-4 pr-4 text-right py-1 text-gray-700">
                                        Brand
                                    </div>
                                    <div class="col-span-8 pl-4 py-1 text-gray-700">
                                        {{ billing.cardBrand }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12">
                                <div class="grid grid-cols-12 divide-x-2">
                                    <div class="col-span-4 pr-4 text-right py-1 text-gray-700">
                                        Number
                                    </div>
                                    <div class="col-span-8 pl-4 py-1 text-gray-700">
                                        {{ billing.lastFour }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-span-12">
                                <div class="grid grid-cols-12 divide-x-2">
                                    <div class="col-span-4 pr-4 text-right py-1 text-gray-700">
                                        Expiration
                                    </div>
                                    <div class="col-span-8 pl-4 py-1 text-gray-700">
                                        {{ billing.expirationData }}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <p class="my-4 sm:my-6" v-if="subscription != null && subscription.edit != null">
                        <a class="text-primary cursor-pointer no-select text-sm sm:text-base hover:underline" :href="subscription.edit">
                            Update Payment Method
                        </a>
                    </p>
                </template>


                <p class="font-bold text-gray-700 mt-8 sm:mt-12 text-sm sm:text-base mb-2">Billing History:</p>
                <div v-if="receipts.length === 0" class="border border-gray-200 mb-4 text-sm sm:text-base flex justify-center align-center p-6 text-gray-700">
                    /
                </div>
                <div v-else class="border border-gray-200 mb-4 text-sm sm:text-base">
                    <div class="grid grid-cols-12 text-center text-gray-700 border-b border-gray-200">
                        <div class="col-span-4">
                            Number
                        </div>
                        <div class="col-span-4">
                            Date
                        </div>
                        <div class="col-span-4">
                            Download
                        </div>
                    </div>
                    <div class="grid grid-cols-12 text-center text-gray-700 px-1">
                        <template v-for="receipt in receipts">
                            <div class="col-span-4 py-1">
                                {{ receipt.order_id }}
                            </div>
                            <div class="col-span-4 py-1">
                                {{ receipt.paid_at_formatted }}
                            </div>
                            <div class="col-span-4 py-1">
                                <a class="text-primary hover:underline" :href="receipt.receipt_url" target="_blank">Link</a>
                            </div>
                        </template>
                    </div>
                </div>

                <div class="my-4 sm:my-6 block" v-if="subscribed">
                    <template v-if="canceled.length === 0">
                        <span
                            v-if="!cancelRequestIsActive"
                            class="text-primary cursor-pointer no-select text-sm sm:text-base hover:underline"
                            @click="cancelSubscription"
                        >
                            Cancel Subscription
                        </span>
                        <my-spinner color="#9CA3AF" size="16px" v-else></my-spinner>
                    </template>

                    <p v-else v-text="canceled" class="mt-6 sm:mt-10 text-gray-700"></p>
                    <p v-if="errorMessage.length > 0" v-text="errorMessage" class="text-red-500 mt-6 sm:mt-10"></p>
                </div>
                <p v-if="!subscribed || canceled.length > 0" class="mt-8">
                    <a
                        class="text-primary cursor-pointer hover:underline text-base sm:text-lg inline inline-flex align-center"
                        href="/premium"
                    >
                        <img class="w-6 mr-2" src="/images/icons/diamond.png"> UPGRADE ACCOUNT
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "subscription",

    props: ['subscribed','subscription', 'receipts', 'billing'],

    created() {
        if(this.subscription != null && this.subscription.message != null) this.canceled = this.subscription.message;
    },

    data(){
        return {
            cancelRequestIsActive: false,
            canceled: '',
            errorMessage: ''
        }
    },

    methods: {
        /**
         * Cancel User Subscription
         */
        cancelSubscription(){
            if (this.cancelRequestIsActive) return;
            this.errorMessage = '';
            let question = "Your account will remain premium until "+ this.subscription.next +". \nClick OK to confirm the cancellation.";
            if (confirm(question)) {
                this.cancelRequestIsActive = true;
                axios.post('/api/subscription/cancel', {}).then(res => {
                    this.canceled = res.data.message;
                    this.subscription.status = "Active until " + this.subscription.next;
                    this.subscription.next = "/"
                    this.subscription.amount = "/"
                }).catch(err => {
                    this.errorMessage = 'Unexpected error. Please try again or contact support@labchess.com to resolve the issue.';
                }).finally(() => {
                    this.cancelRequestIsActive = false;
                });
            }
        }
    }
}
</script>
