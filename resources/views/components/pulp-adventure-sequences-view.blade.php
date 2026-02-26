@props([
    'sequences'
])

<div>
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
