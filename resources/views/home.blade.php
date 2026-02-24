<x-layouts.app title="Random Prompt">

@if(blank($prompt->getView()))
    {!! $prompt->toHtml() !!}
@else
    <x-dynamic-component :component="$prompt->getView()" :prompt="$prompt" />
@endif

    <form action="{{ route('mark-used') }}" method="post" class="flex flex-col text-center w-full mt-10 py-10">
        @csrf

        <input type="hidden" value="{{ $prompt->hash() }}">

        <button type="submit" class="mt-5">Mark as Used</button>
    </form>

</x-layouts.app>
