<x-layouts.app title="Random Prompt">

    <x-dynamic-component :component="$prompt->getView()" :prompt="$prompt" />

</x-layouts.app>
