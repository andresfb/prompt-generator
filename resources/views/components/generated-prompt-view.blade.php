<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 mx-auto">
            <div class="flex flex-col text-center w-full mb-5">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">{{ $prompt->title }}</h1>
            </div>

            <div class="flex flex-col text-center w-full mb-10">
                <p class="leading-relaxed text-lg mb-4">{{ $prompt->content }}</p>
            </div>

            <div class="flex flex-col text-center w-full mb-6">
                <h1 class="text-lg sm:text-xl text-gray-900 font-medium title-font mb-3">{{ $prompt->subHeader }}</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-center w-full">

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionGenre }}</h4>
                    <p class="leading-relaxed text-base">{{ $prompt->genre }}</p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionSetting }}</h4>
                    <p class="leading-relaxed text-base">{{ $prompt->setting }}</p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionCharacter }}</h4>
                    <p class="leading-relaxed text-base">{{ $prompt->character }}</p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionConflict }}</h4>
                    <p class="leading-relaxed text-base">{{ $prompt->conflict }}</p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionTone }}</h4>
                    <p class="leading-relaxed text-base">{{ $prompt->tone }}</p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionNarrative }}</h4>
                    <p class="leading-relaxed text-base">{{ $prompt->narrative }}</p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionPeriod }}</h4>
                    <p class="leading-relaxed text-base">{{ $prompt->period }}</p>
                </div>

            </div>

            <div class="flex text-center flex-col w-full mt-10 mb-6">
                <p class="leading-relaxed text-sm">
                    <span class="font-semibold">{{ $prompt->sectionEnd }}:</span> {{ $prompt->endText }}
                </p>
            </div>

        @if(! blank($prompt->modifiers))
            <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
        @endif

        </div>

    </section>

</div>
