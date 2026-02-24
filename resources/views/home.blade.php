<x-layouts.app title="Random Prompt">

@if(blank($prompt->getView()))
    {!! $prompt->toHtml() !!}
@else
    <x-dynamic-component :component="$prompt->getView()" :prompt="$prompt" />
@endif

    <div
        x-data="{
            submitted: false,
            submit() {
                window.axios.post('{{ route('mark-used') }}', {
                    hash: '{{ $prompt->hash() }}'
                }).then(() => {
                    this.submitted = true;
                });
            }
        }"
        class="flex flex-col text-center w-full mt-10 py-10"
    >
        <button
            @click="submit()"
            :disabled="submitted"
            class="mt-2 self-center rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-600 disabled:opacity-50 disabled:cursor-not-allowed"
            x-text="submitted ? 'Marked as Used' : 'Mark as Used'"
        ></button>

{{--    <form action="{{ route('mark-used') }}" method="post" class="flex flex-col text-center w-full mt-10 py-10">--}}
{{--        @csrf--}}

{{--        <input type="hidden" name="hash" value="{{ $prompt->hash() }}">--}}

{{--        <button type="submit" class="mt-2 self-center rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white hover:bg-gray-800">Mark as Used</button>--}}
{{--    </form>--}}

</x-layouts.app>
