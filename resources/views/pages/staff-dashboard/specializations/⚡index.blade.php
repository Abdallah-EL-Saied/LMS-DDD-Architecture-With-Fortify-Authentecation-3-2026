<?php

use App\Application\Specialization\Actions\CreateSpecializationAction;
use App\Application\Specialization\Actions\UpdateSpecializationAction;
use App\Domains\Specialization\RepositoryInterface\ISpecializationRepository;
use App\Concerns\Repositories\BaseRepositoryInterface;
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
        $view->title(__('staff-dashboard/specializations.title'));
    }

    #[Url]
    public string $search = '';

    public int $perPage = 16;

    // Modal state
    public bool $showModal = false;
    public ?int $editingId = null;
    public array $name = ['ar' => '', 'en' => ''];
    public array $description = ['ar' => '', 'en' => ''];
    public bool $isActive = true;

    /**
     * Reset to page 1 when search changes.
     */
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Get specializations for the list.
     */
    #[Computed]
    public function specializations()
    {
        $filters = [];
        if ($this->search) {
            $filters['search'] = $this->search;
        }

        return app(ISpecializationRepository::class)->filter(
            filters: $filters,
            perPage: $this->perPage
        );
    }

    public function create(): void
    {
        $this->resetErrorBag();
        $this->editingId = null;
        $this->name = ['ar' => '', 'en' => ''];
        $this->description = ['ar' => '', 'en' => ''];
        $this->isActive = true;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $this->resetErrorBag();
        $specialization = app(ISpecializationRepository::class)->findById($id);

        if ($specialization) {
            $this->editingId = $specialization->id();
            $this->name = $specialization->name();
            $this->description = $specialization->description() ?? ['ar' => '', 'en' => ''];
            $this->isActive = $specialization->isActive();
            $this->showModal = true;
        }
    }

    public function save(CreateSpecializationAction $createAction, UpdateSpecializationAction $updateAction): void
    {
        $this->validate([
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'description.ar' => 'nullable|string',
            'description.en' => 'nullable|string',
        ]);

        if ($this->editingId) {
            $updateAction->execute($this->editingId, $this->name, $this->description, $this->isActive);
            $this->dispatch('toast', variant: 'success', heading: __('staff-dashboard/specializations.toast.success'), message: __('staff-dashboard/specializations.toast.updated'));
        } else {
            $createAction->execute($this->name, $this->description, $this->isActive);
            $this->dispatch('toast', variant: 'success', heading: __('staff-dashboard/specializations.toast.success'), message: __('staff-dashboard/specializations.toast.created'));
        }

        $this->showModal = false;
    }

    public function delete(int $id): void
    {
        app(ISpecializationRepository::class)->delete($id);
        $this->dispatch('toast', variant: 'success', heading: __('staff-dashboard/specializations.toast.success'), message: __('staff-dashboard/specializations.toast.deleted'));
    }
}; ?>

