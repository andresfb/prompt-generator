<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 mx-auto">
            <div class="flex flex-col text-center w-full mb-15">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
                    {{ $prompt->title }}
                </h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center w-full mb-12">
                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionGenre }}
                    </h4>
                    <p class="leading-relaxed text-base">
                        {{ $prompt->genre }}
                    </p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionHero }}
                    </h4>
                    <p class="leading-relaxed text-base">
                        {{ $prompt->hero }}
                    </p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionFlaw }}
                    </h4>
                    <p class="leading-relaxed text-base">
                        {{ $prompt->flaw }}
                    </p>
                </div>
            </div>

            <div class="flex flex-col text-center w-full mb-8">
                <h2 class="sm:text-2xl text-xl font-semibold title-font mb-4 text-gray-700">
                    {{ $prompt->sectionPrompt }}
                </h2>
                <p class="leading-relaxed text-lg mb-4">
                    {{ $prompt->prompt }}
                </p>
            </div>

        @if(! blank($prompt->modifiers))
            <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
        @endif
        </div>

    </section>

</div>
