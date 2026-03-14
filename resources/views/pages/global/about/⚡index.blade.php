<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.welcome')] class extends Component {

};
?>

<!-- About Us Page -->
<div dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <x-page-header :title="__('global.header.about')" :subtitle="__('landing.about.desc_1')" />

    <!-- About Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
            <!-- Text Content -->
            <div>
                <h2 class="text-3xl font-bold text-primary mb-6 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('landing.about.heading') }}
                </h2>
                <div class="text-lg text-gray-700 space-y-4 leading-relaxed">
                    <p>{{ __('landing.about.desc_1') }}</p>
                    <p>{{ __('landing.about.desc_2') }}</p>
                </div>
            </div>
            <!-- Interactive Image/Visual placeholder -->
            <div class="relative rounded-3xl overflow-hidden shadow-2xl h-96 group">
                <img src="{{ asset('assets/hero.webp') }}" alt="{{ __('landing.about.heading') }}" class="object-cover w-full h-full transform group-hover:scale-110 transition-transform duration-700">
                <div class="absolute inset-0 bg-primary/20 group-hover:bg-transparent transition-colors duration-500"></div>
            </div>
        </div>

        <!-- Goals Section -->
        <div class="mt-24">
            <h2 class="text-3xl font-bold text-center text-primary mb-12 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('landing.about.goals_heading') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach (range(1, 5) as $i)
                    <div class="bg-white p-8 rounded-2xl shadow-lg border-b-4 border-secondary hover:-translate-y-2 transition-transform duration-300">
                        <div class="size-14 bg-primary/10 rounded-full flex items-center justify-center text-primary mb-6">
                            <i class="fa-solid fa-star text-2xl"></i>
                        </div>
                        <p class="text-gray-800 text-lg font-medium">{{ __('landing.about.goal_' . $i) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Reused "Why Choose Us" component -->
    <livewire:pages::global.home.features />
</div>