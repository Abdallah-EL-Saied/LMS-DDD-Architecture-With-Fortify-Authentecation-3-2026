<?php

use App\Application\Recruitment\Actions\ProcessJobApplicationAction;
use App\Domains\Recruitment\RepositoryInterface\IJobApplicationRepository;
use App\Domains\Specialization\RepositoryInterface\ISpecializationRepository;
use App\Domains\Identity\Enums\RequestStatus;
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
        $view->title(__('staff-dashboard/recruitment.title'));
    }

    #[Url]
    public string $statusFilter = 'all';

    #[Url]
    public string $search = '';

    #[Url]
    public string $dateFrom = '';

    #[Url]
    public string $dateTo = '';

    #[Url]
    public $specFilter = '';

    public int $perPage = 10;

    // Accordion state
    public ?int $expandedId = null;
    public string $reviewerNotes = '';

    public function resetFilters(): void
    {
        $this->statusFilter = 'all';
        $this->search = '';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->specFilter = '';
        $this->resetPage();
    }

    public function toggleAccordion(int $id): void
    {
        if ($this->expandedId === $id) {
            $this->expandedId = null;
        } else {
            $this->expandedId = $id;
            $this->reviewerNotes = '';
        }
    }

    #[Computed]
    public function applications()
    {
        $filters = [];
        
        if ($this->statusFilter && $this->statusFilter !== 'all') {
            $filters['status'] = $this->statusFilter;
        }

        if ($this->search) {
            $filters['search'] = $this->search;
        }

        if ($this->specFilter) {
            $filters['specialization_ids'] = ['operator' => 'JSON_CONTAINS', 'value' => (int) $this->specFilter];
        }

        if ($this->dateFrom && $this->dateTo) {
            $filters['submitted_at'] = ['from' => $this->dateFrom . ' 00:00:00', 'to' => $this->dateTo . ' 23:59:59'];
        }

        return app(IJobApplicationRepository::class)->filter(
            filters: $filters,
            perPage: $this->perPage
        );
    }

    #[Computed]
    public function specializations()
    {
        return collect(app(ISpecializationRepository::class)->getAll())->keyBy(fn($s) => $s->id());
    }

    public function openReview(int $id): void
    {
        $this->toggleAccordion($id);
    }

    public function process(int $id, string $newStatus, ProcessJobApplicationAction $action): void
    {
        try {
            $action->execute($id, $newStatus, auth()->id(), $this->reviewerNotes);
            $message = $newStatus === RequestStatus::APPROVED->value 
                ? __('staff-dashboard/recruitment.toast.approved') 
                : __('staff-dashboard/recruitment.toast.rejected');
            $this->dispatch('toast', variant: 'success', heading: __('staff-dashboard/recruitment.toast.success'), message: $message);
            $this->expandedId = null;
        } catch (\Exception $e) {
            $this->dispatch('toast', variant: 'danger', heading: __('staff-dashboard/recruitment.toast.error'), message: $e->getMessage());
        }
    }
}; ?>

