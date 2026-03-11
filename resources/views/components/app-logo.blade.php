@props([
    'sidebar' => false,
    'name' => 'Fatema Alzahraa',
    'size' => 'size-14',
    'color' => 'bg-primary',
    'imgUrl' => '/logo.png',
])

@if($sidebar)
    <flux:sidebar.brand :name="$name" {{ $attributes }}>
        <x-slot name="logo">
            <div class="{{ $color }} size-6"
                 style="-webkit-mask-image: url(/FZLogo.png); -webkit-mask-size: contain; -webkit-mask-repeat: no-repeat; -webkit-mask-position: center; mask-image: url(/FZLogo.png); mask-size: contain; mask-repeat: no-repeat; mask-position: center;">
            </div>
        </x-slot>
    </flux:sidebar.brand>
@else
    <a href="/" {{ $attributes->merge(['class' => 'flex items-center gap-3']) }}>
        <div class="{{ $color }} {{ $size }}"
             style="-webkit-mask-image: url({{ $imgUrl }}); -webkit-mask-size: contain; -webkit-mask-repeat: no-repeat; -webkit-mask-position: center; mask-image: url({{ $imgUrl }}); mask-size: contain; mask-repeat: no-repeat; mask-position: center;">
        </div>
    </a>
@endif
