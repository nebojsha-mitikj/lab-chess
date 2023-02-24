<x-app-layout>
    <div class="py-7 sm:py-16 px-3 max-w-7xl mx-auto">
        <trainer-variant
            :trainer-prop='@json($trainer)'
            :trainers='@json($trainers)'
            :variants-prop='@json($variants)'
            :variant-id-prop='@json($variantId)'
            :progress-prop='@json($progress)'
            :positions-prop='@json($positions)'
        ></trainer-variant>
    </div>
</x-app-layout>
