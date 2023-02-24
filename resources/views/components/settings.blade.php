<x-app-layout>
    <div class="py-3 sm:py-16 px-3 max-w-5xl mx-auto">
        <div class="grid grid-cols-12 divide-none sm:divide-solid sm:divide-x divide-gray-300">
            <!-- Vertical Navigation -->
            <div class="col-span-12 text-center sm:text-left sm:col-span-3 xl:col-span-2">

                <div class="grid grid-cols-12 gap-x-2">
                    <div
                        class="col-span-6 sm:col-span-12 inline-block hover:underline py-2 border sm:border-none border-gray-200 rounded-lg mb-2 {{Route::is('settings.account') ? 'text-primary underline' : ''}}">
                        <a href="{{ route('settings.account') }}">
                            <uil-user-circle class="inline" style="margin-top: -4px"></uil-user-circle>
                            Account
                        </a>
                    </div>
                    <div
                        class="col-span-6 sm:col-span-12 inline-block hover:underline py-2 border sm:border-none border-gray-200 rounded-lg mb-2 {{Route::is('settings.profile') ? 'text-primary underline' : ''}}">
                        <a href="{{ route('settings.profile') }}">
                            <uil-user class="inline" style="margin-top: -4px"></uil-user>
                            Profile
                        </a>
                    </div>
                    <div
                        class="col-span-6 sm:col-span-12 inline-block hover:underline py-2 border sm:border-none border-gray-200 rounded-lg mb-2 {{Route::is('settings.password') ? 'text-primary underline': ''}}">
                        <a href="{{ route('settings.password') }}">
                            <uil-padlock class="inline" style="margin-top: -4px"></uil-padlock>
                            Password
                        </a>
                    </div>
                    <div
                        class="col-span-6 sm:col-span-12 inline-block hover:underline py-2 border sm:border-none border-gray-200 rounded-lg mb-2 {{Route::is('settings.display') ? 'text-primary underline' : ''}}">
                        <a href="{{ route('settings.display') }}">
                            <uil-desktop class="inline" style="margin-top: -4px"></uil-desktop>
                            Board Display
                        </a>
                    </div>

                    <div
                        class="col-span-6 sm:col-span-12 hover:underline py-2 border sm:border-none border-gray-200 rounded-lg mb-2 {{Route::is('settings.daily-goal') ? 'text-primary underline' : ''}}">
                        <a href="{{ route('settings.daily-goal') }}">
                            <uil-basketball class="inline" style="margin-top: -4px"></uil-basketball>
                            Daily Goal
                        </a>
                    </div>

                    <div
                        class="col-span-6 sm:col-span-12 inline-block hover:underline py-2 border sm:border-none border-gray-200 rounded-lg mb-2 {{Route::is('settings.notifications') ? 'text-primary underline' : ''}}">
                        <a href="{{ route('settings.notifications') }}">
                            <uil-bell class="inline" style="margin-top: -4px"></uil-bell>
                            Notifications
                        </a>
                    </div>

                    <div
                        class="col-span-6 sm:col-span-12 inline-block hover:underline py-2 border sm:border-none mb-2 border-gray-200 rounded-lg {{Route::is('settings.privacy') ? 'text-primary underline' : ''}}">
                        <a href="{{ route('settings.privacy') }}">
                            <uil-keyhole-square class="inline" style="margin-top: -4px"></uil-keyhole-square>
                            Privacy
                        </a>
                    </div>
                </div>

            </div>

            <!-- Component slot -->
            <div class="col-span-12 sm:col-span-9 xl:col-span-10 px-0 sm:px-8 pb-5 mt-5 sm:mt-0">
                <hr class="d-block sm:hidden mb-6">
                {{ $slot }}
            </div>

        </div>
    </div>
</x-app-layout>
