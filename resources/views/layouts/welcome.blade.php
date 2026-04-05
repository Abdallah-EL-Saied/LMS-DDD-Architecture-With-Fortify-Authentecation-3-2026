<x-layouts::app.global-header :title="$title ?? null">
    {{ $slot }}
    <x-layouts::app.global-footer />
</x-layouts::app.global-header>