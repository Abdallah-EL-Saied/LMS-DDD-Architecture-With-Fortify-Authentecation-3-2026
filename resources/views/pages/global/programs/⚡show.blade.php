<?php


use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use App\Domains\Program\RepositoryInterface\IProgramRepository;

new #[Layout('layouts.welcome')] class extends Component {
    public string $slug;
    public string $billing = 'monthly';
    public string $currency = 'egp';

    public function mount(string $program)
    {
        $this->slug = $program;
        $country = session('user_country', 'EG');
        $this->currency = strtolower($country) === 'eg' ? 'egp' : 'usd';
    }

    #[Computed]
    public function program()
    {
        return app(IProgramRepository::class)->findBySlug($this->slug, [
            'levels',
            'features',
            'learnings',
            'faqs',
            'bundles' => function ($q) {
                $q->where('is_active', true)->orderBy('order');
            }
        ]) ?? abort(404);
    }

    #[Computed]
    public function pricingLayout(): array
    {
        $bundles = $this->program->bundles;
        if (empty($bundles)) return [];

        $pricingService = app(\App\Infrastructure\Services\Pricing\PricingService::class);
        $country = session('user_country', 'EG');
        
        // Use first bundle as base for the sidebar card
        return $pricingService->calculateLayout($bundles[0], $country);
    }
};
?>

@php
    $layout = $this->pricingLayout;
@endphp

<div dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="bg-zinc-50 min-h-screen"
    x-data="{ 
        billing: @entangle('billing').live, 
        currency: '{{ $this->currency }}', 
        activeTab: 'overview',
        pricing: {{ json_encode($layout) }}
    }">

    @include('pages.global.programs.partials.hero', ['program' => $this->program])

    <!-- Main Content Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Left: Tabs & Content -->
            <div class="lg:col-span-2 space-y-8">
                @include('pages.global.programs.partials.tabs-navigation')

                <!-- Tab Panels -->
                <div class="space-y-8">
                    @include('pages.global.programs.partials.about-section', ['program' => $this->program])
                    @include('pages.global.programs.partials.levels-section', ['program' => $this->program])
                    @include('pages.global.programs.partials.faq-section', ['program' => $this->program])
                    @include('pages.global.programs.partials.review-section', ['program' => $this->program])
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-8">
                @include('pages.global.programs.partials.sidebar-pricing', ['program' => $this->program])
            </div>
        </div>

    </div>
</div>
</div>