<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="py-20 bg-surface" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2
                class="text-3xl md:text-4xl font-bold text-primary mb-4 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('landing.features.heading') }}</h2>
            <p class="text-lg text-zinc-600">{{ __('landing.features.subheading') }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Feature 1 -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-zinc-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300">
                <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-6 text-primary">
                    <i class="fa-solid fa-graduation-cap text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-zinc-900 mb-3 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('landing.features.f1_title') }}</h3>
                <p class="text-zinc-600 leading-relaxed">{{ __('landing.features.f1_desc') }}</p>
            </div>

            <!-- Feature 2 -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-zinc-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 delay-100">
                <div
                    class="w-14 h-14 bg-tertiary/20 rounded-xl flex items-center justify-center mb-6 text-tertiary-500">
                    <i class="fa-regular fa-clock text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-zinc-900 mb-3 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('landing.features.f2_title') }}</h3>
                <p class="text-zinc-600 leading-relaxed">{{ __('landing.features.f2_desc') }}</p>
            </div>

            <!-- Feature 3 -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-zinc-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 delay-200">
                <div class="w-14 h-14 bg-primary/10 rounded-xl flex items-center justify-center mb-6 text-primary">
                    <i class="fa-solid fa-book-open text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-zinc-900 mb-3 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('landing.features.f3_title') }}</h3>
                <p class="text-zinc-600 leading-relaxed">{{ __('landing.features.f3_desc') }}</p>
            </div>

            <!-- Feature 4 -->
            <div
                class="bg-white p-6 rounded-2xl shadow-sm border border-zinc-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 delay-300">
                <div
                    class="w-14 h-14 bg-tertiary/20 rounded-xl flex items-center justify-center mb-6 text-tertiary-500">
                    <i class="fa-solid fa-chart-line text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-zinc-900 mb-3 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('landing.features.f4_title') }}</h3>
                <p class="text-zinc-600 leading-relaxed">{{ __('landing.features.f4_desc') }}</p>
            </div>
        </div>
    </div>
</div>