<div class="flex h-full w-full overflow-hidden">
    <div class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-thin">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            @php
                $pageTitle = __('staff-dashboard/specializations.title');
                $breadcrumbs = [__('global.sidebar.dashboard') => route('dashboard'), $pageTitle];
            @endphp
            <x-dashboard-page-header :title="$pageTitle" :breadcrumbs="$breadcrumbs">
                <flux:badge variant="primary" size="sm" inset="top bottom">{{ $this->specializations->total() }}
                </flux:badge>

            </x-dashboard-page-header>
        </div>

        <!-- Filters & Table -->
        <flux:card
            class="p-0 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl overflow-hidden mb-6">
            <div class="px-4 sm:px-6 py-4 flex flex-col md:flex-row items-stretch justify-between gap-3 sm:gap-4">
                <div class="flex-1 w-full flex items-center">
                    <flux:input wire:model.live="search" class="w-full"
                        :placeholder="__('staff-dashboard/specializations.search_placeholder')"
                        icon="magnifying-glass" />
                </div>
                <flux:button variant="primary" icon="plus" class="w-full md:w-auto" wire:click="create">
                    {{ __('staff-dashboard/specializations.add_specialization') }}
                </flux:button>
            </div>
        </flux:card>

        <!-- Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mb-6">
            @foreach ($this->specializations as $spec)
                <flux:card wire:key="spec-{{ $spec->id() }}" class="p-4 flex flex-col justify-between transition-all duration-300 hover:border-primary dark:hover:border-secondary">
                    <div class="space-y-3">
                        <div class="flex justify-between items-start">
                            <div class="space-y-2">
                                <flux:heading size="md" class="font-bold">
                                    {{ $spec->name()[app()->getLocale()] ?? $spec->name()['ar'] }}
                                </flux:heading>
                                <flux:badge size="sm" :color="$spec->isActive() ? 'emerald' : 'zinc'">
                                    {{ $spec->isActive() ? __('staff-dashboard/specializations.active') : __('staff-dashboard/specializations.inactive') }}
                                </flux:badge>
                            </div>
                            
                            <div class="flex items-center gap-1 shrink-0">
                                <flux:button variant="ghost" icon="pencil-square" size="xs" 
                                    class="hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30 font-bold" 
                                    wire:click="edit({{ $spec->id() }})" 
                                    wire:target="edit({{ $spec->id() }})"
                                    square />
                                <flux:button variant="ghost" icon="trash" size="xs" 
                                    class="text-zinc-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/30" 
                                    wire:click="delete({{ $spec->id() }})" 
                                    :wire:confirm="__('staff-dashboard/specializations.delete_confirm')" 
                                    wire:target="delete({{ $spec->id() }})"
                                    square />
                            </div>
                        </div>
                        
                        <flux:text size="sm" class="line-clamp-3 opacity-80 min-h-[4.5rem]">
                            {{ $spec->description()[app()->getLocale()] ?? $spec->description()['ar'] ?? '' }}
                        </flux:text>
                    </div>
                </flux:card>
            @endforeach
        </div>

        @if($this->specializations->isEmpty())
            <flux:card class="p-12 text-center flex flex-col items-center justify-center border-dashed">
                <flux:icon name="academic-cap" class="h-12 w-12 text-zinc-300 mb-4" />
                <flux:heading variant="subtle">{{ __('staff-dashboard/specializations.no_results') }}</flux:heading>
            </flux:card>
        @endif

        <x-pagination :paginator="$this->specializations" :options="[16, 32, 48, 96]" />

        <!-- Create/Edit Modal -->
        <flux:modal wire:model="showModal" class="md:w-[600px]">
            <div class="space-y-6">
                <div>
                    <flux:heading size="lg">
                        {{ $editingId ? __('staff-dashboard/specializations.modal.edit_title') : __('staff-dashboard/specializations.modal.create_title') }}
                    </flux:heading>
                    <flux:subheading>{{ __('staff-dashboard/specializations.modal.subtitle') }}</flux:subheading>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:input :label="__('staff-dashboard/specializations.modal.name_ar')" wire:model="name.ar" />
                    <flux:input :label="__('staff-dashboard/specializations.modal.name_en')" wire:model="name.en" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <flux:textarea :label="__('staff-dashboard/specializations.modal.description_ar')"
                        wire:model="description.ar" />
                    <flux:textarea :label="__('staff-dashboard/specializations.modal.description_en')"
                        wire:model="description.en" />
                </div>

                <div class="rounded-xl border border-zinc-200 bg-zinc-50 p-4 dark:border-zinc-800 dark:bg-zinc-900/50">
                    <flux:checkbox wire:model="isActive" :label="__('staff-dashboard/specializations.modal.active')" />
                    <flux:text size="sm" class="mt-2 text-zinc-500">
                        {{ __('staff-dashboard/specializations.modal.active_help') }}
                    </flux:text>
                </div>

                <div class="flex justify-end gap-3">
                    <flux:button variant="ghost" @click="$wire.showModal = false">
                        {{ __('staff-dashboard/specializations.modal.cancel') }}
                    </flux:button>
                    <flux:button variant="primary" wire:click="save">
                        {{ __('staff-dashboard/specializations.modal.save') }}
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    </div>
</div>
