<?php


use App\Application\Program\Actions\CreateProgramAction;
use App\Application\Program\Actions\UpdateProgramAction;
use App\Domains\Program\RepositoryInterface\IProgramRepository;
use App\Domains\Specialization\RepositoryInterface\ISpecializationRepository;
use App\Infrastructure\Enums\ProgramThumbnailType;
use App\Infrastructure\Enums\ProgramType;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

new #[Layout('layouts.app')] class extends Component {
    use WithPagination, WithFileUploads;

    public function rendering($view)
    {
        $view->title(__('staff-dashboard/programs.management_title'));
    }

    #[Url]
    public string $search = '';

    public int $perPage = 12;

    // Archive logic
    public function archive(int $id): void
    {
        app(IProgramRepository::class)->archive($id);
        $this->dispatch('toast', variant: 'success', heading: __('staff-dashboard/programs.toast.success'), message: __('staff-dashboard/programs.toast.deleted'));
    }

    #[Computed]
    public function programs()
    {
        $filters = [];
        if ($this->search) {
            $filters['search'] = $this->search;
        }

        return app(IProgramRepository::class)->filter(
            filters: $filters,
            perPage: $this->perPage
        );
    }

    // Methods removed as they now belong to program-control.blade.php
}; ?>

<div class="flex h-full w-full overflow-hidden">
    <div class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-thin">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            @php
                $pageTitle = __('staff-dashboard/programs.management_title');
                $breadcrumbs = [__('global.sidebar.dashboard') => route('dashboard'), $pageTitle];
            @endphp
            <x-dashboard-page-header :title="$pageTitle" :breadcrumbs="$breadcrumbs">
                <flux:badge size="sm" inset="top bottom">{{ $this->programs->total() }}</flux:badge>
            </x-dashboard-page-header>
        </div>

        <!-- Actions -->
        <flux:card class="p-4 mb-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <flux:button variant="primary" icon="plus" size="base" href="{{ route('programs.control') }}"
                    wire:navigate>
                    {{ __('staff-dashboard/programs.add_program') }}
                </flux:button>
                <flux:button variant="outline" color="amber" icon="archive-box" size="base" href="{{ route('programs.archive') }}"
                    wire:navigate>
                    {{ __('staff-dashboard/programs.archive_title') }}
                </flux:button>
            </div>
        </flux:card>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($this->programs as $program)
                <flux:card wire:key="prog-{{ $program->id }}" class="p-0 overflow-hidden flex flex-col group">
                    <div class="relative h-48 bg-zinc-100 dark:bg-zinc-800">
                        @if($program->thumbnail_path)
                            <img src="{{ asset('uploads/' . $program->thumbnail_path) }}" class="w-full h-full object-cover">
                        @else
                            <div class="flex items-center justify-center h-full text-zinc-400">
                                <flux:icon name="academic-cap" variant="outline" class="w-12 h-12" />
                            </div>
                        @endif
                        <div class="absolute top-3 left-3 flex gap-2">
                            @if($program->badge)
                                <flux:badge size="sm" color="amber">{{ $program->badge }}</flux:badge>
                            @endif
                        </div>
                    </div>

                    <div class="p-5 flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <flux:heading size="md" class="font-bold line-clamp-1">
                                {{ $program->title }}
                            </flux:heading>
                            <div class="flex gap-1">
                                <flux:button variant="ghost" icon="eye" size="xs"
                                    href="{{ route('programs.view', $program->slug) }}" wire:navigate square />
                                <flux:button variant="ghost" icon="pencil-square" size="xs"
                                    href="{{ route('programs.control', $program->slug) }}" wire:navigate square />
                                <flux:button variant="ghost" icon="archive-box" size="xs" class="text-amber-600"
                                    wire:click="archive({{ $program->id }})" wire:confirm="Move this program to archive?"
                                    square />
                            </div>
                        </div>



                        <flux:text size="sm" class="line-clamp-2 opacity-80 mb-4 flex-1">
                            {{ $program->short_description }}
                        </flux:text>

                        <div
                            class="flex items-center justify-between mt-auto pt-4 border-t border-zinc-100 dark:border-zinc-800">
                            <flux:badge size="sm" :color="$program->is_active ? 'emerald' : 'zinc'">
                                {{ $program->is_active ? __('staff-dashboard/programs.active') : __('staff-dashboard/programs.inactive') }}
                            </flux:badge>
                            <flux:text size="xs" color="zinc">{{ $program->created_at?->format('M d, Y') }}</flux:text>
                        </div>
                    </div>
                </flux:card>
            @endforeach
        </div>

        @if($this->programs->isEmpty())
            <flux:card class="p-12 text-center flex flex-col items-center justify-center border-dashed">
                <flux:icon name="academic-cap" class="h-12 w-12 text-zinc-300 mb-4" />
                <flux:heading variant="subtle">{{ __('staff-dashboard/programs.no_results') }}</flux:heading>
            </flux:card>
        @endif

        <div class="mt-6">
            <x-pagination :paginator="$this->programs" />
        </div>

    </div>
</div>