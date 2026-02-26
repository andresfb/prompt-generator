<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 mx-auto">
            <div class="flex flex-col text-center w-full mb-8">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
                    {{ $prompt->title }}
                </h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-center w-full">
                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionConflict }}
                    </h4>
                    <p class="leading-relaxed text-base">
                        {{ ucwords($prompt->conflict) }}
                    </p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionSubgenre }}
                    </h4>
                    <p class="leading-relaxed text-base">
                        {{ ucwords($prompt->subgenre) }}
                    </p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionRandomItem }}
                    </h4>
                    <p class="leading-relaxed text-base">
                        {{ ucwords($prompt->randomItem) }}
                    </p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionRandomWord }}
                    </h4>
                    <p class="leading-relaxed text-base">
                        {{ ucwords($prompt->randomWord) }}
                    </p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionMustFeature }}
                    </h4>
                    <p class="leading-relaxed text-base">
                        {{ ucwords($prompt->mustFeature) }}
                    </p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionMustAlsoFeature }}
                    </h4>
                    <p class="leading-relaxed text-base">
                        {{ ucwords($prompt->mustAlsoFeature) }}
                    </p>
                </div>

            </div>

            @if(! blank($prompt->modifiers))
                <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
            @endif
        </div>

    </section>

</div>
