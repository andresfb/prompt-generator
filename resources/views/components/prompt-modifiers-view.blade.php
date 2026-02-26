<div>
    <div class="flex items-center flex-wrap pb-4 mb-4 mt-8 border-b-2 border-gray-100 w-full">
    </div>

    <div class="flex flex-col text-center w-full mb-10">
        <h1 class="text-lg sm:text-xl text-gray-900 font-medium title-font mb-3">{{ $modifiers->title }}</h1>
    </div>

    <div id="modifiers" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 text-center w-full">
        <div class="grow">
            <h4 class="text-gray-900 text-lg title-font font-medium">{{ $modifiers->sectionAge }}</h4>
            <p class="leading-relaxed text-base">{{ $modifiers->age }}</p>
        </div>
        <div class="grow">
            <h4 class="text-gray-900 text-lg title-font font-medium">{{ $modifiers->sectionDescendancy }}</h4>
            <p class="leading-relaxed text-base">{{ $modifiers->descendancy }}</p>
        </div>
        <div class="grow">
            <h4 class="text-gray-900 text-lg title-font font-medium">{{ $modifiers->sectionGender }}</h4>
            <p class="leading-relaxed text-base">{{ $modifiers->gender }}</p>
        </div>
        <div class="grow">
            <h4 class="text-gray-900 text-lg title-font font-medium">{{ $modifiers->sectionPointOfView }}</h4>
            <p class="leading-relaxed text-sm">{{ $modifiers->pointOfView }}</p>
        </div>
        <div class="grow">
            <h4 class="text-gray-900 text-lg title-font font-medium">{{ $modifiers->sectionTimePeriods }}</h4>
            <p class="leading-relaxed text-base">{{ $modifiers->timePeriods }}</p>
        </div>
        <div class="grow">
        @if($modifiers->anachronise)
            <p class="leading-relaxed text-base font-semibold"><br>{{ $modifiers->anachroniseText }}</p>
        @else
             
        @endif
        </div>
    </div>
</div>
