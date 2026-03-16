<?php

use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public $billing = 'monthly';

    /**
     * Define pricing plans configuration as a computed property
     */
    #[Computed]
    public function tiers()
    {
        return [
            [
                'id' => 'p1',
                'name' => __('pricing.p1_name'),
                'monthly_price' => __('pricing.p1_monthly'),
                'yearly_price' => __('pricing.p1_yearly'),
                'highlight' => false,
                'type' => 'plan',
                'features' => [
                    __('pricing.p1_f1'),
                    __('pricing.p1_f2'),
                    __('pricing.p1_f3'),
                    __('pricing.p1_f4'),
                ]
            ],
            [
                'id' => 'p2',
                'name' => __('pricing.p2_name'),
                'monthly_price' => __('pricing.p2_monthly'),
                'yearly_price' => __('pricing.p2_yearly'),
                'highlight' => false,
                'type' => 'plan',
                'features' => [
                    __('pricing.p2_f1'),
                    __('pricing.p2_f2'),
                    __('pricing.p2_f3'),
                    __('pricing.p2_f4'),
                    __('pricing.p2_f5'),
                ]
            ],
            [
                'id' => 'p3',
                'name' => __('pricing.p3_name'),
                'monthly_price' => __('pricing.p3_monthly'),
                'yearly_price' => __('pricing.p3_yearly'),
                'highlight' => true,
                'type' => 'plan',
                'features' => [
                    __('pricing.p3_f1'),
                    __('pricing.p3_f2'),
                    __('pricing.p3_f3'),
                    __('pricing.p3_f4'),
                    __('pricing.p3_f5'),
                    __('pricing.p3_f6'),
                ]
            ],
            [
                'id' => 'p4',
                'name' => __('pricing.p4_name'),
                'monthly_price' => __('pricing.p4_monthly'),
                'yearly_price' => __('pricing.p4_yearly'),
                'highlight' => false,
                'type' => 'custom',
                'features' => [
                    __('pricing.p4_f1'),
                    __('pricing.p4_f2'),
                    __('pricing.p4_f3'),
                    __('pricing.p4_f4'),
                    __('pricing.p4_f5'),
                ]
            ],
        ];
    }
};
?>

