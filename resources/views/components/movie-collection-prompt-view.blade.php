<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 mx-auto">

            <div class="flex flex-col text-center w-full mb-6">
                <h1 class="md:text-3xl text-2xl font-bold title-font mb-4 text-gray-900">
                    {{ $prompt->header }}
                </h1>
            </div>

            <div class="flex flex-col text-center w-full mb-15">
                <h2 class="md:text-2xl text-xl font-semibold title-font text-gray-900 mb-2">
                    Collection
                </h2>
                <p class="md:text-2xl text-xl font-medium text-gray-000">
                    {{ $prompt->subHeader }}
                </p>
            </div>

            <div class="lg:w-4/5 mx-auto flex flex-wrap">
                <img alt="cover" class="lg:w-1/2 w-full lg:h-auto h-64 object-cover object-center rounded" src="{{ $prompt->image }}">
                <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">

                    <h1 class="text-gray-900 text-3xl title-font font-medium mb-7">
                        <a class="inline-flex items-center gap-1 underline" href="{{ $prompt->url }}" target="_blank">
                            {{ $prompt->title }}
                        </a>
                    </h1>
                    <p class="leading-relaxed mb-6">
                        {{ $prompt->overview }}
                    </p>

                @if(! blank($prompt->tagLines))
                    <div class="mb-5">
                        <h1 class="text-gray-800 text-lg title-font font-medium mb-1">
                            {{ str($prompt->sectionTagLines)->plural(count($prompt->tagLines))->toString() }}
                        </h1>
                        @foreach($prompt->tagLines as $tagLine)
                            <p class="leading-relaxed mb-1">
                                {{ $tagLine }}
                            </p>
                        @endforeach
                    </div>
                @endif

                @if(! blank($prompt->genres))
                    <h1 class="text-gray-800 text-lg title-font font-medium mb-1">
                        {{ str($prompt->sectionGenres)->plural(count($prompt->genres))->toString() }}
                    </h1>
                    <p class="leading-relaxed mb-1">
                        {{ implode(', ', $prompt->genres) }}
                    </p>
                @endif

                </div>
            </div>

        @if(! blank($prompt->trailers))
            <div class="mt-10 pt-8 text-center mb-2">
                <h2 class="md:text-2xl text-xl font-semibold title-font text-gray-900 mb-8">
                    Trailers
                </h2>

            @if(count($prompt->trailers) === 1)
                <div class="flex justify-center-safe text-center w-full">
                    <x-youtube-embeded-trailer :prompt="$prompt" :trailer="$prompt->trailers[0]" />
                </div>
            @else
                <div id="trailers" class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center w-full mt-10">
                @foreach($prompt->trailers as $trailer)
                    <div class="grow">
                        <x-youtube-embeded-trailer :prompt="$prompt" :trailer="$trailer" />
                    </div>
                @endforeach
                </div>
            @endif

            </div>
        @endif

            <div class="pt-10">
            @if(! blank($prompt->modifiers))
                <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
            @endif
            </div>

        </div>

    </section>

</div>
