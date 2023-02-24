<x-guest-layout>
    <div class="flex flex-col justify-center items-center pt-2 pb-5 sm:pt-2 sm:pb-2 guest-height mt-8">

        <a href="/" class="flex items-center justify-center cursor-pointer no-select no-drag my-3">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="isolation:isolate" viewBox="0 0 800 800" width="26pt" height="26pt"><defs><clipPath id="_clipPath_H2tOL8BEhXhy6DwPmqaxqpb7G4LLHwyx"><rect width="800" height="800"/></clipPath></defs><g clip-path="url(#_clipPath_H2tOL8BEhXhy6DwPmqaxqpb7G4LLHwyx)"><path d=" M 689.483 5.25 L 597.375 5.25 C 582.841 5.25 571.058 17.033 571.058 31.567 L 571.058 110.517 L 492.108 110.517 L 492.108 31.567 C 492.108 17.033 480.327 5.25 465.792 5.25 L 334.208 5.25 C 319.673 5.25 307.892 17.033 307.892 31.567 L 307.892 110.517 L 229.106 110.517 L 229.106 31.567 C 229.106 17.033 217.323 5.25 202.789 5.25 L 110.517 5.25 C 95.983 5.25 84.2 17.033 84.2 31.567 L 84.2 321.05 L 189.467 373.683 C 189.467 453.177 186.933 529.938 167.74 636.85 L 632.26 636.85 C 613.067 529.938 610.533 452.172 610.533 373.683 L 715.8 321.05 L 715.8 31.567 C 715.8 17.033 704.017 5.25 689.483 5.25 Z  M 452.633 478.95 L 347.367 478.95 L 347.367 373.683 C 347.367 344.614 370.932 321.05 400 321.05 C 429.068 321.05 452.633 344.614 452.633 373.683 L 452.633 478.95 Z  M 689.483 689.483 L 110.517 689.483 C 95.983 689.483 84.2 701.266 84.2 715.8 L 84.2 768.433 C 84.2 782.967 95.983 794.75 110.517 794.75 L 689.483 794.75 C 704.017 794.75 715.8 782.967 715.8 768.433 L 715.8 715.8 C 715.8 701.266 704.017 689.483 689.483 689.483 Z " fill="rgb(61,150,242)"/></g></svg>
            <span class="text-5xl text-gray-700 font-semibold ml-0.5">labchess</span>
        </a>

        <div class="w-full max-w-sm p-5 mt-1 border rounded border-gray-200 shadow-md bg-white overflow-hidden guest-card">

            <h1 class="text-center mb-4 font-semibold text-gray-700 guest-card-title">
                Choose a new password
            </h1>

            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <x-input id="email" placeholder="Email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
                </div>

                <!-- Password -->
                <div class="mt-4 relative">
                    <x-input id="password" placeholder="New Password" class="block mt-1 w-full" type="password" name="password" required />
                    <p id="togglePassword" onclick="togglePassword()" class="cursor-pointer text-sm no-select text-gray-400 hover:text-gray-500 inline absolute top-3 right-3">Show</p>
                </div>

                <button class="button main-button mt-4 w-full">
                    Reset Password
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
