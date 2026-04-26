<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domains\Program\RepositoryInterface\IProgramRepository;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\WithPagination;

new #[Layout('layouts.welcome')] class extends Component {
    use WithPagination;

    public $search = '';
    public int $perPage = 12;

    public function loadMore(): void
    {
        $this->perPage += 12;
    }

    #[Computed]
    public function programs()
    {
        return app(IProgramRepository::class)->filter(
            filters: ['search' => $this->search, 'is_active' => true],
            perPage: $this->perPage
        );
    }
};
?>

<div dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="bg-surface">
    <x-page-header :title="__('programs.index_title')" :subtitle="__('programs.index_subtitle')" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 sm:py-24">
        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($this->programs as $program)
                <x-program-card :program="$program" wire:key="program-{{ $program->id }}" />
            @endforeach
        </div>

        @if($this->programs->isEmpty())
            <div class="py-24 text-center">
                <div class="size-20 bg-primary-50 dark:bg-primary-900/10 rounded-full flex items-center justify-center mx-auto mb-6 text-primary-300">
                    <flux:icon name="magnifying-glass" variant="outline" class="size-10" />
                </div>
                <h3 class="text-2xl font-black text-primary-500 dark:text-white mb-2 {{ app()->getLocale() === 'ar' ? 'cairo-font' : 'roboto-font tracking-tight' }}">
                    {{ __('programs.no_results_title') }}
                </h3>
                <p class="text-zinc-500 text-sm font-medium">{{ __('programs.no_results_desc') }}</p>
                <button wire:click="$set('search', '')" class="mt-8 px-8 py-3 rounded-xl bg-primary-50 text-primary-500 font-black text-xs uppercase tracking-widest hover:bg-primary-100 transition-colors">
                    {{ __('programs.clear_search') }}
                </button>
            </div>
        @endif

        <x-load-more :paginator="$this->programs" />
    </div>
</div>