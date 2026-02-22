<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 mx-auto">
            <div class="flex flex-col text-center w-full mb-5">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">{{ $prompt->header }}</h1>
            </div>

            <div class="flex flex-col text-center w-full mb-8">
                <p class="leading-relaxed text-lg mb-4">{{ $prompt->text }}</p>
            </div>

        @if(! blank($prompt->modifiers))
            <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
        @endif

        </div>

    </section>

</div>
