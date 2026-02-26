@props([
    'villans'
])

<div>
    <ul class="list-disc list-inside">
    @foreach($villans as $villan)
        <li class="mb-2">
            {{ $villan->text }} <br>
        @if(!blank($villan->description))
            <small class="text-xs">
                {{ $villan->description }}
            </small>
        @endif
        </li>
    @endforeach
    </ul>
</div>
