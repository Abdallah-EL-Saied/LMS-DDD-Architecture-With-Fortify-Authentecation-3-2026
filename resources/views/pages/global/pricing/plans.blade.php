<?php

use App\Domains\Program\RepositoryInterface\IBundleRepository;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public $billing = 'monthly'; // 'monthly' or 'annual'
    public $currency = 'egp'; // 'egp' or 'usd'

    public function mount()
    {
        // Use detected country from session (middleware)
        $country = session('user_country', 'EG');
        $this->currency = $country === 'EG' ? 'egp' : 'usd';
    }

    /**
     * Get General Bundles for the pricing page.
     */
    #[Computed]
    public function bundles()
    {
        $bundles = app(IBundleRepository::class)->filter(
            filters: ['program_id' => 'general', 'is_active' => true],
            perPage: 3
        );

        $pricingService = app(\App\Infrastructure\Services\Pricing\PricingService::class);
        $country = session('user_country', 'EG');

        // Attach pricing layout to each bundle entity
        foreach ($bundles->getCollection() as $bundle) {
            $bundle->pricing = $pricingService->calculateLayout($bundle, $country);
        }

        return $bundles;
    }
}; ?>
@php
    $firstBundle = $this->bundles->first();
    $discount = $firstBundle ? $firstBundle->pricing['discount_percentage'] : 0;
@endphp

