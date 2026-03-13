<?php

use Livewire\Component;

new class extends Component {
    public array $plans = [];

    public function mount()
    {
        $this->plans = [
            [
                'name' => __('landing.pricing.p1_name'),
                'price' => __('landing.pricing.p1_price'),
                'period' => __('landing.pricing.period'),
                'features' => [
                    __('landing.pricing.p1_f1'),
                    __('landing.pricing.p1_f2'),
                    __('landing.pricing.p1_f3'),
                    __('landing.pricing.p1_f4'),
                ],
                'popular' => false,
            ],
            [
                'name' => __('landing.pricing.p2_name'),
                'price' => __('landing.pricing.p2_price'),
                'period' => __('landing.pricing.period'),
                'features' => [
                    __('landing.pricing.p2_f1'),
                    __('landing.pricing.p2_f2'),
                    __('landing.pricing.p2_f3'),
                    __('landing.pricing.p2_f4'),
                    __('landing.pricing.p2_f5'),
                ],
                'popular' => true,
            ],
            [
                'name' => __('landing.pricing.p3_name'),
                'price' => __('landing.pricing.p3_price'),
                'period' => __('landing.pricing.period'),
                'features' => [
                    __('landing.pricing.p3_f1'),
                    __('landing.pricing.p3_f2'),
                    __('landing.pricing.p3_f3'),
                    __('landing.pricing.p3_f4'),
                    __('landing.pricing.p3_f5'),
                    __('landing.pricing.p3_f6'),
                ],
                'popular' => false,
            ],
        ];
    }
};
?>

<div class="py-24 bg-white" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <x-section-heading :title="__('landing.pricing.heading')" :description="__('landing.pricing.subheading')"
            show-line />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-start">
            @foreach ($plans as $plan)
                <div
                    class="relative bg-surface rounded-3xl p-8 border-2 {{ $plan['popular'] ? 'border-primary shadow-2xl scale-105 z-10' : 'border-zinc-100 shadow-lg' }} transition-all duration-300">

                    @if ($plan['popular'])
                        <div
                            class="absolute -top-5 left-1/2 -translate-x-1/2 bg-tertiary text-primary text-xs font-black px-6 py-2 rounded-full uppercase tracking-wider {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                            {{ __('landing.pricing.popular') }}
                        </div>
                    @endif

                    <div class="mb-8">
                        <h3
                            class="text-xl font-bold text-zinc-900 mb-4 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                            {{ $plan['name'] }}
                        </h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl md:text-5xl font-black text-primary">{{ $plan['price'] }}</span>
                            <span class="text-zinc-500 font-medium">/ {{ $plan['period'] }}</span>
                        </div>
                    </div>

                    <ul class="space-y-4 mb-10">
                        @foreach ($plan['features'] as $feature)
                            <li class="flex items-start gap-3">
                                <div
                                    class="w-5 h-5 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-check text-[10px] text-primary"></i>
                                </div>
                                <span class="text-zinc-600 text-sm font-medium leading-tight">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <flux:button variant="{{ $plan['popular'] ? 'primary' : 'subtle' }}"
                        class="w-full py-4 rounded-2xl font-black transition-all active:scale-95 text-lg {{ $plan['popular'] ? 'bg-primary text-white hover:bg-primary-400' : 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200' }} {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                        {{ __('landing.pricing.subscribe') }}
                    </flux:button>
                </div>
            @endforeach
        </div>
    </div>
</div>