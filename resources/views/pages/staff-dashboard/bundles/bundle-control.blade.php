<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domains\Program\RepositoryInterface\IBundleRepository;
use App\Domains\Program\RepositoryInterface\IProgramRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component {
    public ?Bundle $bundle = null;

    // Form data
    #[Livewire\Attributes\Url]
    public ?int $programId = null;
    public array $name = ['ar' => '', 'en' => ''];
    public int $durationMinutes = 60;
    public int $sessionsCount = 12;
    public array $features = [
        'ar' => ['', '', ''],
        'en' => ['', '', '']
    ];
    public float $monthlyPriceEgp = 0;
    public float $monthlyPriceUsd = 0;
    public float $globalDiscount = 0;
    public bool $isBestSeller = false;
    public bool $isActive = true;
    public int $order = 0;
    public string $bestSellerMode = 'manual';

    public function rendering($view)
    {
        $view->title($this->bundle ? ($this->name['ar'] ?: 'Edit Plan') : 'Create New Plan');
    }

    public function mount(?Bundle $bundle = null): void
    {
        if ($bundle && $bundle->exists) {
            $this->bundle = $bundle;
            $this->programId = $bundle->program_id;
            $this->name = $bundle->getTranslations('name');
            $this->durationMinutes = $bundle->duration_minutes;
            $this->sessionsCount = $bundle->sessions_count;
            
            $rawFeatures = $bundle->getTranslations('features');
            foreach (['ar', 'en'] as $lang) {
                $val = $rawFeatures[$lang] ?? [];
                if (is_string($val)) {
                    $this->features[$lang] = array_values(array_filter(explode("\n", str_replace("\r", "", $val))));
                } else {
                    $this->features[$lang] = array_values($val ?: []);
                }
                
                if (empty($this->features[$lang])) {
                    $this->features[$lang] = [''];
                }
            }

            $this->monthlyPriceEgp = $bundle->monthly_price_egp;
            $this->monthlyPriceUsd = $bundle->monthly_price_usd;
            $this->isBestSeller = $bundle->is_best_seller;
            $this->isActive = $bundle->is_active;
            $this->order = $bundle->order;
        }
        $this->bestSellerMode = Setting::getValue('best_seller_mode', 'manual');
        $this->globalDiscount = (float) Setting::getValue('global_annual_discount', 0);
    }

    public function save(): void
    {
        $this->validate([
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'durationMinutes' => 'required|integer|min:1',
            'sessionsCount' => 'required|integer|min:1',
            'monthlyPriceEgp' => 'required|numeric|min:0',
            'monthlyPriceUsd' => 'required|numeric|min:0',
        ]);

        $cleanedFeatures = [];
        foreach (['ar', 'en'] as $lang) {
            $cleanedFeatures[$lang] = array_values(array_filter($this->features[$lang] ?? []));
        }

        $data = [
            'program_id' => $this->programId,
            'name' => $this->name,
            'duration_minutes' => $this->durationMinutes,
            'sessions_count' => $this->sessionsCount,
            'features' => $cleanedFeatures,
            'monthly_price_egp' => $this->monthlyPriceEgp,
            'monthly_price_usd' => $this->monthlyPriceUsd,
            'is_best_seller' => $this->isBestSeller,
            'is_active' => $this->isActive,
            'order' => $this->order,
        ];

        if ($this->isBestSeller && $this->bestSellerMode === 'manual') {
            app(IBundleRepository::class)->clearBestSellers();
        } elseif ($this->bestSellerMode === 'auto') {
            $data['is_best_seller'] = $this->bundle?->is_best_seller ?? false;
        }

        if ($this->bundle && $this->bundle->exists) {
            app(IBundleRepository::class)->update($this->bundle->id, $data);
            $this->dispatch('toast', variant: 'success', heading: 'Updated', message: 'Plan updated successfully');
        } else {
            app(IBundleRepository::class)->create($data);
            $this->dispatch('toast', variant: 'success', heading: 'Created', message: 'New subscription plan created');
        }

        $this->redirect(route('bundles.management'), navigate: true);
    }


    public function addFeatureField($lang): void
    {
        $this->features[$lang][] = '';
    }

    public function removeFeatureField($lang, $index): void
    {
        unset($this->features[$lang][$index]);
        $this->features[$lang] = array_values($this->features[$lang]);
    }

    public function getProgramsProperty()
    {
        return app(IProgramRepository::class)->all();
    }
}; ?>

