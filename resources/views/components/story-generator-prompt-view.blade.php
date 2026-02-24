<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 mx-auto">
            <div class="flex flex-col text-center w-full mb-8">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">{{ $prompt->title }}</h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-center w-full pb-5">
                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionSituation }}</h4>
                    <p class="leading-relaxed text-base">{{ ucwords($prompt->situation) }}</p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionCharacter }}</h4>
                    <p class="leading-relaxed text-base">{{ ucwords($prompt->character) }}</p>
                </div>

                <div class="grow">
                    <h4 class="text-gray-900 text-lg title-font font-medium">{{ $prompt->sectionAction }}</h4>
                    <p class="leading-relaxed text-base">{{ ucwords($prompt->action) }}</p>
                </div>

            </div>

        @if(! blank($prompt->modifiers))
            <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
        @endif
        </div>

    </section>

</div>
