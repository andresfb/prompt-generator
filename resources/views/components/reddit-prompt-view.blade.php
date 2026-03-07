<div>
    <section class="text-gray-600 body-font">

        <div class="container px-10 mx-auto">
            <div class="flex flex-col text-center w-full mb-12">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">{{ $prompt->header }}</h1>
            </div>

            <div class="flex flex-col text-center w-full mb-10">
                <p class="leading-relaxed text-lg mb-4">{!! $prompt->getHtmlTitle() !!}</p>
                <p class="text-base">
                    <a class="inline-flex items-center gap-1 underline text-indigo-700 hover:text-indigo-700" href="{{ $prompt->permalink }}" target="_blank">
                        Permalink
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                </p>
            </div>

        @if(! blank($prompt->modifiers))
            <x-prompt-modifiers-view :modifiers="$prompt->modifiers" />
        @endif

        </div>

    </section>
</div>
