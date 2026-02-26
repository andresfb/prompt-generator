@props([
    'plots'
])

<div>
    <ul class="list-disc list-inside">
        @foreach($plots as $plot)
            <li class="mb-2">
                {{ $plot->text }}
                @if(!blank($plot->description))
                    {{ $plot->description }}
                @endif
            </li>
        @endforeach
    </ul>
</div>
