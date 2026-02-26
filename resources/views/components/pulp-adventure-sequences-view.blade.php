@props([
    'title',
    'count',
    'sequences',
])

<div>
    <div class="flex flex-col w-full mb-5">
        <h5 class="title-font sm:text-xl text-base font-medium text-gray-900">
            {{ str($title)->plural($count)->toString() }}
            <span class="text-base font-normal ms-1">
                ({{ $count }})
            </span>
        </h5>
    </div>

    <div class="flex flex-col w-full mb-10">
        <ol>
        @foreach($sequences as $sequence)
            <li class="mb-5">
                <ul class="list-disc list-inside text-base">
                    <li>
                        <span class="text-base font-medium">{{ $sequence->typeTitle }}</span>
                        <span class="text-base font-normal ms-1">{{ $sequence->type }}</span>
                    </li>
                    <li>
                        <span class="text-base font-medium">{{ $sequence->participantsTitle }}</span>
                        <span class="text-base font-normal ms-1">{{ $sequence->participants }}</span>
                    </li>
                    <li>
                        <span class="text-base font-medium">{{ $sequence->settingTitle }}</span>
                        <span class="text-base font-normal ms-1">{{ $sequence->setting }}</span>
                    </li>
                    <li>
                        <span class="text-base font-medium">{{ $sequence->complicationsTitle }}</span>
                        <span class="text-base font-normal ms-1">{{ $sequence->complications }}</span>
                        @if(! blank($sequence->complicationsDescription))
                            <div class="text-sm font-normal ">
                                {{ $sequence->complicationsDescription }}
                            </div>
                        @endif
                    </li>
                </ul>
            </li>
        @endforeach
        </ol>
    </div>
</div>