<div class="flex h-full w-full overflow-hidden">
    <div class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-thin">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        @php
            $pageTitle = __('staff-dashboard/recruitment.title');
            $breadcrumbs = [__('global.sidebar.dashboard') => route('dashboard'), $pageTitle];
        @endphp
        <x-dashboard-page-header :title="$pageTitle" :breadcrumbs="$breadcrumbs">
            <flux:badge variant="primary" size="sm" inset="top bottom">{{ $this->applications->total() }}</flux:badge>
        </x-dashboard-page-header>
    </div>

    <!-- Main Content -->
    <flux:card class="p-0 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl overflow-hidden mb-6">
        <div class="px-4 sm:px-6 py-4 flex items-center justify-between gap-3 sm:gap-4">
            <div class="flex-1">
                <flux:input wire:model.live="search"
                    :placeholder="__('staff-dashboard/recruitment.filters.search_placeholder')"
                    icon="magnifying-glass" />
            </div>

            {{-- Desktop: Sidebar Trigger --}}
            <div class="hidden lg:block">
                <flux:button icon="adjustments-horizontal" variant="outline" square
                    @click="$dispatch('toggle-drawer-recruitment-filters')" />
            </div>

            {{-- Mobile/Tablet: Bottom Sheet Trigger --}}
            <div class="lg:hidden">
                <flux:modal.trigger name="recruitment-filters">
                    <flux:button icon="adjustments-horizontal" variant="outline" square />
                </flux:modal.trigger>
            </div>
        </div>

        <!-- Accordion List -->
        <div class="border-t border-zinc-200 dark:border-zinc-800 divide-y divide-zinc-200 dark:divide-zinc-800">
            @forelse ($this->applications as $app)
                <div class="flex flex-col">
                    {{-- Row Header --}}
                    <div class="px-4 sm:px-6 py-4 flex items-center justify-between cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors gap-2"
                         wire:click="toggleAccordion({{ $app->id() }})">
                        <div class="flex items-center gap-3 min-w-0 w-full">
                            <flux:avatar :name="$app->fullName()" size="sm" class="shrink-0" />
                            <div class="flex flex-col min-w-0">
                                <flux:text class="font-bold truncate">{{ $app->fullName() }}</flux:text>
                                <flux:text variant="subtle" size="xs" class="truncate">{{ $app->email() }}</flux:text>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 sm:gap-4 shrink-0">
                             <flux:badge size="sm" :color="match($app->status()) {
                                RequestStatus::PENDING, 'pending' => 'yellow',
                                RequestStatus::APPROVED, 'approved' => 'emerald',
                                RequestStatus::REJECTED, 'rejected' => 'red',
                                default => 'zinc',
                            }">
                                {{ $app->status()->value }}
                            </flux:badge>
                            
                            <flux:button variant="ghost" :icon="$expandedId === $app->id() ? 'chevron-up' : 'chevron-down'" size="sm" square />
                        </div>
                    </div>

                    {{-- Accordion Content --}}
                    @if($expandedId === $app->id())
                        <div class="p-6 sm:p-8 bg-zinc-50/50 dark:bg-zinc-800/10 border-t border-zinc-200 dark:border-zinc-800 animate-in slide-in-from-top-2 duration-200">
                            <div class="flex flex-col space-y-4">
                                
                                {{-- Row 1: Submission Date --}}
                                <div class="flex items-center justify-between mb-2">
                                    <flux:label size="sm" class="mb-1">{{ __('staff-dashboard/recruitment.filters.date_range') }}</flux:label>
                                    <flux:text size="sm" class="tabular-nums">{{ $app->submittedAt()?->format('Y-m-d') }}</flux:text>
                                </div>

                                {{-- Row 2: Name, Age, and Email --}}
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex flex-col">
                                        <div class="flex items-center gap-2">
                                            <flux:text class="font-bold text-lg leading-none">{{ $app->fullName() }}</flux:text>
                                            <flux:text variant="subtle" size="sm" class="leading-none">{{ $app->age() }} {{ __('staff-dashboard/recruitment.modal.years') }}</flux:text>
                                        </div>
                                        <flux:text variant="subtle" size="sm" class="mt-2">{{ $app->email() }}</flux:text>
                                    </div>
                                </div>

                                {{-- Row 3: Specializations and Experience Years --}}
                                <div class="flex flex-row gap-6">
                                    <div class="flex-1">
                                        <flux:label size="sm" class="mb-2">{{ __('staff-dashboard/recruitment.specializations') }}</flux:label>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($app->specializationIds() as $sid)
                                                 <flux:badge size="sm">
                                                     {{ $this->specializations->get($sid)?->name()[app()->getLocale()] ?? $this->specializations->get($sid)?->name()['ar'] ?? 'Unknown' }}
                                                 </flux:badge>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="min-w-fit">
                                        <flux:label size="sm" class="mb-2">{{ __('staff-dashboard/recruitment.modal.experience') }}</flux:label>
                                        <flux:text size="sm">{{ $app->yearsExperience() }} {{ __('staff-dashboard/recruitment.modal.years') }}</flux:text>
                                    </div>
                                </div>

                                {{-- Row 4: Address and Phone --}}
                                <div class="flex items-center justify-between">
                                    <div>
                                        <flux:label size="sm" class="mb-1">{{ __('staff-dashboard/recruitment.modal.address') }}</flux:label>
                                        <flux:text size="sm" class="break-words">{{ $app->address() }}</flux:text>
                                    </div>
                                    <div>
                                        <flux:label size="sm" class="mb-1">{{ __('staff-dashboard/recruitment.modal.phone') }}</flux:label>
                                        <flux:text variant="subtle" size="sm" dir="ltr" class="text-start">{{ $app->phone() }}</flux:text>
                                    </div>
                                </div>

                                {{-- Row 5: Cover Letter and CV Link --}}
                                <div class="overflow-hidden">
                                    <div class="flex items-center gap-2 mb-2">
                                        <flux:label class="!m-0" size="sm">{{ __('staff-dashboard/recruitment.modal.cover_letter') }}</flux:label>
                                        <a href="{{ asset('uploads/' . $app->cvPath()) }}" target="_blank" class="text-sm font-medium text-[var(--color-primary)] hover:underline dark:text-[var(--color-primary-400)] transition-colors">
                                            ( {{ __('staff-dashboard/recruitment.view_cv') }} )
                                        </a>
                                    </div>
                                    <div class="bg-white dark:bg-zinc-900/50 p-4 rounded-xl border border-zinc-200 dark:border-zinc-800 text-sm text-zinc-600 dark:text-zinc-400 break-words w-full overflow-hidden">
                                        {{ $app->coverLetter() ?: __('staff-dashboard/recruitment.modal.no_cover_letter') }}
                                    </div>
                                </div>

                                {{-- Review Form --}}
                                <div class="pt-6 border-t border-zinc-200 dark:border-zinc-800">
                                    <flux:textarea :label="__('staff-dashboard/recruitment.modal.reviewer_notes')"
                                        wire:model="reviewerNotes"
                                        :placeholder="__('staff-dashboard/recruitment.modal.notes_placeholder')"
                                        rows="3" />
                                    
                                    <div class="flex justify-end gap-3 mt-4">
                                        @if($app->status() === RequestStatus::PENDING)
                                            <flux:button variant="danger" wire:click="process({{ $app->id() }}, 'rejected')">
                                                {{ __('staff-dashboard/recruitment.modal.reject') }}
                                            </flux:button>
                                            <flux:button variant="primary" wire:click="process({{ $app->id() }}, 'approved')">
                                                {{ __('staff-dashboard/recruitment.modal.approve') }}
                                            </flux:button>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            @empty
                <div class="p-12 text-center flex flex-col items-center justify-center">
                    <flux:icon name="inbox" class="h-12 w-12 text-zinc-300 mb-4" />
                    <flux:heading variant="subtle">{{ __('staff-dashboard/recruitment.no_results') }}</flux:heading>
                </div>
            @endforelse
        </div>

        <x-pagination :paginator="$this->applications" />
    </flux:card>

    </div>

    {{-- Filters Drawer --}}
    <x-sidebar-drawer name="recruitment-filters" :title="__('staff-dashboard/recruitment.filters.filter_title')">
        <div class="space-y-6">
            @include('pages.staff-dashboard.recruitment._filters')
        </div>
    </x-sidebar-drawer>
</div>
