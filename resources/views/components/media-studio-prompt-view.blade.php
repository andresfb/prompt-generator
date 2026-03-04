<div>
    <section class="text-gray-600 body-font">
        <div class="container px-5 mx-auto">

            <div class="flex flex-col text-center w-full mb-15">
                <h1 class="md:text-4xl text-3xl font-bold title-font text-gray-900">
                    {{ $prompt->header }}
                </h1>
            </div>

            <div class="flex flex-col text-center w-full mb-12">
                <h1 class="md:text-3xl text-2xl font-bold title-font mb-4 text-gray-900">
                    {{ $prompt->subHeader }}
                </h1>
            </div>

            <div class="flex flex-col text-center w-full mb-10">
                <h2 class="md:text-2xl text-xl font-semibold title-font text-gray-900 mb-2">
                    {{ $prompt->sectionTitle }}
                </h2>
                <p class="md:text-2xl text-xl font-medium text-gray-700">
                    {{ $prompt->title }}
                </p>
            </div>

            <div class="flex flex-col items-justify-start px-10 md:px-20 w-full mb-8">
                <h2 class="md:text-2xl text-xl font-medium text-gray-700 mb-4">
                    {{ $prompt->sectionDescription }}
                </h2>
                <p class="md:text-xl text-lg font-base">
                    {{ $prompt->description }}
                </p>
            </div>

        @if(! blank($prompt->tags))
            <div class="flex flex-col items-justify-start px-10 md:px-20 w-full mb-8">
                <h2 class="md:text-2xl text-xl font-medium text-gray-700 mb-4">
                    {{ $prompt->sectionTags }}
                </h2>
                <p class="md:text-xl text-lg font-base">
                    {{ collect($prompt->tags)->take(15)->implode('  ·  ') }}
                </p>
            </div>
        @endif

        @if(! blank($prompt->image))
            <div id="image" class="flex justify-center w-full pt-10 mb-8">
                <div x-data="{ revealed: false }" class="relative cursor-pointer lg:w-3/4 md:w-1/2 w-full" @click="revealed = true">
                    <img x-cloak alt="cover" class="w-full lg:h-auto h-64 object-cover object-center rounded transition-all duration-300" :class="revealed ? 'blur-0' : 'blur-2xl'" src="{{ $prompt->image }}">
                    <style>[x-cloak] { filter: blur(40px) !important; }</style>
                    <div x-show="!revealed" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="absolute inset-0 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-16 text-gray-300 drop-shadow-lg">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                    </div>
                </div>
            </div>
        @endif

        @if(! blank($prompt->trailer))
            <div id="video" class="flex flex-col text-center w-full pt-15 mb-8">
                <h2 class="md:text-3xl text-2xl font-medium text-gray-700 mb-6">
                    Trailer
                </h2>
                <div class="flex justify-center w-full px-8">
                    <video class="lg:w-3/4 md:w-1/2 w-full rounded" controls>
                        <source src="{{ $prompt->trailer }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
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
