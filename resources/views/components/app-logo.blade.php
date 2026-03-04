@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Fatema Alzahraa Center" {{ $attributes }}>
        <x-slot name="logo">
            <img src="/FZLogo.png" class="size-8" alt="Fatema Alzahraa Center Logo">
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Fatema Alzahraa Center" {{ $attributes }}>
        <x-slot name="logo">
            <img src="/FZLogo.png" class="size-8" alt="Fatema Alzahraa Center Logo">
        </x-slot>
    </flux:brand>
@endif
