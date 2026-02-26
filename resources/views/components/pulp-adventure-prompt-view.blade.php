<div>

    <section class="text-gray-600 body-font">

        <div class="container px-5 mx-auto">

            <div class="flex flex-col text-center w-full mb-8">
                <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
                    {{ $prompt->title }}
                </h1>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-center w-full mb-8">
                <div class="grow">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-3">
                        {{ str($prompt->villanTitle)->plural($prompt->villans->count())->toString() }}
                    </h2>
                    <p class="leading-relaxed text-base">
                        <x-pulp-adventure-villans-view :villans="$prompt->villans"/>
                    </p>
                </div>

                <div class="grow">
                    <h2 class="text-gray-900 text-lg title-font font-medium mb-3">
                        {{ str($prompt->plotTitle)->plural($prompt->plots->count())->toString() }}
                    </h2>
                    <p class="leading-relaxed text-base">
                        <x-pulp-adventure-plots-view :plots="$prompt->plots"/>
                    </p>
                </div>

                <div class="grow">
                    <h2 class="text-gray-900 text-lg title-font font-medium">
                        {{ $prompt->mainLocationTitle }}
                    </h2>
                    <p class="leading-relaxed text-base">
                        {{ $prompt->mainLocation }}
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-full mb-6">

                    <div @class([
                        'h-full',
                        'bg-gray-100',
                        'bg-opacity-75',
                        'px-8',
                        'pt-4',
                        'pb-4',
                        'rounded-lg',
                        'overflow-hidden',
                        'relative',
                    ])>
                        <h1 class="title-font sm:text-2xl text-xl text-center font-medium text-gray-900 mb-8">
                            {{ $prompt->sectionAct1 }}
                        </h1>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 w-full mb-8">

                            <div class="grow">
                                <div class="flex flex-col w-full mb-5">
                                    <h5 class="title-font sm:text-xl text-base font-medium text-gray-900">
                                        {{
                                            str($prompt->hockTitle)
                                               ->plural($prompt->hockElements->count())
                                               ->toString()
                                        }}
                                    </h5>
                                </div>
                                <div class="flex flex-col w-full mb-10">
                                    <ul class="list-disc list-inside text-base">
                                        @foreach($prompt->hockElements as $element)
                                            <li class="mb-4">
                                                {{ $element->text }}
                                                @if(!blank($element->description))
                                                    {{ $element->description }}
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="grow">
                                <div class="flex flex-col w-full mb-5">
                                    <h5 class="title-font sm:text-xl text-base font-medium text-gray-900">
                                        {{
                                            str($prompt->supportCharactersTitle)
                                                ->plural($prompt->supportCharactersCount)
                                                ->toString()
                                        }}
                                        <span class="text-base font-normal ms-1">
                                            ({{ $prompt->supportCharactersCount }})
                                        </span>
                                    </h5>
                                </div>
                                <div class="flex flex-col w-full mb-10">
                                    <ul class="list-disc list-inside text-base">
                                        @foreach($prompt->supportCharacters as $element)
                                            <li class="mb-2">
                                                {{ $element->text }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            <div class="grow">
                                <x-pulp-adventure-sequences-view
                                    :title="$prompt->act1ActionSequenceTitle"
                                    :count="$prompt->act1ActionSequenceCount"
                                    :sequences="$prompt->act1ActionSequences"
                                />
                            </div>

                        </div>

                    </div>

                </div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-full mb-6">
                    <div @class([
                        'h-full',
                        'bg-gray-100',
                        'bg-opacity-75',
                        'px-8',
                        'pt-4',
                        'pb-4',
                        'rounded-lg',
                        'overflow-hidden',
                        'relative',
                    ])>
                        <h1 class="title-font sm:text-2xl text-xl text-center font-medium text-gray-900 mb-8">
                            {{ $prompt->sectionAct2 }}
                        </h1>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full mb-8">

                            <div class="grow">
                                <x-pulp-adventure-sequences-view
                                    :title="$prompt->act2ActionSequenceTitle"
                                    :count="$prompt->act2ActionSequenceCount"
                                    :sequences="$prompt->act2ActionSequences"
                                />
                            </div>

                            <div class="grow">
                                <x-pulp-adventure-twist-view
                                    :title="$prompt->act2PlotTwistTitle"
                                    :twist="$prompt->act2PlotTwist"
                                />
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-wrap">
                <div class="w-full mb-6">
                    <div @class([
                        'h-full',
                        'bg-gray-100',
                        'bg-opacity-75',
                        'px-8',
                        'pt-4',
                        'pb-4',
                        'rounded-lg',
                        'overflow-hidden',
                        'relative',
                    ])>
                        <h1 class="title-font sm:text-2xl text-xl text-center font-medium text-gray-900 mb-8">
                            {{ $prompt->sectionAct3 }}
                        </h1>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full mb-8">

                            <div class="grow">
                                <x-pulp-adventure-sequences-view
                                    :title="$prompt->act3ActionSequenceTitle"
                                    :count="$prompt->act3ActionSequenceCount"
                                    :sequences="$prompt->act3ActionSequences"
                                />
                            </div>

                            <div class="grow">
                                <x-pulp-adventure-twist-view
                                    :title="$prompt->act3PlotTwistTitle"
                                    :twist="$prompt->act3PlotTwist"
                                />
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @if(! blank($prompt->modifiers))
                <x-prompt-modifiers-view :modifiers="$prompt->modifiers"/>
            @endif

        </div>

    </section>

</div>