<div x-data="{ billing: @entangle('billing').live, currency: '{{ $currency }}' }">
    <div class="flex flex-col items-center gap-8 mb-16">
        <div class="flex items-center gap-6">
            <span :class="billing === 'monthly' ? 'text-zinc-900 font-bold' : 'text-zinc-500'"
                class="text-sm font-medium transition-colors cursor-pointer" @click="billing = 'monthly'">
                {{ __('pricing.monthly') }}
            </span>

            <button @click="billing = billing === 'monthly' ? 'annual' : 'monthly'"
                class="relative w-16 h-8 bg-zinc-200 rounded-full p-1 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-primary/20 flex items-center shadow-inner"
                :class="billing === 'annual' ? 'bg-primary' : 'bg-zinc-200'">
                <div class="absolute w-6 h-6 bg-white rounded-full shadow-lg transition-all duration-300 pointer-events-none"
                    :class="billing === 'annual' ? 'start-[2.25rem]' : 'start-[0.25rem]'">
                </div>
            </button>

            <div class="flex items-center gap-3 cursor-pointer" @click="billing = 'annual'">
                <span :class="billing === 'annual' ? 'text-zinc-900 font-bold' : 'text-zinc-500'"
                    class="text-sm font-medium transition-colors">
                    {{ __('pricing.yearly') }}
                </span>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-secondary/20 text-primary shadow-sm border border-secondary uppercase tracking-wider">
                    {{ __('pricing.save_prefix') }} {{ $discount }}%
                </span>
            </div>
        </div>
    </div>

    <!-- Pricing Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 items-stretch">
        @foreach($this->bundles as $bundle)
            <div wire:key="bundle-{{ $bundle->id }}" @class([
                'relative flex flex-col p-8 rounded-[32px] transition-all duration-500 border-2',
                'bg-white border-primary shadow-2xl shadow-primary/10 scale-105 z-10' => $bundle->is_best_seller,
                'bg-white border-transparent shadow-xl shadow-zinc-200/50 hover:border-zinc-200' => !$bundle->is_best_seller,
            ])>

                @if($bundle->is_best_seller)
                    <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-white text-xs font-bold px-6 py-1.5 rounded-full shadow-lg">
                        {{ __('pricing.popular') }}
                    </div>
                @endif

                <div class="mb-8 text-center">
                    <h3 class="text-2xl font-black text-zinc-900 mb-4 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                        {{ $bundle->name }}
                    </h3>

                    <div class="flex flex-col items-center">
                        <div class="flex items-baseline justify-center gap-1">
                            <span class="text-5xl font-black text-primary tracking-tight">
                                <span x-show="currency === 'usd'">$</span>
                                <span x-text="billing === 'annual' ? formatNumber({{ json_encode($bundle->pricing) }}.annual.total) : formatNumber({{ json_encode($bundle->pricing) }}.monthly.amount)"></span>
                                <span x-show="currency === 'egp'" class="text-lg font-bold text-primary/70">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</span>
                            </span>
                            <span class="text-zinc-400 text-sm font-medium" x-show="billing === 'monthly'">
                                / {{ __('pricing.monthly_label') }}
                            </span>
                        </div>

                        {{-- Effective Monthly Price (Only for Annual) --}}
                        <div x-show="billing === 'annual'" x-cloak class="mt-2 inline-flex items-center gap-1.5 py-1 px-3 bg-primary/5 rounded-xl border border-primary/10">
                            <span class="text-sm font-black text-secondary">
                                <span x-show="currency === 'usd'">$</span>
                                <span x-text="formatNumber({{ json_encode($bundle->pricing) }}.annual.effective_monthly)"></span>
                                <span x-show="currency === 'egp'" class="text-[10px] font-bold">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</span>
                                <span class="text-[10px] font-bold opacity-60">/{{ __('programs.month') }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="w-full h-px bg-zinc-100 mb-8"></div>

                <ul class="space-y-4 mb-10 flex-1">
                    <li class="flex items-start gap-4 {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                        <div class="size-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0 mt-0.5">
                            <flux:icon icon="check" variant="solid" class="size-4 text-primary" />
                        </div>
                        <span class="text-base text-zinc-700 font-medium">
                            {{ $bundle->sessions_count }} {{ __('pricing.sessions_per_month') }}
                        </span>
                    </li>
                    <li class="flex items-start gap-4 {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                        <div class="size-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0 mt-0.5">
                            <flux:icon icon="check" variant="solid" class="size-4 text-primary" />
                        </div>
                        <span class="text-base text-zinc-700 font-medium">
                            {{ $bundle->duration_minutes }} {{ __('pricing.minutes_label') }}
                        </span>
                    </li>

                    @foreach($bundle->features ?? [] as $feature)
                        <li class="flex items-start gap-4 {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                            <div class="size-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0 mt-0.5">
                                <flux:icon icon="check" variant="solid" class="size-4 text-primary" />
                            </div>
                            <span class="text-base text-zinc-700 font-medium">
                                {{ $feature }}
                            </span>
                        </li>
                    @endforeach
                </ul>

                <button @class([
                    'w-full py-5 px-6 rounded-2xl font-black text-base transition-all duration-300',
                    'bg-primary text-white shadow-xl shadow-primary/20 hover:bg-zinc-900 hover:scale-[1.02] active:scale-95' => $bundle->is_best_seller,
                    'bg-zinc-50 text-zinc-900 hover:bg-zinc-100 active:scale-95' => !$bundle->is_best_seller,
                ])>
                    {{ __('pricing.subscribe') }}
                </button>
            </div>
        @endforeach

        <!-- Custom Package Card -->
        <div class="flex flex-col p-8 rounded-[32px] border-2 border-zinc-100 bg-white shadow-xl shadow-zinc-200/50">
             <div class="mb-8 text-center flex-1">
                <h3 class="text-2xl font-black text-zinc-900 mb-8 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('pricing.custom_package') }}
                </h3>
                <div class="text-4xl font-bold text-zinc-300 italic mb-12">
                     {{ __('pricing.contact_us') }}
                </div>
                
                <ul class="space-y-4 text-start">
                     @foreach(__('pricing.custom_features') as $feat)
                        <li class="flex items-start gap-4 {{ app()->getLocale() === 'ar' ? 'flex-row-reverse' : '' }}">
                            <div class="size-6 rounded-full bg-secondary flex items-center justify-center flex-shrink-0 mt-0.5">
                                <flux:icon icon="check" variant="solid" class="size-4 text-primary" />
                            </div>
                            <span class="text-base text-zinc-700 font-medium">{{ $feat }}</span>
                        </li>
                     @endforeach
                </ul>
             </div>

             <button class="w-full py-5 px-6 rounded-2xl font-black text-base bg-zinc-900 text-white transition-all duration-300 hover:bg-black active:scale-95 mt-auto">
                 {{ __('pricing.get_started') }}
             </button>
        </div>
    </div>
</div>