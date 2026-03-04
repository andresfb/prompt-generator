@props([
    'prompt',
    'trailer',
    'width' => 840,
    'height' => 472,
])

<div>
    @php
        $embeddedUrl = $prompt->embeddedTrailer($trailer)
    @endphp

    @if(blank($embeddedUrl))
         
    @else
        <iframe
            width="{{ $width }}"
            height="{{ $height }}"
            src="{{ $embeddedUrl }}"
            title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen></iframe>
    @endif
</div>
