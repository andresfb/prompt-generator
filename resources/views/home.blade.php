<x-layouts.app title="Random Prompt">

@if(blank($prompt->getView()))
    {!! $prompt->toHtml() !!}
@else
    <x-dynamic-component :component="$prompt->getView()" :prompt="$prompt" />
@endif

    <div class="flex flex-col text-center w-full mt-10 py-10">
        <form method="POST" action="{{ route('download') }}">
            @csrf

            <input type="hidden" name="hash" value="{{ $prompt->hash() }}">
            <button
                type="submit"
                class="mt-2 self-center rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-600"
            >Download</button>
        </form>
    </div>

</x-layouts.app>