<div>
    <!-- Optimized Toggle Switch (Pure Alpine for instant feedback) -->
    <div class="flex items-center justify-center gap-6 mb-16" x-data="{ billing: @entangle('billing').live }">
        <span :class="billing === 'monthly' ? 'text-zinc-900 font-bold' : 'text-zinc-500'"
            class="text-sm font-medium transition-colors cursor-pointer" @click="billing = 'monthly'">
            {{ __('pricing.monthly') }}
        </span>

        <button @click="billing = billing === 'monthly' ? 'yearly' : 'monthly'"
            class="relative w-16 h-8 bg-zinc-200 rounded-full p-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary/20 flex items-center shadow-inner"
            :class="billing === 'yearly' ? 'bg-primary' : 'bg-zinc-200'">
            <!-- Logical Properties (start) handle RTL/LTR automatically -->
            <div class="absolute w-6 h-6 bg-white rounded-full shadow-lg transition-all duration-300 pointer-events-none"
                :class="billing === 'yearly' ? 'start-[2.25rem]' : 'start-[0.25rem]'">
            </div>
        </button>

        <div class="flex items-center gap-3 cursor-pointer" @click="billing = 'yearly'">
            <span :class="billing === 'yearly' ? 'text-zinc-900 font-bold' : 'text-zinc-500'"
                class="text-sm font-medium transition-colors">
                {{ __('pricing.yearly') }}
            </span>
            <span
                class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-secondary text-primary shadow-sm border border-secondary uppercase tracking-wider">
                {{ __('pricing.save_label') }}
            </span>
        </div>
    </div>

    <!-- Pricing Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-stretch"
        x-data="{ billing: @entangle('billing') }">
        @foreach($this->tiers as $tier)
            <div wire:key="plan-{{ $tier['id'] }}" @class([
                'relative group flex flex-col p-8 rounded-3xl transition-all duration-300 border focus-within:ring-2 focus-within:ring-primary/50',
                'bg-white border-primary shadow-2xl shadow-primary/10 -translate-y-2' => $tier['highlight'],
                'bg-white border-zinc-100 shadow-xl shadow-zinc-200/50' => !$tier['highlight'],
            ])>

                @if($tier['highlight'])
                    <div
                        class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-white text-[10px] font-bold px-4 py-1 rounded-full uppercase tracking-widest shadow-lg">
                        {{ __('pricing.popular') }}
                    </div>
                @endif

                <div class="mb-8 text-center">
                    <h3 class="text-lg font-bold text-zinc-900 mb-2 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                        {{ $tier['name'] }}
                    </h3>

                    @if($tier['type'] === 'plan')
                        <div class="flex items-baseline justify-center gap-1">
                            <span class="text-4xl font-extrabold text-primary tracking-tight"
                                x-text="billing === 'monthly' ? '{{ $tier['monthly_price'] }}' : '{{ $tier['yearly_price'] }}'">
                                {{ $billing === 'monthly' ? $tier['monthly_price'] : $tier['yearly_price'] }}
                            </span>
                            <span class="text-zinc-400 text-sm font-medium">
                                /<span
                                    x-text="billing === 'monthly' ? '{{ __('pricing.monthly') }}' : '{{ __('pricing.yearly') }}'">{{ $billing === 'monthly' ? __('pricing.monthly') : __('pricing.yearly') }}</span>
                            </span>
                        </div>

                        <!-- Monthly Average Display -->
                        <div x-show="billing === 'yearly'" x-cloak class="mt-2 text-center">
                            @php
                                $yearlyPrice = (int) filter_var($tier['yearly_price'], FILTER_SANITIZE_NUMBER_INT);
                                $monthlyAvg = $yearlyPrice > 0 ? round($yearlyPrice / 12, 1) : 0;
                            @endphp
                            <span
                                class="text-xs text-secondary font-bold bg-secondary/10 px-3 py-1.5 rounded-full inline-block">
                                {{ __('pricing.monthly') }}: ${{ $monthlyAvg }}
                            </span>
                        </div>
                    @else
                        <div class="py-2 text-center text-primary font-bold">
                            <span class="text-2xl font-bold text-zinc-400 italic">
                                {{ $tier['monthly_price'] }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="w-full h-px bg-zinc-100 mb-8"></div>

                <ul class="space-y-4 text-left mb-10 flex-1 {{ app()->getLocale() === 'ar' ? 'text-right' : '' }}">
                    @foreach($tier['features'] as $feature)
                        <li class="flex items-start gap-3">
                            <div class="size-5 rounded-full bg-secondary flex items-center justify-center flex-shrink-0 mt-0.5">
                                <flux:icon icon="check" variant="solid" class="size-3 text-primary" />
                            </div>
                            <span class="text-sm text-zinc-600 leading-tight">
                                {{ $feature }}
                            </span>
                        </li>
                    @endforeach
                </ul>

                <button @class([
                    'w-full py-4 px-6 rounded-2xl font-bold text-sm transition-all duration-300',
                    'bg-primary text-white shadow-xl shadow-primary/20 hover:bg-primary-dark hover:scale-[1.02] active:scale-95' => $tier['highlight'],
                    'bg-zinc-900 text-white shadow-lg hover:bg-black active:scale-95' => $tier['type'] === 'custom',
                    'bg-zinc-50 text-zinc-600 hover:bg-zinc-100 active:scale-95' => !$tier['highlight'] && $tier['type'] !== 'custom',
                ])>
                    {{ __('pricing.subscribe') }}
                </button>

                @if($tier['highlight'])
                    <div class="absolute inset-0 bg-primary/5 rounded-3xl -z-10 pointer-events-none"></div>
                @endif
            </div>
        @endforeach
    </div>
</div>