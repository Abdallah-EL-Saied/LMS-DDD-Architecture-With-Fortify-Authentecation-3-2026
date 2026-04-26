<?php

namespace App\Infrastructure\Persistence\Eloquent\Models;

use App\Domains\Program\RepositoryInterface\IProgramRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination;

    public function rendering($view)
    {
        $view->title(__('staff-dashboard/programs.archive_title'));
    }

    public int $perPage = 12;

    #[Computed]
    public function archivedPrograms()
    {
        return app(IProgramRepository::class)->getArchived($this->perPage);
    }

    public function restore(int $id): void
    {
        app(IProgramRepository::class)->restore($id);
        $this->dispatch('toast', variant: 'success', heading: 'Restored', message: 'Program has been restored successfully');
    }

    public function forceDelete(int $id): void
    {
        app(IProgramRepository::class)->forceDelete($id);
        $this->dispatch('toast', variant: 'success', heading: 'Deleted Permanently', message: 'Program has been removed from the database');
    }
}; ?>

<div class="flex h-full w-full overflow-hidden">
    <div class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-thin">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            @php
                $pageTitle = __('staff-dashboard/programs.archive_title');
                $breadcrumbs = [__('global.sidebar.dashboard') => route('dashboard'), __('staff-dashboard/programs.management_title') => route('programs.management'), $pageTitle];
            @endphp
            <x-dashboard-page-header :title="$pageTitle" :breadcrumbs="$breadcrumbs">
                <flux:badge variant="amber" size="sm" inset="top bottom">{{ $this->archivedPrograms->total() }}</flux:badge>
            </x-dashboard-page-header>
            <flux:button variant="ghost" icon="arrow-left" href="{{ route('programs.management') }}" wire:navigate>{{ __('staff-dashboard/programs.back_to_programs') }}</flux:button>
        </div>

        <flux:card class="rounded-lg mb-6 p-4 bg-primary dark:bg-secondary text-white dark:text-primary flex gap-3 items-center">
            <flux:icon icon="information-circle" />
            <div class="text-sm">
                 {{ __('staff-dashboard/programs.archive_note') }}
            </div>
        </flux:card>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($this->archivedPrograms as $program)
                <flux:card wire:key="archived-{{ $program->id }}" class="p-0 overflow-hidden flex flex-col opacity-75 grayscale-[0.5] hover:grayscale-0 transition-all">
                    <div class="relative h-40 bg-zinc-100 dark:bg-zinc-800">
                        @if($program->thumbnail_path)
                            <img src="{{ asset('uploads/'.$program->thumbnail_path) }}" class="w-full h-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-black/20 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                             <flux:button variant="primary" size="sm" wire:click="restore({{ $program->id }})">Restore</flux:button>
                        </div>
                    </div>
                    
                    <div class="p-4 flex-1 flex flex-col">
                        <flux:heading size="sm" class="font-bold mb-1">{{ $program->title }}</flux:heading>
                        <flux:text size="xs" color="zinc" class="mb-4">Archived on {{ $program->updated_at ? $program->updated_at->format('M d, Y') : 'N/A' }}</flux:text>
                        
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-zinc-100">
                            <flux:button variant="ghost" icon="trash" size="xs" class="text-red-500" wire:click="forceDelete({{ $program->id }})" wire:confirm="Are you absolutely sure? This cannot be undone." square />
                            <flux:button variant="subtle" size="xs" wire:click="restore({{ $program->id }})">Restore Now</flux:button>
                        </div>
                    </div>
                </flux:card>
            @endforeach
        </div>

        @if($this->archivedPrograms->isEmpty())
            <flux:card class="p-12 text-center flex flex-col items-center justify-center border-dashed">
                <flux:icon name="archive-box" class="h-12 w-12 text-zinc-200 mb-4" />
                <flux:heading variant="subtle">Archive is empty</flux:heading>
            </flux:card>
        @endif

        <div class="mt-6">
            <x-pagination :paginator="$this->archivedPrograms" />
        </div>
    </div>
</div>
