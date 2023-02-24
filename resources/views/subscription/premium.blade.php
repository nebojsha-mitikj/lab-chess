<x-app-layout>
    <div class="py-7 sm:py-16 px-3 max-w-7xl mx-auto">
            <div class="flex justify-center align-center max-w-4xl mx-auto mb-3 sm:mb-0 mt-3 sm:mt-3">
                <div class="w-full max-w-md py-3 px-6 mb-4">
                    <div class="text-center">
                        <img class="mx-auto w-14 text-center" src="{{\Illuminate\Support\Facades\URL::asset('images/icons/diamond.png')}}">
                        <h1 class="text-2xl mb-1 font-bold" style="color: #FFAA64;">
                            PREMIUM
                        </h1>
                        <h1 class="text-2xl mt-4 font-bold text-gray-900">
                            @if($subscriptionStatus == "free")
                                FREE for 14-days, then $9.50<span class="mx-0.5">/</span>month. Cancel anytime.
                            @else
                                $9.50<span class="mx-0.5">/</span>month, cancel anytime.
                            @endif
                        </h1>
                    </div>
                    <div class="pb-2 mt-2 px-5 mx-auto">
                        <ol class="list-decimal text-gray-800">
                            <li class="font-bold">Endgame Courses</li>
                            <ul class="list-disc">
                                <li>Pawn endgames</li>
                                <li>Bishop endgames</li>
                                <li>Knight endgames</li>
                                <li>Minor pieces endgames</li>
                                <li>Rook endgames</li>
                                <li>Rook & pieces endgames</li>
                                <li>Queen endgames</li>
                            </ul>
                            <li class="mt-1 font-bold font-semibold">Endgame Trainer</li>
                            <ul class="list-disc">
                                <li>2000+ endgame positions</li>
                                <li>Unlimited engine analysis</li>
                                <li>Mistake detection</li>
                            </ul>
                        </ol>
                    </div>
                    <x-paddle-button
                        url="{{$payLink}}"
                        data-theme="none"
                        class="bg-primary text-white hover:shadow-none shadow-lg w-full font-bold text-center block py-3 rounded text-base sm:text-lg"
                    >
                        @if($subscriptionStatus == "free")
                            START 14-DAY FREE TRIAL
                        @else
                            SUBSCRIBE
                        @endif
                    </x-paddle-button>
                    <p class="text-gray-800 text-sm mt-3">
                        @if($subscriptionStatus == "free")
                                Your first charge will be on {{ \Carbon\Carbon::now()->addDays(14)->format("Y-m-d") }} for $9.50. You can cancel with 2-clicks anytime and you will not be charged. By clicking “Start 14-day free trial”, you accept our
                            <a href="/privacy-policy" target="_blank" class="text-primary hover:underline">privacy policy</a> &
                            <a href="/refund-policy" target="_blank" class="text-primary hover:underline">refund policy</a>.</p>
                        @else
                            @if($subscriptionStatus == "grace" && $subscriptionEnd != null)
                            Your first charge will be on {{$subscriptionEnd}} for $9.50. You can cancel with 2-clicks anytime and you will not be charged.
                            @endif
                            By clicking “Subscribe”, you accept our
                            <a href="/privacy-policy" target="_blank" class="text-primary hover:underline">privacy policy</a> &
                            <a href="/refund-policy" target="_blank" class="text-primary hover:underline">refund policy</a>.</p>
                        @endif

                </div>
            </div>
    </div>
</x-app-layout>
