<x-layouts::auth.split :title="$title ?? null">
    @if (isset($cover))
        <x-slot:cover>
            {{ $cover }}
            </x-slot>
    @endif

        {{ $slot }}
</x-layouts::auth.split>