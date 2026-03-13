<?php

use Livewire\Component;

new class extends Component {
    public array $features = [];

    public function mount()
    {
        $this->features = [
            [
                'num' => '01',
                'icon' => 'fa-solid fa-chalkboard-user',
                'title' => __('landing.features.f1_title'),
                'desc' => __('landing.features.f1_desc'),
                'img' => 'assets/hero.webp',
            ],
            [
                'num' => '02',
                'icon' => 'fa-solid fa-tag',
                'title' => __('landing.features.f2_title'),
                'desc' => __('landing.features.f2_desc'),
                'img' => 'assets/hero.webp',
            ],
            [
                'num' => '03',
                'icon' => 'fa-solid fa-file-invoice',
                'title' => __('landing.features.f3_title'),
                'desc' => __('landing.features.f3_desc'),
                'img' => 'assets/hero.webp',
            ],
            [
                'num' => '04',
                'icon' => 'fa-solid fa-people-arrows',
                'title' => __('landing.features.f4_title'),
                'desc' => __('landing.features.f4_desc'),
                'img' => 'assets/hero.webp',
            ],
            [
                'num' => '05',
                'icon' => 'fa-solid fa-clock-rotate-left',
                'title' => __('landing.features.f5_title'),
                'desc' => __('landing.features.f5_desc'),
                'img' => 'assets/auth.webp',
            ],
            [
                'num' => '06',
                'icon' => 'fa-solid fa-users',
                'title' => __('landing.features.f6_title'),
                'desc' => __('landing.features.f6_desc'),
                'img' => 'assets/auth.webp',
            ],
            [
                'num' => '07',
                'icon' => 'fa-solid fa-venus-mars',
                'title' => __('landing.features.f7_title'),
                'desc' => __('landing.features.f7_desc'),
                'img' => 'assets/auth.webp',
            ],
            [
                'num' => '08',
                'icon' => 'fa-solid fa-award',
                'title' => __('landing.features.f8_title'),
                'desc' => __('landing.features.f8_desc'),
                'img' => 'assets/auth.webp',
            ],
        ];
    }
};
?>

<div class="py-24 bg-surface" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <x-section-heading :title="__('landing.features.heading')" :description="__('landing.features.subheading')" />

        {{-- Features List --}}
        <div class="flex flex-col gap-12 md:gap-16 lg:gap-20">
            @foreach ($features as $index => $feature)
                @php $isEvenRow = $index % 2 === 0; @endphp

                <div
                    class="flex flex-col md:flex-row items-center gap-8 md:gap-12 lg:gap-16 {{ !$isEvenRow ? 'md:flex-row-reverse' : '' }}">

                    {{-- Image Block (fixed size) --}}
                    <div class="w-full md:w-80 lg:w-[480px] flex-shrink-0">
                        <div
                            class="w-full h-56 md:h-72 lg:h-80 rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white group relative">
                            <img src="{{ asset($feature['img']) }}" alt="{{ $feature['title'] }}"
                                class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            {{-- Decorative overlay --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-primary/20 to-transparent"></div>
                        </div>
                    </div>

                    {{-- Content Block --}}
                    <div
                        class="flex-1 flex gap-6 lg:gap-10 items-center {{ !$isEvenRow ? 'md:flex-row-reverse text-end' : '' }}">

                        {{-- Text Content --}}
                        <div class="flex-1 group">
                            <div class="flex items-center gap-4 mb-4 {{ !$isEvenRow ? 'md:flex-row-reverse' : '' }}">
                                <div
                                    class="w-14 h-14 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-primary/20 transition-transform group-hover:scale-110 group-hover:rotate-3">
                                    <i class="{{ $feature['icon'] }} text-2xl"></i>
                                </div>
                                <h3
                                    class="text-xl lg:text-2xl font-black text-primary tracking-tight {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                                    {{ $feature['title'] }}
                                </h3>
                            </div>
                            <p class="text-zinc-600 leading-relaxed text-sm lg:text-base font-medium">
                                {{ $feature['desc'] }}
                            </p>
                        </div>

                        {{-- Number --}}
                        <div class="hidden md:flex flex-shrink-0">
                            <span
                                class="text-7xl lg:text-8xl font-black text-primary opacity-20 group-hover:opacity-100 transition-opacity duration-300 leading-none select-none tracking-tighter">
                                {{ $feature['num'] }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Horizontal line (optional, matching image layout slightly) --}}
                @if (!$loop->last)
                    <div class="w-full h-px bg-zinc-100 md:hidden"></div>
                @endif
            @endforeach
        </div>
    </div>
</div>