<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="relative py-20 overflow-hidden" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Background Image -->
    <div class="absolute inset-0 bg-cover bg-center bg-fixed"
        style="background-image: url('{{ asset('assets/auth.webp') }}');"></div>

    <!-- Heavy Overlay -->
    <div class="absolute inset-0 bg-primary/90 backdrop-blur-sm"></div>

    <!-- Decorative patterns -->
    <div class="absolute inset-0 opacity-10"
        style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;">
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
        <h2
            class="text-3xl md:text-5xl font-bold text-white mb-6 leading-tight {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
            {{ __('home.cta.heading') }}<br>
            <span class="text-tertiary">{{ __('home.cta.subheading') }}</span>
        </h2>

        <p class="text-lg md:text-xl text-white/80 mb-10 max-w-2xl mx-auto">
            {{ __('home.cta.desc') }}
        </p>

        <div class="flex flex-col sm:flex-row justify-center gap-4">
            <flux:button variant="primary" :href="route('register')"
                class="bg-tertiary text-primary hover:bg-tertiary-400 border-none px-10 py-4 text-xl font-bold shadow-xl rounded-full transition-all duration-300 hover:scale-105 active:scale-95 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('home.cta.register_btn') }}
            </flux:button>
        </div>
    </div>
</div>