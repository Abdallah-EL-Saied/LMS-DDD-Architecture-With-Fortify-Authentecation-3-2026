@props([
    'color' => 'bg-primary'
])
<div {{ $attributes->merge(['class' => $color]) }}
     style="-webkit-mask-image: url(/FZLogo.png); -webkit-mask-size: contain; -webkit-mask-repeat: no-repeat; -webkit-mask-position: center; mask-image: url(/FZLogo.png); mask-size: contain; mask-repeat: no-repeat; mask-position: center;">
</div>
