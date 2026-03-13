@props([
    'title' => '',
    'description' => null,
    'imgUrl' => '/FZLogo.png',
    'imgSize' => 'size-64',
    'showLine' => false,
])

<div {{ $attributes->merge(['class' => 'text-center max-w-3xl mx-auto mb-16 -mt-10 relative py-16']) }}>
    {{-- Decorative background element --}}
    @if($imgUrl)
        <div class="absolute inset-0 -top-10 opacity-7 pointer-events-none flex justify-center overflow-hidden">
            <x-app-logo :imgUrl="$imgUrl" :size="$imgSize" />
        </div>
    @endif

<h2
        class="text-3xl md:text-6xl font-bold text-primary mb-4 relative z-10 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
    {{ $title }}
    </h2>

    @if($description)
        <p class="text-lg text-zinc-600 relative z-10 leading-relaxed">{{ $description }}</p>
    @endif
    
    @if($showLine || (!$description && $showLine !== false))
        <div class="w-24 h-1.5 bg-tertiary mx-auto rounded-full mt-6 relative z-10"></div>
    @endif
</div>
