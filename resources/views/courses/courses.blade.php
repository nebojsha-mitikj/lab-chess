<x-app-layout>
    <div class="py-7 sm:py-16 px-3 max-w-7xl mx-auto">
        <courses-main
            :courses='@json($courses)'
        ></courses-main>
    </div>
</x-app-layout>
