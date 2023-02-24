<x-guest-layout>
    <div class="py-7 sm:py-16 px-3 max-w-7xl mx-auto text-gray-800">
        <div class="flex justify-center align-center max-w-4xl mx-auto mb-3 sm:mb-0 mt-3 sm:mt-3">
            <div class="w-full max-w-md py-3 px-6 mb-4">
                <div class="text-center">
                    <h1 class="text-3xl mt-4 text-gray-800 font-bold">
                        <span >Free trial for 14 days.</span> <br>$9.50<span class="px-1">/</span>month after that.<br> Cancel anytime.
                    </h1>
                </div>
                <div class="pb-2 mt-2 px-14 mx-auto">
                    <ol class="list-decimal">
                        <li class="text-primary-dark font-bold text-lg">Endgame Courses</li>
                        <ul class="list-disc pl-4">
                            <li>Pawn endgames</li>
                            <li>Bishop endgames</li>
                            <li>Knight endgames</li>
                            <li>Minor pieces endgames</li>
                            <li>Rook endgames</li>
                            <li>Rook & pieces endgames</li>
                            <li>Queen endgames</li>
                        </ul>
                        <li class="mt-1 text-primary-dark font-bold text-lg">Endgame Trainer</li>
                        <ul class="list-disc pl-4">
                            <li>2000+ endgame positions</li>
                            <li>Unlimited engine analysis</li>
                            <li>Mistake detection</li>
                        </ul>
                    </ol>
                </div>
                <div class="w-full text-center mb-6">
                    <a
                        href="/register"
                        class="mt-1 inline-flex duration-200 justify-center transition hover:shadow-none border-primary align-center bg-primary no-select border-4 rounded text-white shadow-lg shadow-primary font-bold inline-block w-full py-4 text-base sm:text-lg"
                    >
                        Start your free trial today!
                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 17px; margin-left: 8px" fill="#79BBFF" viewBox="0 0 24 24"><path d="M15.54,11.29,9.88,5.64a1,1,0,0,0-1.42,0,1,1,0,0,0,0,1.41l4.95,5L8.46,17a1,1,0,0,0,0,1.41,1,1,0,0,0,.71.3,1,1,0,0,0,.71-.3l5.66-5.65A1,1,0,0,0,15.54,11.29Z"/></svg>
                    </a>
                </div>
                <p class="mt-3 text-sm">
                    Don't worry - your first charge won't be until {{ \Carbon\Carbon::now()->addDays(14)->format("Y-m-d") }}, and it's only $9.50. Plus, you can cancel with just 2-clicks and you won't get charged. When you join labchess.com, you're not only getting access to the best endgame training available - you're also investing in the future of our platform. Your support will help us continue to grow and improve, so join us now and make a difference!
                </p>
            </div>
        </div>
    </div>

</x-guest-layout>
