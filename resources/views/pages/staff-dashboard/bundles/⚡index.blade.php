<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domains\Program\RepositoryInterface\IBundleRepository;
use App\Domains\Program\RepositoryInterface\IProgramRepository;
use App\Infrastructure\Persistence\Eloquent\Models\Setting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public function rendering($view)
    {
        $view->title(__('staff-dashboard/bundles.management_title'));
    }

    #[Url]
    public string $search = '';

    public int $perPage = 10;
    public bool $showSettings = false;
    public ?int $expandedId = null;

    // Global settings
    public float $globalAnnualDiscount = 0;
    public float $hourlyRateEgp = 0;
    public float $hourlyRateUsd = 0;

    // Best seller mode: 'auto' or 'manual'
    public string $bestSellerMode = 'manual';

    public function mount(): void
    {
        $this->bestSellerMode = Setting::getValue('best_seller_mode', 'manual');
        $this->globalAnnualDiscount = (float) Setting::getValue('global_annual_discount', 0);
        $this->hourlyRateEgp = (float) Setting::getValue('hourly_rate_egp', 0);
        $this->hourlyRateUsd = (float) Setting::getValue('hourly_rate_usd', 0);
    }

    public function toggleBestSellerMode(): void
    {
        $this->bestSellerMode = $this->bestSellerMode === 'auto' ? 'manual' : 'auto';
        Setting::setValue('best_seller_mode', $this->bestSellerMode);

        if ($this->bestSellerMode === 'auto') {
            $repo = app(IBundleRepository::class);
            $topBundleId = $repo->getTopSellingBundleId();
            $repo->markAsBestSeller($topBundleId);
            $this->dispatch('toast', variant: 'success', heading: __('staff-dashboard/bundles.auto'), message: __('staff-dashboard/bundles.toast.updated'));
        } else {
            $this->dispatch('toast', variant: 'info', heading: __('staff-dashboard/bundles.manual'), message: __('staff-dashboard/bundles.toast.updated'));
        }
    }

    public function saveGlobalSettings(): void
    {
        Setting::setValue('global_annual_discount', $this->globalAnnualDiscount);
        Setting::setValue('hourly_rate_egp', $this->hourlyRateEgp);
        Setting::setValue('hourly_rate_usd', $this->hourlyRateUsd);

        $this->dispatch('toast', variant: 'success', heading: __('global.success'), message: __('staff-dashboard/bundles.toast.updated'));
    }

    #[Computed]
    public function bundles()
    {
        $filters = [];
        if ($this->search) {
            $filters['search'] = $this->search;
        }

        return app(IBundleRepository::class)->filter(
            filters: $filters,
            perPage: $this->perPage
        );
    }

    public function toggleAccordion(int $id): void
    {
        if ($this->expandedId === $id) {
            $this->expandedId = null;
        } else {
            $this->expandedId = $id;
        }
    }

    public function markAsBestSeller(int $id): void
    {
        if ($this->bestSellerMode === 'auto') {
            $this->dispatch('toast', variant: 'warning', heading: __('staff-dashboard/bundles.auto'), message: 'Manual selection is disabled in Auto mode.');
            return;
        }

        $bundle = app(IBundleRepository::class)->findById($id);
        if (!$bundle) return;

        app(IBundleRepository::class)->markAsBestSeller($id);

        $this->dispatch('toast', variant: 'success', heading: __('global.success'), message: __('staff-dashboard/bundles.toast.updated'));
    }


    public function delete(int $id): void
    {
        $bundle = app(IBundleRepository::class)->findById($id);
        if ($bundle && $bundle->program_id) {
            $this->dispatch('toast', variant: 'danger', heading: __('global.error'), message: 'Cannot delete a bundle linked to a program.');
            return;
        }

        app(IBundleRepository::class)->delete($id);
        $this->dispatch('toast', variant: 'success', heading: __('global.success'), message: __('staff-dashboard/bundles.toast.deleted'));
    }
}; ?>

