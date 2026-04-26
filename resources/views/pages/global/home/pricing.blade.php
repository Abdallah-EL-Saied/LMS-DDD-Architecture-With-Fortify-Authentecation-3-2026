<?php

use App\Domains\Program\RepositoryInterface\IBundleRepository;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    public string $currency = 'egp';

    public function mount()
    {
        $country = session('user_country', 'EG');
        $this->currency = $country === 'EG' ? 'egp' : 'usd';
    }

    #[Computed]
    public function bundles()
    {
        return app(IBundleRepository::class)->filter(
            filters: ['program_id' => 'general', 'is_active' => true],
            perPage: 3
        );
    }
};
?>

<div class="py-24 bg-white" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <x-section-heading :title="__('pricing.heading')" :description="__('pricing.subheading')" show-line />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-start">
            @foreach ($this->bundles as $bundle)
                <div @class([
                    'relative bg-surface rounded-3xl p-8 border-2 transition-all duration-300',
                    'border-primary shadow-2xl scale-105 z-10' => $bundle->is_best_seller,
                    'border-zinc-100 shadow-lg' => !$bundle->is_best_seller,
                ])>

                    @if ($bundle->is_best_seller)
                        <div
                            class="absolute -top-5 left-1/2 -translate-x-1/2 bg-tertiary text-primary text-xs font-black px-6 py-2 rounded-full uppercase tracking-wider {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                            {{ __('pricing.popular') }}
                        </div>
                    @endif

                    <div class="mb-8">
                        <h3
                            class="text-xl font-bold text-zinc-900 mb-4 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                            {{ $bundle->name }}
                        </h3>
                        <div class="flex items-baseline gap-1">
                            <span class="text-4xl md:text-5xl font-black text-primary">
                                {{ $currency === 'egp' ? number_format($bundle->monthly_price_egp) . ' ج.م' : '$' . number_format($bundle->monthly_price_usd) }}
                            </span>
                            <span class="text-zinc-500 font-medium">/ {{ __('pricing.monthly') }}</span>
                        </div>
                    </div>

                    <ul class="space-y-4 mb-10">
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-check text-[10px] text-primary"></i>
                            </div>
                            <span class="text-zinc-600 text-sm font-medium leading-tight">{{ $bundle->sessions_count }} {{ __('pricing.sessions_per_month') }}</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-5 h-5 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-check text-[10px] text-primary"></i>
                            </div>
                            <span class="text-zinc-600 text-sm font-medium leading-tight">{{ $bundle->duration_minutes }} {{ __('pricing.minutes_label') }}</span>
                        </li>
                        @foreach ($bundle->features ?? [] as $feature)
                            <li class="flex items-start gap-3">
                                <div
                                    class="w-5 h-5 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <i class="fa-solid fa-check text-[10px] text-primary"></i>
                                </div>
                                <span class="text-zinc-600 text-sm font-medium leading-tight">{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <flux:button variant="{{ $bundle->is_best_seller ? 'primary' : 'subtle' }}"
                        class="w-full py-4 rounded-2xl font-black transition-all active:scale-95 text-lg {{ $bundle->is_best_seller ? 'bg-primary text-white hover:bg-primary-400' : 'bg-zinc-100 text-zinc-900 hover:bg-zinc-200' }} {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                        {{ __('pricing.subscribe') }}
                    </flux:button>
                </div>
            @endforeach
        </div>
    </div>
</div>