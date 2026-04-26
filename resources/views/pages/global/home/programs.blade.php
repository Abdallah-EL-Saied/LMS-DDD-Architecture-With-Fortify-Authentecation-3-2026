<?php

use App\Domains\Program\RepositoryInterface\IProgramRepository;
use Livewire\Component;
use Livewire\Attributes\Computed;

new class extends Component {
    #[Computed]
    public function programs()
    {
        return app(IProgramRepository::class)->filter(
            filters: ['is_active' => true],
            perPage: 6
        );
    }
};
?>

<div class="py-24 bg-white" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <x-section-heading :title="__('programs.heading')" />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($this->programs as $program)
                <x-program-card :program="$program" />
            @endforeach
        </div>

        <div class="text-center mt-12">
            <flux:button variant="primary" href="/programs" wire:navigate
                class="bg-primary text-white hover:bg-primary-dark border-none px-10 py-4 text-lg font-bold rounded-full shadow-lg transition-all duration-300 hover:scale-105 active:scale-95 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('programs.view_all') }}
            </flux:button>
        </div>
    </div>
</div>