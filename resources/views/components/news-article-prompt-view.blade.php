<div>
    <section class="text-gray-600 body-font">
        <div class="container px-5 mx-auto">

            <div class="flex flex-col text-center w-full mb-15">
                <h1 class="md:text-4xl text-3xl font-bold title-font text-gray-900">
                    {{ $prompt->header }}
                </h1>
            </div>

            <div class="flex flex-col text-center w-full mb-10">
                <h2 class="md:text-2xl text-xl font-semibold title-font text-gray-900 mb-2">
                    {{ $prompt->sectionSource }}
                </h2>
                <p class="md:text-xl text-lg font-medium text-gray-700">
                    {{ $prompt->source }}
                </p>
            </div>

            <div class="flex flex-col text-center w-full mb-10">
                <h2 class="md:text-2xl text-xl font-semibold title-font text-gray-900 mb-2">
                    {{ $prompt->sectionTitle }}
                </h2>
                <p class="md:text-xl text-lg font-medium text-gray-700 mb-3">
                    {{ $prompt->title }}
                </p>
                <p class="text-base">
                    <a class="inline-flex items-center gap-1 underline text-indigo-500 hover:text-indigo-700" href="{{ $prompt->permalink }}" target="_blank">
                        Permalink
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                </p>
            </div>

        @if(! blank($prompt->thumbnail))
            <div class="flex justify-center w-full mb-10">
                <div class="lg:w-3/4 md:w-1/2 w-full">
                    <img alt="article thumbnail" class="w-full lg:h-auto h-64 object-cover object-center rounded" src="{{ $prompt->thumbnail }}">
                </div>
            </div>
        @endif

            <div class="flex flex-col items-justify-start px-10 md:px-20 w-full mb-8">
                <p class="md:text-xl text-lg font-base leading-relaxed">
                    {{ $prompt->content }}
                </p>
            </div>

            <div class="pt-10">
            @if(! blank($prompt->modifiers))
                <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
            @endif
            </div>
        </div>
    </section>
</div>
