<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.welcome')] class extends Component {

};
?>

<!-- Pricing Page -->
<div dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="bg-surface pb-24">
    <x-page-header :title="__('global.header.pricing')" :subtitle="__('pricing.subheading')" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-16 text-center">

        <!-- Pricing Plans component (Includes Toggle + Cards) -->
        <livewire:pages::global.pricing.plans />

        <!-- Trust Badges -->
        <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-8 items-center pt-12 border-t border-zinc-100">
            <div class="flex flex-col items-center gap-2">
                <div class="size-12 rounded-2xl bg-primary/5 flex items-center justify-center text-primary mb-2">
                    <flux:icon icon="shield-check" variant="solid" class="size-6" />
                </div>
                <h4 class="font-bold text-zinc-900 text-sm">{{ __('pricing.guarantee_title') }}</h4>
                <p class="text-xs text-zinc-400 px-4">{{ __('pricing.guarantee_desc') }}</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <div class="size-12 rounded-2xl bg-secondary/5 flex items-center justify-center text-secondary mb-2">
                    <flux:icon icon="chat-bubble-left-right" variant="solid" class="size-6" />
                </div>
                <h4 class="font-bold text-zinc-900 text-sm">{{ __('pricing.support_title') }}</h4>
                <p class="text-xs text-zinc-400 px-4">{{ __('pricing.support_desc') }}</p>
            </div>
            <div class="flex flex-col items-center gap-2">
                <div class="size-12 rounded-2xl bg-zinc-50 flex items-center justify-center text-zinc-400 mb-2">
                    <flux:icon icon="user-group" variant="solid" class="size-6" />
                </div>
                <h4 class="font-bold text-zinc-900 text-sm">{{ __('pricing.trial_title') }}</h4>
                <p class="text-xs text-zinc-400 px-4">{{ __('pricing.trial_desc') }}</p>
            </div>
        </div>
    </div>
</div>