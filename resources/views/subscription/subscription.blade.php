<x-app-layout>
    <div class="py-7 sm:py-16 px-3 max-w-7xl mx-auto">
        <subscription
            :subscribed="{{json_encode($subscribed)}}"
            :receipts="{{json_encode($receipts)}}"
            :subscription="{{json_encode($subscription)}}"
            :billing="{{json_encode($billing)}}"
        ></subscription>
    </div>
</x-app-layout>
