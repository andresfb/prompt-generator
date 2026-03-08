<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 mx-auto">
            <div class="flex flex-col text-center w-full mb-5">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
                    {{ $prompt->header }}
                </h1>
            </div>

            <div class="flex flex-col text-center w-full mb-5">
                <h2 class="sm:text-2xl text-xl font-medium title-font mb-4 text-gray-900">
                    {{ $prompt->title }}
                </h2>
            </div>

            <div class="flex flex-col items-center w-full mb-8" x-data="{ showTip: false }">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <h4 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->sectionDescription }}
                    </h4>

                    @if(! blank($prompt->hint))
                        <button
                            type="button"
                            x-on:click="showTip = !showTip"
                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 text-sm font-bold cursor-pointer"
                            title="Show tip"
                        >
                            ?
                        </button>
                    @endif

                </div>

                <p class="leading-relaxed text-base text-center max-w-2xl">
                    {!! nl2br(e($prompt->description)) !!}
                </p>

                @if(! blank($prompt->hint))
                    <div
                        x-show="showTip"
                        x-transition
                        x-cloak
                        class="mt-4 p-4 bg-amber-50 border border-amber-200 rounded-lg max-w-2xl w-full"
                    >
                        <h5 class="text-gray-900 text-sm title-font font-medium mb-1">
                            {{ $prompt->sectionHint }}
                        </h5>
                        <p class="leading-relaxed text-sm text-gray-700">
                            {!! $prompt->getHintHtml() !!}
                        </p>
                    </div>
                @endif

            </div>

            <div class="flex flex-col text-center w-full mb-5">
                <h3 class="sm:text-xl text-lg font-semibold title-font mb-4 text-gray-700">
                    {{ $prompt->subHeader }}
                </h3>
                <p class="leading-relaxed text-lg mb-4">
                    {!! nl2br(e($prompt->text)) !!}
                </p>
            </div>

            @if(! blank($prompt->modifiers))
                <x-prompt-modifiers-view :modifiers="$prompt->modifiers"/>
            @endif
        </div>

    </section>

</div>
