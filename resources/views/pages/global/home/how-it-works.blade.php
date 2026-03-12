<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="py-24 bg-white" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="text-center mb-20">
            <h2
                class="text-3xl md:text-4xl font-bold text-primary mb-4 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('landing.how_it_works.heading') }}</h2>
            <p class="text-lg text-zinc-600 max-w-2xl mx-auto">{{ __('landing.how_it_works.subheading') }}</p>
        </div>

        <div class="relative max-w-5xl mx-auto">
            <!-- Connecting Line (Desktop) -->
            <div class="hidden md:block absolute top-1/2 left-0 w-full h-1 bg-zinc-100 -translate-y-1/2 z-0"></div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 md:gap-6 relative z-10">

                <!-- Step 1 -->
                <div class="flex flex-col items-center text-center group">
                    <div
                        class="w-20 h-20 rounded-full bg-white border-4 border-tertiary shadow-lg flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                        <span class="text-3xl font-bold text-primary">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 mb-2">{{ __('landing.how_it_works.s1_title') }}</h3>
                    <p class="text-zinc-600 text-sm">{{ __('landing.how_it_works.s1_desc') }}</p>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col items-center text-center group mt-8 md:mt-0">
                    <div
                        class="w-20 h-20 rounded-full bg-primary border-4 border-primary text-white shadow-lg flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                        <span class="text-3xl font-bold">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 mb-2">{{ __('landing.how_it_works.s2_title') }}</h3>
                    <p class="text-zinc-600 text-sm">{{ __('landing.how_it_works.s2_desc') }}</p>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col items-center text-center group mt-8 md:mt-0">
                    <div
                        class="w-20 h-20 rounded-full bg-white border-4 border-tertiary shadow-lg flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                        <span class="text-3xl font-bold text-primary">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 mb-2">{{ __('landing.how_it_works.s3_title') }}</h3>
                    <p class="text-zinc-600 text-sm">{{ __('landing.how_it_works.s3_desc') }}</p>
                </div>

                <!-- Step 4 -->
                <div class="flex flex-col items-center text-center group mt-8 md:mt-0">
                    <div
                        class="w-20 h-20 rounded-full bg-primary border-4 border-primary text-white shadow-lg flex items-center justify-center mb-6 transition-transform group-hover:scale-110">
                        <span class="text-3xl font-bold">4</span>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 mb-2">{{ __('landing.how_it_works.s4_title') }}</h3>
                    <p class="text-zinc-600 text-sm">{{ __('landing.how_it_works.s4_desc') }}</p>
                </div>

            </div>
        </div>

    </div>
</div>