<div class="p-6 scrollbar-thin overflow-y-auto h-full w-full bg-zinc-50 dark:bg-zinc-950">
    <div class="">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
            <x-dashboard-page-header 
                :title="$bundle ? __('staff-dashboard/bundles.modal.edit_title') : __('staff-dashboard/bundles.modal.create_title')"
                :breadcrumbs="[
                    __('global.sidebar.dashboard') => route('dashboard'), 
                    __('staff-dashboard/bundles.management_title') => route('bundles.management'), 
                    $bundle ? __('staff-dashboard/bundles.edit') : __('staff-dashboard/bundles.add_plan')
                ]" 
            />
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('bundles.management') }}" wire:navigate class="w-full md:w-auto">
                {{ __('staff-dashboard/bundles.modal.cancel') }}
            </flux:button>
        </div>

        <div class="space-y-8">
            {{-- Basic Identity --}}
            <flux:card class="p-8">
                <div class="flex items-center gap-3 mb-8">
                    <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <flux:icon icon="identification" size="sm" class="text-primary" />
                    </div>
                    <div>
                        <flux:heading size="lg">{{ __('staff-dashboard/bundles.modal.subtitle') }}</flux:heading>
                        <flux:subheading>Define the identity and target of this subscription plan.</flux:subheading>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <flux:field>
                        <flux:label>{{ __('staff-dashboard/bundles.modal.name_ar') }}</flux:label>
                        <flux:input wire:model="name.ar" placeholder="e.g. الخطة القياسية" />
                    </flux:field>
                    <flux:field>
                        <flux:label>{{ __('staff-dashboard/bundles.modal.name_en') }}</flux:label>
                        <flux:input wire:model="name.en" placeholder="e.g. Standard Plan" />
                    </flux:field>

                    @if($programId)
                        <flux:field class="md:col-span-2">
                            <div class="p-4 bg-primary/5 rounded-xl border border-primary/20 flex items-center gap-3">
                                <flux:icon icon="link" class="size-5 text-primary" />
                                <div>
                                    <h4 class="font-bold text-sm text-zinc-900">Linked to Program</h4>
                                    <p class="text-xs text-zinc-500">This bundle is exclusively tied to a specific program and cannot be changed here.</p>
                                </div>
                            </div>
                        </flux:field>
                    @endif
                </div>
            </flux:card>

            {{-- Sessions & Features --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-1 space-y-8">
                    <flux:card class="p-8">
                        <flux:heading size="sm" class="mb-6">Session Details</flux:heading>
                        <div class="space-y-6">
                            <flux:field>
                                <flux:label>{{ __('staff-dashboard/bundles.modal.duration_minutes') }}</flux:label>
                                <flux:input type="number" wire:model.live="durationMinutes" icon="clock" suffix="min" />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('staff-dashboard/bundles.modal.sessions_per_month') }}</flux:label>
                                <flux:input type="number" wire:model.live="sessionsCount" icon="hashtag" />
                            </flux:field>
                            <flux:field>
                                <flux:label>{{ __('staff-dashboard/bundles.modal.order') }}</flux:label>
                                <flux:input type="number" wire:model.live="order" icon="bars-3" />
                            </flux:field>
                        </div>
                    </flux:card>

                    <flux:card class="p-8 bg-zinc-900 border-zinc-800 text-white">
                        <flux:heading size="sm" class="mb-6 text-white">Visibility & Status</flux:heading>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between p-4 bg-zinc-800/50 rounded-2xl border border-zinc-700">
                                <div class="flex flex-col gap-1">
                                    <flux:label class="text-white">Active Status</flux:label>
                                    <flux:text size="xs" color="subtle">Visible to potential students</flux:text>
                                </div>
                                <flux:switch wire:model="isActive" color="emerald" />
                            </div>
                            <div class="flex items-center justify-between p-4 bg-zinc-800/50 rounded-2xl border border-zinc-700">
                                <div class="flex flex-col gap-1">
                                    <flux:label class="text-white">Best Seller Badge</flux:label>
                                    <flux:text size="xs" color="subtle">Highlighted on the pricing page</flux:text>
                                </div>
                                <flux:switch wire:model="isBestSeller" color="amber" :disabled="$bestSellerMode === 'auto'" />
                            </div>
                            @if($bestSellerMode === 'auto')
                                <flux:text size="xs" color="primary" class="mt-2 text-center block italic">
                                    {{ __('staff-dashboard/bundles.strategy_auto_desc') }}
                                </flux:text>
                            @endif
                        </div>
                    </flux:card>
                </div>

                <div class="lg:col-span-2">
                    <flux:card class="p-8 h-full">
                        <flux:heading size="sm" class="mb-6">{{ __('staff-dashboard/bundles.included_features_title') }}</flux:heading>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- AR Features --}}
                            <div class="space-y-4">
                                <flux:label class="flex items-center gap-2">
                                    <flux:icon icon="language" size="xs" class="text-zinc-400" />
                                    Arabic Points
                                </flux:label>
                                <div class="space-y-3">
                                    @foreach($features['ar'] as $idx => $feature)
                                        <div class="flex gap-2" wire:key="feat-ar-{{ $idx }}">
                                            <flux:input wire:model="features.ar.{{ $idx }}" class="flex-1" />
                                            <flux:button variant="ghost" size="xs" icon="trash" class="text-red-500" wire:click="removeFeatureField('ar', {{ $idx }})" square />
                                        </div>
                                    @endforeach
                                    <flux:button variant="subtle" size="xs" icon="plus" wire:click="addFeatureField('ar')" class="w-full">Add Feature (AR)</flux:button>
                                </div>
                            </div>
                            {{-- EN Features --}}
                            <div class="space-y-4">
                                <flux:label class="flex items-center gap-2">
                                    <flux:icon icon="language" size="xs" class="text-zinc-400" />
                                    English Points
                                </flux:label>
                                <div class="space-y-3">
                                    @foreach($features['en'] as $idx => $feature)
                                        <div class="flex gap-2" wire:key="feat-en-{{ $idx }}">
                                            <flux:input wire:model="features.en.{{ $idx }}" class="flex-1" />
                                            <flux:button variant="ghost" size="xs" icon="trash" class="text-red-500" wire:click="removeFeatureField('en', {{ $idx }})" square />
                                        </div>
                                    @endforeach
                                    <flux:button variant="subtle" size="xs" icon="plus" wire:click="addFeatureField('en')" class="w-full">Add Feature (EN)</flux:button>
                                </div>
                            </div>
                        </div>
                    </flux:card>
                </div>
            </div>

            {{-- Pricing Section --}}
            <flux:card class="p-8 border-2 border-primary/10">
                <div class="flex items-center gap-3 mb-8">
                    <div class="size-10 rounded-xl bg-primary/10 flex items-center justify-center">
                        <flux:icon icon="banknotes" size="sm" class="text-primary" />
                    </div>
                    <flux:heading size="lg">Pricing Configuration</flux:heading>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                    <flux:field>
                        <flux:label>{{ __('staff-dashboard/bundles.modal.egypt_price') }}</flux:label>
                        <flux:input type="number" step="0.01" wire:model.live="monthlyPriceEgp" icon="banknotes" suffix="EGP" />
                    </flux:field>
                    <flux:field>
                        <flux:label>{{ __('staff-dashboard/bundles.modal.international_price') }}</flux:label>
                        <flux:input type="number" step="0.01" wire:model.live="monthlyPriceUsd" icon="currency-dollar" prefix="$" />
                    </flux:field>
                    <flux:field class="md:col-span-1">
                        <!-- Removed Annual Discount as it's globally managed -->
                    </flux:field>
                </div>

                @if($monthlyPriceEgp > 0 || $monthlyPriceUsd > 0)
                <div class="p-6 rounded-3xl bg-zinc-900 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-10">
                        <flux:icon icon="presentation-chart-line" class="size-32 text-white" />
                    </div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                        <div class="space-y-2">
                            <flux:text size="xs" class="text-primary uppercase font-black tracking-[0.2em]">Live Pricing Preview</flux:text>
                            <flux:heading size="xl" class="text-white">
                                {{ number_format($monthlyPriceEgp * 12 * (1 - ($globalDiscount / 100)), 2) }} <span class="text-sm font-normal text-zinc-400">EGP / Year</span>
                            </flux:heading>
                        </div>
                        <div class="h-12 w-px bg-zinc-800 hidden md:block"></div>
                        <div class="space-y-2 text-right">
                            <flux:text size="xs" class="text-amber-400 uppercase font-black tracking-[0.2em]">International Estimate</flux:text>
                            <flux:heading size="xl" class="text-white">
                                ${{ number_format($monthlyPriceUsd * 12 * (1 - ($globalDiscount / 100)), 2) }} <span class="text-sm font-normal text-zinc-400">USD / Year</span>
                            </flux:heading>
                        </div>
                    </div>
                </div>
                @endif
            </flux:card>

            {{-- Save Bar --}}
            <div class="flex flex-col-reverse md:flex-row md:items-center justify-end gap-4 py-8 border-t border-zinc-200">
                <flux:button variant="ghost" href="{{ route('bundles.management') }}" wire:navigate class="w-full md:w-auto">{{ __('staff-dashboard/bundles.modal.cancel') }}</flux:button>
                <flux:button variant="primary" icon="check" wire:click="save" class="px-12 w-full md:w-auto">
                    {{ $bundle ? 'Update Plan' : 'Create Subscription' }}
                </flux:button>
            </div>
        </div>
    </div>
</div>
