<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 md:px-20 mx-auto">
            <div class="flex flex-col text-center w-full mb-6">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
                    {{ $prompt->header }}
                </h1>
            </div>

            <div class="flex flex-col text-center w-full mb-5">
                <h2 class="sm:text-2xl text-xl font-medium title-font mb-1 text-gray-900">
                    {{ $prompt->genreTitle }}
                </h2>
                <p class="leading-relaxed text-xl mb-4">{{ $prompt->genre }}</p>
            </div>

            <div class="flex flex-col justify-start w-full mb-8">
                {!! $prompt->getOutlineHtml() !!}
            </div>

        @if(! blank($prompt->modifiers))
            <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
        @endif

        </div>

    </section>

</div>
