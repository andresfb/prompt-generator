<div>
    <section class="text-gray-600 body-font">
        <div class="container px-5 mx-auto">

            <div class="flex flex-col text-center w-full mb-15">
                <h1 class="md:text-4xl text-3xl font-bold title-font text-gray-900">
                    {{ $prompt->header }}
                </h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-10 md:px-20 w-full mb-12">
                <div>
                    <h2 class="md:text-2xl text-xl font-medium text-gray-700 mb-2">
                        {{ $prompt->sectionGenre }}
                    </h2>
                    <p class="md:text-xl text-lg font-base">
                        {{ $prompt->genre }}
                    </p>
                </div>

            @if(! blank($prompt->subGenre))
                <div>
                    <h2 class="md:text-2xl text-xl font-medium text-gray-700 mb-2">
                        {{ $prompt->sectionSubGenre }}
                    </h2>
                    <p class="md:text-xl text-lg font-base">
                        {{ $prompt->subGenre }}
                    </p>
                </div>
            @endif

                <div>
                    <h2 class="md:text-2xl text-xl font-medium text-gray-700 mb-2">
                        {{ $prompt->sectionTone }}
                    </h2>
                    <p class="md:text-xl text-lg font-base">
                        {{ $prompt->tone }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col items-justify-start px-10 md:px-20 w-full mb-8">
                <h2 class="md:text-2xl text-xl font-medium text-gray-700 mb-4">
                    {{ $prompt->sectionIdea }}
                </h2>
                <p class="md:text-xl text-lg font-base leading-relaxed">
                    {{ $prompt->idea }}
                </p>
            </div>

            <div class="pt-8">
            @if(! blank($prompt->modifiers))
                <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
            @endif
            </div>
        </div>
    </section>
</div>
