<x-layouts::app.global-header :title="$title ?? null">
    {{ $slot }}
    @include('layouts.app.global-footer')
</x-layouts::app.global-header>