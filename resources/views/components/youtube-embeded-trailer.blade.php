@props([
    'prompt',
    'trailer',
])

<div>
    @php
        $embeddedUrl = $prompt->embeddedTrailer($trailer)
    @endphp

    @if(blank($embeddedUrl))
         
    @else
        <iframe
            width="560"
            height="315"
            src="{{ $embeddedUrl }}"
            title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen></iframe>
    @endif
</div>