<div class="space-y-6 flex h-full w-full overflow-hidden bg-zinc-50 dark:bg-zinc-950">
    <div class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-thin">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            @php
                $pageTitle = __('staff-dashboard/bundles.management_title');
                $breadcrumbs = [__('global.sidebar.dashboard') => route('dashboard'), $pageTitle];
            @endphp
            <x-dashboard-page-header :title="$pageTitle" :breadcrumbs="$breadcrumbs">
                <flux:badge variant="primary" size="sm" inset="top bottom">{{ $this->bundles->total() }}</flux:badge>
            </x-dashboard-page-header>
        </div>

        {{-- Top Bar & Action Card --}}
        <div class="space-y-6 mb-8">
            <flux:card class="flex flex-col md:flex-row items-center justify-between gap-6 p-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800">
                <div class="flex-1 w-full">
                    <flux:input wire:model.live="search" class="!bg-zinc-50 dark:!bg-zinc-800/50 !border-transparent" :placeholder="__('staff-dashboard/bundles.search_placeholder')" icon="magnifying-glass" />
                </div>
                <div class="flex flex-col md:flex-row items-center gap-6 w-full md:w-auto">
                    <flux:button variant="outline" icon="cog-6-tooth" wire:click="$toggle('showSettings')" class="w-full">
                        {{ __('staff-dashboard/bundles.system_settings') }}
                    </flux:button>
                    <flux:button variant="primary" icon="plus" href="{{ route('bundles.control') }}" wire:navigate class="w-full">
                        {{ __('staff-dashboard/bundles.add_plan') }}
                    </flux:button>
                </div>
            </flux:card>

            {{-- System Settings Unified Card --}}
            @if($showSettings)
                <flux:card class="p-8 border-2 dark:!border-secondary !border-primary/50 animate-in slide-in-from-top-4 duration-500 overflow-hidden relative z-5">
                    <div class="absolute top-0 right-0 p-12 opacity-[0.03] pointer-events-none">
                        <flux:icon icon="bolt" class="size-64" />
                    </div>

                    <div class="relative z-10 flex flex-col xl:flex-row gap-12">
                        {{-- Pricing Rules --}}
                        <div class="flex-1 space-y-6">
                            <div class="flex items-center gap-3">
                                <div class="size-10 flex items-center justify-center">
                                    <flux:icon icon="banknotes" size="sm" class="text-secondary" />
                                </div>
                                <flux:heading size="sm">{{ __('staff-dashboard/bundles.global_pricing_rules') }}</flux:heading>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <flux:field>
                                    <flux:label class="text-[10px] uppercase font-black tracking-widest text-zinc-500 mb-1">{{ __('staff-dashboard/bundles.unified_discount') }}</flux:label>
                                    <flux:input type="number" wire:model="globalAnnualDiscount" suffix="%" class="!rounded-xl" />
                                </flux:field>
                                <flux:field>
                                    <flux:label class="text-[10px] uppercase font-black tracking-widest text-zinc-500 mb-1">{{ __('staff-dashboard/bundles.hourly_rate_egp') }}</flux:label>
                                    <flux:input type="number" wire:model="hourlyRateEgp" class="!rounded-xl" />
                                </flux:field>
                                <flux:field>
                                    <flux:label class="text-[10px] uppercase font-black tracking-widest text-zinc-500 mb-1">{{ __('staff-dashboard/bundles.hourly_rate_usd') }}</flux:label>
                                    <flux:input type="number" wire:model="hourlyRateUsd" prefix="$" class="!rounded-xl" />
                                </flux:field>
                                <div class="flex items-end">
                                    <flux:button variant="primary" class="w-full !rounded-xl" icon="check" wire:click="saveGlobalSettings">
                                        {{ __('staff-dashboard/bundles.update_pricing_btn') }}
                                    </flux:button>
                                </div>
                            </div>
                        </div>

                        <flux:separator vertical class="hidden xl:block" />

                        {{-- Promotion Strategy --}}
                        <div class="xl:w-1/3 space-y-6">
                            <div class="flex items-center gap-3">
                                <div class="size-10 flex items-center justify-center">
                                    <flux:icon icon="rocket-launch" size="sm" class="text-secondary" />
                                </div>
                                <flux:heading size="sm">{{ __('staff-dashboard/bundles.promotion_strategy') }}</flux:heading>
                            </div>

                            <div class="p-6 rounded-3xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-sm space-y-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col gap-1">
                                        <flux:text size="xs" color="subtle">{{ __('staff-dashboard/bundles.how_it_chosen') }}</flux:text>
                                    </div>
                                    <div class="flex items-center gap-2 p-1 bg-zinc-100 dark:bg-zinc-900 rounded-full border border-zinc-200 dark:border-zinc-700">
                                        <button type="button" 
                                            wire:click="toggleBestSellerMode"
                                            class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all {{ $bestSellerMode === 'manual' ? 'bg-primary dark:bg-secondary text-white dark:text-primary shadow-lg dark:shadow-secondary/20 font-bold' : 'text-zinc-400 hover:text-zinc-600' }}">
                                            {{ __('staff-dashboard/bundles.manual') }}
                                        </button>
                                        <button type="button" 
                                            wire:click="toggleBestSellerMode"
                                            class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all {{ $bestSellerMode === 'auto' ? 'bg-primary dark:bg-secondary text-white dark:text-primary shadow-lg dark:shadow-secondary/20 font-bold' : 'text-zinc-400 hover:text-zinc-600' }}">
                                            {{ __('staff-dashboard/bundles.auto') }}
                                        </button>
                                    </div>
                                </div>

                                <flux:card class="p-4 bg-zinc-50 dark:bg-zinc-900/50 rounded-2xl border border-zinc-100 dark:border-zinc-800">
                                    <flux:text size="xs" class="leading-relaxed text-primary dark:text-secondary italic text-center block">
                                        {{ $bestSellerMode === 'auto' ? __('staff-dashboard/bundles.strategy_auto_desc') : __('staff-dashboard/bundles.strategy_manual_desc') }}
                                    </flux:text>
                                </flux:card>
                            </div>
                        </div>
                    </div>
                </flux:card>
            @endif
        </div>

        {{-- Bundle List --}}
        <flux:card class="p-0 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 overflow-hidden mb-6 shadow-sm">
            <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                @forelse ($this->bundles as $bundle)
                    <div class="flex flex-col @if($expandedId === $bundle->id) bg-primary/[0.02] dark:bg-secondary/[0.02] @endif">
                        {{-- Row Header --}}
                        <div class="px-6 py-5 flex items-center justify-between cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors gap-4"
                             wire:click="toggleAccordion({{ $bundle->id }})">
                            
                            <div class="flex items-center gap-4 flex-1 min-w-0">
                                <div class="size-12 rounded-2xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 border border-zinc-200 dark:border-zinc-700">
                                    <flux:icon icon="banknotes" size="sm" class="text-zinc-500" />
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <div class="flex items-center gap-2">
                                        <flux:text class="font-bold truncate text-zinc-900 dark:text-white">{{ $bundle->name }}</flux:text>
                                        <button type="button" 
                                            @if($bestSellerMode === 'manual') wire:click.stop="markAsBestSeller({{ $bundle->id }})" @endif
                                            class="transition-all duration-300 {{ $bundle->is_best_seller ? 'text-amber-400 scale-110 drop-shadow-[0_0_8px_rgba(251,191,36,0.5)]' : 'text-zinc-300 hover:text-amber-200 hover:scale-105' }} {{ $bestSellerMode === 'auto' ? 'opacity-50 cursor-not-allowed' : '' }}"
                                            title="{{ $bestSellerMode === 'auto' ? 'Automatic Mode Enabled' : ($bundle->is_best_seller ? __('staff-dashboard/bundles.best_seller_label') : __('staff-dashboard/bundles.manual')) }}">
                                            <flux:icon icon="star" variant="{{ $bundle->is_best_seller ? 'solid' : 'outline' }}" class="size-4" />
                                        </button>
                                        @if($bundle->is_best_seller)
                                            <flux:badge size="sm" color="amber" class="!rounded-md lowercase text-[9px] font-black px-1.5 py-0">BEST</flux:badge>
                                        @endif
                                    </div>
                                    <flux:text variant="subtle" size="xs" class="truncate font-medium">
                                        {{ $bundle->sessions_count }} {{ __('staff-dashboard/bundles.sessions') }} / {{ $bundle->duration_minutes }}m @if($bundle->program) • {{ __('staff-dashboard/bundles.linked_to') }} {{ $bundle->program->title }} @endif
                                    </flux:text>
                                </div>
                            </div>

                            <div class="flex items-center gap-6 shrink-0">
                                <div class="hidden md:flex flex-col items-end">
                                    <flux:text size="sm" font="bold" class="text-zinc-900 dark:text-white">{{ number_format($bundle->monthly_price_egp) }} EGP</flux:text>
                                    <flux:text size="xs" color="subtle">${{ number_format($bundle->monthly_price_usd, 2) }} USD</flux:text>
                                </div>
                                
                                <flux:badge size="sm" :color="$bundle->is_active ? 'emerald' : 'zinc'" class="!rounded-lg uppercase text-[10px] font-black tracking-widest px-3">
                                    {{ $bundle->is_active ? __('staff-dashboard/bundles.active') : __('staff-dashboard/bundles.inactive') }}
                                </flux:badge>
                                
                                <flux:button variant="ghost" :icon="$expandedId === $bundle->id ? 'chevron-up' : 'chevron-down'" size="sm" square />
                            </div>
                        </div>

                        {{-- Expanded Content --}}
                        @if($expandedId === $bundle->id)
                            <div class="p-8 bg-zinc-50/50 dark:bg-zinc-900/50 border-t border-zinc-100 dark:border-zinc-800 animate-in slide-in-from-top-2 duration-300">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                                    {{-- Info Side --}}
                                    <div class="space-y-8">
                                        <div class="grid grid-cols-2 gap-8">
                                            <div>
                                                <flux:label size="sm" class="mb-1 text-zinc-400 uppercase text-[9px] font-black tracking-widest">{{ __('staff-dashboard/bundles.duration_scale') }}</flux:label>
                                                <flux:text class="font-bold text-zinc-900 dark:text-white">{{ $bundle->sessions_count }} {{ __('staff-dashboard/bundles.sessions') }} @ {{ $bundle->duration_minutes }} {{ __('staff-dashboard/bundles.duration') }}</flux:text>
                                            </div>
                                            <div>
                                                <flux:label size="sm" class="mb-1 text-zinc-400 uppercase text-[9px] font-black tracking-widest">{{ __('staff-dashboard/bundles.annual_savings') }}</flux:label>
                                                <div class="flex items-center gap-2">
                                                    <flux:badge color="blue" size="sm" class="!rounded-md">{{ $bundle->annual_discount_percentage }}% {{ __('staff-dashboard/bundles.off') }}</flux:badge>
                                                    <flux:text size="xs" color="subtle">{{ __('staff-dashboard/bundles.on_annual_plan') }}</flux:text>
                                                </div>
                                            </div>
                                        </div>

                                        <div>
                                            <flux:label size="sm" class="mb-4 text-zinc-400 uppercase text-[9px] font-black tracking-widest">{{ __('staff-dashboard/bundles.included_features_title') }}</flux:label>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                                @php 
                                                    $rawFeats = $bundle->getTranslations('features'); 
                                                    $arFeats = is_string($rawFeats['ar'] ?? '') ? array_filter(explode("\n", str_replace("\r", "", $rawFeats['ar']))) : ($rawFeats['ar'] ?? []);
                                                    $enFeats = is_string($rawFeats['en'] ?? '') ? array_filter(explode("\n", str_replace("\r", "", $rawFeats['en']))) : ($rawFeats['en'] ?? []);
                                                @endphp
                                                <div class="space-y-3">
                                                    <flux:text size="xs" font="bold" class="text-zinc-500 flex items-center gap-2">
                                                        <flux:icon icon="language" size="xs" />
                                                        {{ __('staff-dashboard/bundles.arabic') }}
                                                    </flux:text>
                                                    <ul class="space-y-2">
                                                        @foreach($arFeats as $f)
                                                            <li class="flex items-start gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                                                <flux:icon icon="check-circle" size="xs" class="mt-1 text-primary" variant="solid" />
                                                                {{ $f }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="space-y-3">
                                                    <flux:text size="xs" font="bold" class="text-zinc-500 flex items-center gap-2">
                                                        <flux:icon icon="language" size="xs" />
                                                        {{ __('staff-dashboard/bundles.english') }}
                                                    </flux:text>
                                                    <ul class="space-y-2">
                                                        @foreach($enFeats as $f)
                                                            <li class="flex items-start gap-2 text-sm text-zinc-600 dark:text-zinc-400">
                                                                <flux:icon icon="check-circle" size="xs" class="mt-1 text-primary" variant="solid" />
                                                                {{ $f }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Pricing & Actions Side --}}
                                    <div class="flex flex-col justify-between gap-8">
                                        <div class="p-8 rounded-3xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 shadow-sm space-y-6">
                                            <flux:label size="sm" class="text-zinc-400 uppercase text-[9px] font-black tracking-widest">{{ __('staff-dashboard/bundles.pricing_breakdown') }}</flux:label>
                                            <div class="space-y-5">
                                                <div class="flex justify-between items-center bg-zinc-50 dark:bg-zinc-900 p-4 rounded-2xl border border-zinc-100 dark:border-zinc-800">
                                                    <flux:text size="sm" font="bold">{{ __('staff-dashboard/bundles.monthly_egp') }}</flux:text>
                                                    <flux:heading size="sm">{{ number_format($bundle->monthly_price_egp) }}</flux:heading>
                                                </div>
                                                <div class="flex justify-between items-center bg-primary p-4 rounded-2xl shadow-lg shadow-primary/20 text-white">
                                                    <flux:text size="sm" font="black" class="text-white/80">{{ __('staff-dashboard/bundles.annual_egp') }}</flux:text>
                                                    <flux:heading size="md" class="text-white">{{ number_format($bundle->annualPrice('EGP')) }}</flux:heading>
                                                </div>
                                                <flux:separator variant="subtle" />
                                                <div class="grid grid-cols-2 gap-4">
                                                    <div class="p-4 rounded-2xl border border-zinc-100 dark:border-zinc-800 text-center">
                                                        <flux:text size="xs" color="subtle" class="mb-1">{{ __('staff-dashboard/bundles.monthly_usd') }}</flux:text>
                                                        <flux:text font="bold">${{ number_format($bundle->monthly_price_usd, 2) }}</flux:text>
                                                    </div>
                                                    <div class="p-4 rounded-2xl border border-primary/20 text-center bg-primary/[0.04]">
                                                        <flux:text size="xs" color="primary" class="mb-1 font-black">{{ __('staff-dashboard/bundles.annual_usd') }}</flux:text>
                                                        <flux:text font="black" color="primary">${{ number_format($bundle->annualPrice('USD'), 2) }}</flux:text>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex justify-end gap-3">
                                            <flux:button variant="subtle" icon="pencil-square" href="{{ route('bundles.control', $bundle->id) }}" wire:navigate class="!rounded-xl px-6">
                                                {{ __('staff-dashboard/bundles.edit') }}
                                            </flux:button>
                                            
                                            @if(!$bundle->program_id)
                                                <flux:button variant="ghost" icon="trash" class="text-red-500 !rounded-xl" wire:click="delete({{ $bundle->id }})" wire:confirm="{{ __('staff-dashboard/bundles.delete_confirm') }}">
                                                    {{ __('staff-dashboard/bundles.delete') }}
                                                </flux:button>
                                            @else
                                                <div title="لا يمكن حذف الباقات المربوطة ببرنامج من هنا">
                                                    <flux:button variant="ghost" icon="trash" class="text-zinc-300 dark:text-zinc-600 !rounded-xl" disabled>
                                                        {{ __('staff-dashboard/bundles.delete') }}
                                                    </flux:button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="p-24 text-center flex flex-col items-center justify-center">
                        <div class="size-20 rounded-3xl bg-zinc-100 dark:bg-zinc-800 flex items-center justify-center mb-6">
                            <flux:icon name="banknotes" class="size-10 text-zinc-300" />
                        </div>
                        <flux:heading variant="subtle" size="lg">{{ __('staff-dashboard/bundles.no_bundles_found') }}</flux:heading>
                        <flux:text class="mt-2 text-secondary">{{ __('staff-dashboard/bundles.no_bundles_subtitle') }}</flux:text>
                        <flux:button variant="primary" icon="plus" href="{{ route('bundles.control') }}" wire:navigate class="mt-8 !rounded-2xl">
                            {{ __('staff-dashboard/bundles.create_first_plan') }}
                        </flux:button>
                    </div>
                @endforelse
            </div>
        </flux:card>

        <!-- Pagination -->
        <x-pagination :paginator="$this->bundles" />
    </div>
</div>
