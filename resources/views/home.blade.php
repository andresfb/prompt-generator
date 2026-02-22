<x-layouts.app title="Random Prompt">

@if(blank($prompt->getView()))
    {!! $prompt->toHtml() !!}
@else
    <x-dynamic-component :component="$prompt->getView()" :prompt="$prompt" />
@endif

</x-layouts.app>
