<div class="grid grid-cols-12 mt-4 container">
    <div class="col-span-12 sm:col-span-6 flex items-center mr-auto ml-auto sm:mr-auto sm:ml-0">
        <a href="/" class="py-1 flex justify-center align-center cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="isolation:isolate; margin-top: 9px" viewBox="0 0 800 800" width="18pt" height="18pt"><defs><clipPath id="_clipPath_H2tOL8BEhXhy6DwPmqaxqpb7G4LLHwyx"><rect width="800" height="800"/></clipPath></defs><g clip-path="url(#_clipPath_H2tOL8BEhXhy6DwPmqaxqpb7G4LLHwyx)"><path d=" M 689.483 5.25 L 597.375 5.25 C 582.841 5.25 571.058 17.033 571.058 31.567 L 571.058 110.517 L 492.108 110.517 L 492.108 31.567 C 492.108 17.033 480.327 5.25 465.792 5.25 L 334.208 5.25 C 319.673 5.25 307.892 17.033 307.892 31.567 L 307.892 110.517 L 229.106 110.517 L 229.106 31.567 C 229.106 17.033 217.323 5.25 202.789 5.25 L 110.517 5.25 C 95.983 5.25 84.2 17.033 84.2 31.567 L 84.2 321.05 L 189.467 373.683 C 189.467 453.177 186.933 529.938 167.74 636.85 L 632.26 636.85 C 613.067 529.938 610.533 452.172 610.533 373.683 L 715.8 321.05 L 715.8 31.567 C 715.8 17.033 704.017 5.25 689.483 5.25 Z  M 452.633 478.95 L 347.367 478.95 L 347.367 373.683 C 347.367 344.614 370.932 321.05 400 321.05 C 429.068 321.05 452.633 344.614 452.633 373.683 L 452.633 478.95 Z  M 689.483 689.483 L 110.517 689.483 C 95.983 689.483 84.2 701.266 84.2 715.8 L 84.2 768.433 C 84.2 782.967 95.983 794.75 110.517 794.75 L 689.483 794.75 C 704.017 794.75 715.8 782.967 715.8 768.433 L 715.8 715.8 C 715.8 701.266 704.017 689.483 689.483 689.483 Z " fill="rgb(61,150,242)"/></g></svg>
            <p class="text-3xl text-gray-800 ml-0.5 mt-1 font-semibold ml-0.5">labchess</p>
        </a>
    </div>
    <div class="col-span-12 sm:col-span-6 flex items-center mt-2 mr-auto ml-auto sm:mr-0 sm:ml-auto">
        @guest
            <a href="/pricing" class="hover:underline mx-3">Pricing</a>
            <a href="/login" class="hover:underline mx-3">Log in</a>
            <a href="/register" class="hover:underline mx-3">Register</a>
        @endguest
        @auth
            <a href="/" class="hover:underline mx-3">Home</a>
        @endauth

    </div>
</div>
