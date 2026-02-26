@props([
    'title',
    'twist'
])

<div>

    <div class="flex flex-col w-full mb-5">
        <h5 class="title-font sm:text-xl text-base font-medium text-gray-900 mb-5">
            {{ $title }}
        </h5>

        <div class="text-base font-medium text-gray-800 mb-5">
            {{ $twist->text }}
        @if(! blank($twist->description))
            <div class="text-sm font-normal ">
                {{ $twist->description }}
            </div>
        @endif
        </div>

    @if(! blank($twist->roll))
        @switch($twist->rollType)
            @case('ML')
                <div class="font-normal text-gray-800">
                    {{ $twist->roll }}
                </div>
                @break

            @case('VL')
                <x-pulp-adventure-villans-view :villans="$twist->roll"/>
                @break

            @case('FP')
                <x-pulp-adventure-plots-view :plots="$twist->roll"/>
                @break
            
        @endswitch
    @else

    @endif
    </div>

</div>
