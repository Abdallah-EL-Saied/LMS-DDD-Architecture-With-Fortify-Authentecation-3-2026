@props([
    'title' => '',
    'description' => null,
    'imgUrl' => '/FZLogo.png',
    'imgSize' => 'size-64',
])

<div {{ $attributes->merge(['class' => 'text-center max-w-4xl mx-auto mb-4 sm:mb-8 -mt-12 relative py-16']) }}>
    {{-- Decorative background element --}}
    @if($imgUrl)
        <div class="absolute inset-0 -top-10 opacity-7 pointer-events-none flex justify-center overflow-hidden">
            <x-app-logo :imgUrl="$imgUrl" :size="$imgSize" />
        </div>
    @endif

<h2
        class="text-3xl md:text-6xl font-bold text-primary relative z-10 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
    {{ $title }}
    </h2>

    @if($description)
        <p class="text-lg text-zinc-600 relative z-10 leading-relaxed mt-4">{{ $description }}</p>
    @endif
</div>
