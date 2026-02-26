<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 mx-auto">
            <div class="flex flex-col text-center w-full mb-6">
                <h1 class="sm:text-3xl text-2xl font-bold title-font mb-4 text-gray-900">
                    {{ $prompt->header }}
                </h1>
            </div>

            <div class="flex flex-col text-center w-full mb-2">
                <h2 class="sm:text-2xl text-xl font-semibold title-font mb-4 text-gray-900">
                    {{ $prompt->title }}
                </h2>
            </div>

            <div class="flex flex-col text-center w-full">
                <p class="leading-relaxed text-lg font-medium text-gray-800 mb-1">
                    {{ $prompt->sectionDescription }}
                </p>
            </div>

            <div class="flex flex-col text-center w-full mb-8">
                <p class="leading-relaxed text-sm px-0 md:px-20 lg:px-50 mb-4">
                    {{ $prompt->description }}
                </p>
            </div>

            <div class="flex flex-col text-center w-full mb-2">
                <h2 class="sm:text-2xl text-xl font-semibold title-font text-gray-900">
                    {{ $prompt->subHeader }}
                </h2>
            </div>

            <div class="flex flex-col text-center w-full mb-8">
                <p class="leading-relaxed text-lg mb-4">
                    {{ $prompt->text }}
                </p>
            </div>

        @if(! blank($prompt->modifiers))
            <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
        @endif

        </div>

    </section>

</div>
