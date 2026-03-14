@props([
    'title',
    'subtitle' => null,
])

<div class="relative bg-primary-500 py-32 overflow-hidden border-b-4 border-tertiary">
    <div class="absolute inset-0 bg-linear-to-b from-primary from-0% via-primary/70 to-primary/60"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h1
            class="text-4xl md:text-6xl font-bold text-white mb-6 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
            {{ $title }}
        </h1>
        @if($subtitle)
            <p class="text-xl text-white/80 max-w-2xl mx-auto">
                {{ $subtitle }}
            </p>
        @endif
    </div>
</div>
