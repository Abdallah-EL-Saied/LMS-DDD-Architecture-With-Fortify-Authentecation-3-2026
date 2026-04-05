<?php

use App\Application\Identity\Actions\DeleteUserAction;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use App\Domains\Identity\Enums\UserStatus;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

new #[Layout('layouts.app')] #[Title('User Management')] class extends Component {
    use WithPagination;

    #[Url]
    public array $searchTerms = [];

    public string $searchInput = '';

    #[Url]
    public string $roleFilter = 'all';

    #[Url]
    public string $sortField = 'first_name';

    #[Url]
    public string $sortDirection = 'asc';

    #[Url]
    public string $initialFilter = '';

    #[Url]
    public string $subscriptionFilter = '';

    #[Url]
    public string $statusFilter = '';

    #[Url]
    public string $lastLoginFilter = '';

    public string $createdAtFrom = '';
    public string $createdAtTo = '';

    public int $perPage = 10;

    /**
     * Reset to page 1 when perPage changes.
     */
    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    /**
     * Handle filter change.
     */
    public function setRole(string $role): void
    {
        $this->roleFilter = $role;
        $this->resetPage();
    }

    /**
     * Handle initial filter change.
     */
    public function setInitial(string $letter): void
    {
        $this->initialFilter = ($this->initialFilter === $letter) ? '' : $letter;
        $this->resetPage();
    }

    /**
     * Handle sorting logic.
     */
    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Navigate to user profile.
     */
    public function showUser(int $id): void
    {
        // Placeholder for future profile page
        $this->js("alert('Coming Soon: Navigation to user #{$id}')");
    }

    /**
     * Delete a user.
     */
    public function deleteUser(int $userId, DeleteUserAction $deleteUserAction): void
    {
        $deleteUserAction->execute($userId);
        $this->dispatch('user-deleted');
    }

    /**
     * Get users for the list.
     */
    #[Computed]
    public function users()
    {
        $filters = [];

        if (!empty($this->searchTerms)) {
            $filters['search'] = $this->searchTerms;
        }
        if ($this->roleFilter && $this->roleFilter !== 'all') {
            $filters['roles.name'] = $this->roleFilter;
        }
        if ($this->initialFilter) {
            $filters['first_name'] = ['operator' => 'LIKE', 'value' => $this->initialFilter . '%'];
        }
        if ($this->subscriptionFilter !== '') {
            // Subscription filtering will be handled via a separate subscriptions table
            // For now, this filter is disabled at the DB level
        }
        if ($this->statusFilter) {
            $filters['status'] = $this->statusFilter;
        }
        if ($this->lastLoginFilter) {
            $date = match ($this->lastLoginFilter) {
                '1_month' => now()->subMonth(),
                '3_months' => now()->subMonths(3),
                '6_months' => now()->subMonths(6),
                '1_year' => now()->subYear(),
                'more_than_1_year' => now()->subYear(),
                default => null,
            };
            if ($date) {
                $operator = $this->lastLoginFilter === 'more_than_1_year' ? '<=' : '>=';
                $filters['last_login_at'] = ['operator' => $operator, 'value' => $date->format('Y-m-d 00:00:00')];
            }
        }
        if ($this->createdAtFrom && $this->createdAtTo) {
            $filters['created_at'] = ['operator' => 'between', 'from' => $this->createdAtFrom . ' 00:00:00', 'to' => $this->createdAtTo . ' 23:59:59'];
        }

        return app(IUserRepository::class)->filter(
            filters: $filters,
            sort: ($this->sortDirection === 'desc' ? '-' : '') . $this->sortField,
            perPage: $this->perPage
        );
    }

    /**
     * Handle search tags
     */
    public function addSearchTerm(): void
    {
        $term = trim($this->searchInput);
        if ($term !== '' && !in_array($term, $this->searchTerms)) {
            $this->searchTerms[] = $term;
        }
        $this->searchInput = '';
        $this->resetPage();
    }

    public function removeSearchTerm(string $term): void
    {
        $this->searchTerms = array_values(array_diff($this->searchTerms, [$term]));
        $this->resetPage();
    }

    public function resetAllFilters(): void
    {
        $this->searchTerms = [];
        $this->initialFilter = '';
        $this->subscriptionFilter = '';
        $this->statusFilter = '';
        $this->lastLoginFilter = '';
        $this->createdAtFrom = '';
        $this->createdAtTo = '';
        // NOT resetting roleFilter, it represents the page's main context
        $this->resetPage();
    }
}; ?>

<div class="flex h-full w-full overflow-hidden">
    {{-- Main Content Area --}}
    <div class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-thin">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            @php
                $pageTitle = $roleFilter === 'student' ? __('global.sidebar.students') : ($roleFilter === 'teacher' ? __('global.sidebar.teachers') : __('staff-dashboard/users.title'));
                $breadcrumbs = [__('global.sidebar.dashboard') => route('dashboard'), $pageTitle];
            @endphp
            <x-dashboard-page-header :title="$pageTitle" :breadcrumbs="$breadcrumbs">
                <flux:badge variant="primary" size="sm" inset="top bottom">{{ $this->users->total() }}</flux:badge>
            </x-dashboard-page-header>
        </div>

        <!-- Main Card -->
        <flux:card class="p-0 border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 rounded-xl overflow-hidden mb-6">
            <div class="px-4 sm:px-6 py-4 flex items-center justify-between gap-3 sm:gap-4">
                <div class="flex-1">
                    <flux:input wire:model="searchInput" wire:keydown.enter="addSearchTerm"
                        placeholder="{{ __('staff-dashboard/users.search_placeholder') }}" icon="magnifying-glass" />
                </div>

                {{-- Desktop: Sidebar Trigger --}}
                <div class="hidden lg:block">
                    <flux:button icon="adjustments-horizontal" variant="outline" square
                        @click="$dispatch('toggle-drawer-user-filters')" />
                </div>

                {{-- Mobile/Tablet: Bottom Sheet Trigger --}}
                <div class="lg:hidden">
                    <flux:modal.trigger name="user-filters">
                        <flux:button icon="adjustments-horizontal" variant="outline" square />
                    </flux:modal.trigger>
                </div>
            </div>

            <!-- Active Filter Tags -->
            @if(count($searchTerms) > 0 || $initialFilter || $subscriptionFilter || $statusFilter || $lastLoginFilter || ($createdAtFrom && $createdAtTo))
                <div
                    class="px-6 py-3 border-b border-zinc-200 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-800/10 flex flex-wrap gap-2 items-center">
                    @foreach($searchTerms as $term)
                        <flux:badge size="sm" class="gap-1 whitespace-nowrap !bg-accent !text-white !border-transparent">
                            {{ __('staff-dashboard/users.search') }}: {{ $term }}
                            <button wire:click="removeSearchTerm('{{ $term }}')"
                                class="ml-1 text-[var(--color-secondary-500)] hover:text-[var(--color-secondary-400)] dark:text-[var(--color-secondary-400)] dark:hover:text-[var(--color-secondary-300)] transition-colors">
                                <flux:icon name="x-mark" size="sm" variant="micro" />
                            </button>
                        </flux:badge>
                    @endforeach

                    @if($initialFilter)
                        <flux:badge size="sm"
                            class="gap-1 whitespace-nowrap !bg-accent !text-accent-foreground !border-transparent">
                            {{ __('staff-dashboard/users.letter') }}: {{ $initialFilter }}
                            <button wire:click="setInitial('')" class="ml-1 text-red-500 hover:text-red-600 transition-colors">
                                <flux:icon name="x-mark" size="sm" variant="micro" />
                            </button>
                        </flux:badge>
                    @endif

                    @if($subscriptionFilter)
                        <flux:badge size="sm"
                            class="gap-1 whitespace-nowrap !bg-accent !text-accent-foreground !border-transparent">
                            {{ $subscriptionFilter === 'yes' ? __('staff-dashboard/users.subscribed') : __('staff-dashboard/users.not_subscribed') }}
                            <button wire:click="$set('subscriptionFilter', '')"
                                class="ml-1 text-red-500 hover:text-red-600 transition-colors">
                                <flux:icon name="x-mark" size="sm" variant="micro" />
                            </button>
                        </flux:badge>
                    @endif

                    @if($statusFilter)
                        <flux:badge size="sm"
                            class="gap-1 whitespace-nowrap !bg-accent !text-accent-foreground !border-transparent">
                            {{ __('staff-dashboard/users.status') }}: {{ __("staff-dashboard/users.{$statusFilter}") }}
                            <button wire:click="$set('statusFilter', '')"
                                class="ml-1 text-red-500 hover:text-red-600 transition-colors">
                                <flux:icon name="x-mark" size="sm" variant="micro" />
                            </button>
                        </flux:badge>
                    @endif

                    @if($lastLoginFilter)
                        <flux:badge size="sm"
                            class="gap-1 whitespace-nowrap !bg-accent !text-accent-foreground !border-transparent">
                            {{ __('staff-dashboard/users.last_login') }}: {{ __("staff-dashboard/users.{$lastLoginFilter}") }}
                            <button wire:click="$set('lastLoginFilter', '')"
                                class="ml-1 text-red-500 hover:text-red-600 transition-colors">
                                <flux:icon name="x-mark" size="sm" variant="micro" />
                            </button>
                        </flux:badge>
                    @endif

                    @if($createdAtFrom && $createdAtTo)
                        <flux:badge size="sm"
                            class="gap-1 whitespace-nowrap !bg-accent !text-accent-foreground !border-transparent">
                            {{ __('staff-dashboard/users.created') }}: {{ $createdAtFrom }} {{ __('staff-dashboard/users.to') }}
                            {{ $createdAtTo }}
                            <button wire:click="$set('createdAtFrom', ''); $set('createdAtTo', '');"
                                class="ml-1 text-red-500 hover:text-red-600 transition-colors">
                                <flux:icon name="x-mark" size="sm" variant="micro" />
                            </button>
                        </flux:badge>
                    @endif

                    <flux:button variant="ghost" size="sm" class="text-xs text-red-500 hover:text-red-600 px-2 h-auto py-1"
                        wire:click="resetAllFilters">{{ __('staff-dashboard/users.reset') }}</flux:button>
                </div>
            @endif
        </flux:card>

            <!-- Desktop View: Table -->
            <div class="hidden lg:block">
                <flux:table class="border-y border-zinc-200 dark:border-zinc-800 whitespace-nowrap">
                    <flux:table.columns>
                        <flux:table.column align="center" class="w-10">#</flux:table.column>
                        <flux:table.column sortable :sorted="$sortField === 'first_name'" :direction="$sortDirection"
                            wire:click="sortBy('first_name')">
                            {{ __('staff-dashboard/users.user') }}
                        </flux:table.column>
                        <flux:table.column>{{ __('staff-dashboard/users.subscription') }}</flux:table.column>
                        <flux:table.column sortable :sorted="$sortField === 'status'" :direction="$sortDirection"
                            wire:click="sortBy('status')">{{ __('staff-dashboard/users.status') }}
                        </flux:table.column>
                        <flux:table.column sortable :sorted="$sortField === 'last_login_at'" :direction="$sortDirection"
                            wire:click="sortBy('last_login_at')">
                            {{ __('staff-dashboard/users.last_login') }}
                        </flux:table.column>
                        <flux:table.column align="end" class="w-10"></flux:table.column>
                    </flux:table.columns>

                    <flux:table.rows>
                        @foreach ($this->users as $user)
                            <flux:table.row
                                class="cursor-pointer hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors"
                                wire:click="showUser({{ $user->id() }})">
                                <flux:table.cell align="center">
                                    <flux:text variant="subtle" size="sm" class="tabular-nums">
                                        {{ $this->users->firstItem() + $loop->index }}
                                    </flux:text>
                                </flux:table.cell>

                                <flux:table.cell>
                                    <div class="flex items-center gap-3">
                                        <flux:avatar :name="$user->fullName()" size="sm" />
                                        <div class="flex flex-col">
                                            <flux:text class="font-bold whitespace-nowrap">{{ $user->fullName() }}
                                            </flux:text>
                                            <flux:text variant="subtle" size="sm">{{ $user->email() }}</flux:text>
                                        </div>
                                    </div>
                                </flux:table.cell>

                                <flux:table.cell>
                                    <flux:badge size="sm" :color="$user->isSubscribed() ? 'emerald' : 'zinc'"
                                        :icon="$user->isSubscribed() ? 'check-circle' : 'x-circle'">
                                        {{ $user->isSubscribed() ? __('staff-dashboard/users.subscribed') : __('staff-dashboard/users.unsubscribed') }}
                                    </flux:badge>
                                </flux:table.cell>

                                <flux:table.cell>
                                    @php
                                        $statusColor = match ($user->status()) {
                                            UserStatus::ACTIVE => 'emerald',
                                            UserStatus::INACTIVE => 'zinc',
                                            UserStatus::BANNED => 'red',
                                            default => 'zinc'
                                        };
                                        $statusLabel = match ($user->status()) {
                                            UserStatus::ACTIVE => __('staff-dashboard/users.active'),
                                            UserStatus::INACTIVE => __('staff-dashboard/users.inactive'),
                                            UserStatus::BANNED => __('staff-dashboard/users.blocked'),
                                            default => __('staff-dashboard/users.unknown')
                                        };
                                    @endphp
                                    <flux:badge :color="$statusColor" size="sm" class="capitalize">{{ $statusLabel }}
                                    </flux:badge>
                                </flux:table.cell>

                                <flux:table.cell>
                                    <flux:text variant="subtle" size="sm" class="whitespace-nowrap">
                                        {{ $user->lastLoginAt() ? $user->lastLoginAt()->format('Y-m-d H:i') : __('staff-dashboard/users.never') }}
                                    </flux:text>
                                </flux:table.cell>

                                <flux:table.cell align="end">
                                    <flux:dropdown>
                                        <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" square
                                            wire:click.stop />
                                        <flux:menu>
                                            <flux:menu.item icon="user-circle" wire:click="showUser({{ $user->id() }})">
                                                {{ __('staff-dashboard/users.profile') }}
                                            </flux:menu.item>
                                            <flux:menu.item icon="pencil-square" wire:click="alert('Edit logic here')">
                                                {{ __('staff-dashboard/users.edit') }}
                                            </flux:menu.item>
                                            <flux:separator />
                                            <flux:menu.item icon="trash" variant="danger"
                                                wire:click="deleteUser({{ $user->id() }})"
                                                wire:confirm="{{ __('staff-dashboard/users.delete_confirm') }}">
                                                {{ __('staff-dashboard/users.delete') }}
                                            </flux:menu.item>
                                        </flux:menu>
                                    </flux:dropdown>
                                </flux:table.cell>
                            </flux:table.row>
                        @endforeach
                    </flux:table.rows>
                </flux:table>
            </div>

            <!-- Mobile View: Enhanced Cards -->
            <div class="lg:hidden divide-y divide-zinc-200 dark:divide-zinc-800">
                @foreach ($this->users as $user)
                    @php
                        $statusColor = match ($user->status()) {
                            UserStatus::ACTIVE => 'emerald',
                            UserStatus::INACTIVE => 'zinc',
                            UserStatus::BANNED => 'red',
                            default => 'zinc'
                        };
                        $statusLabel = match ($user->status()) {
                            UserStatus::ACTIVE => __('staff-dashboard/users.active'),
                            UserStatus::INACTIVE => __('staff-dashboard/users.inactive'),
                            UserStatus::BANNED => __('staff-dashboard/users.blocked'),
                            default => __('staff-dashboard/users.unknown')
                        };
                    @endphp
                    <div class="p-4 flex flex-col gap-3">
                        <div class="flex items-center justify-between gap-3">
                            <div class="flex items-center gap-3 min-w-0 flex-1" wire:click="showUser({{ $user->id() }})">
                                <flux:avatar :name="$user->fullName()" size="sm" class="shrink-0" />
                                <div class="flex flex-col min-w-0">
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-[10px] font-mono font-bold text-zinc-400 dark:text-zinc-500 bg-zinc-100 dark:bg-zinc-800/50 px-1.5 py-0.5 rounded leading-none shink-0">#{{ $this->users->firstItem() + $loop->index }}</span>
                                        <flux:text class="font-bold truncate">{{ $user->fullName() }}</flux:text>
                                    </div>
                                    <flux:text variant="subtle" size="sm" class="truncate">{{ $user->email() }}
                                    </flux:text>
                                </div>
                            </div>
                            <flux:dropdown>
                                <flux:button variant="ghost" icon="ellipsis-vertical" size="sm" square />
                                <flux:menu>
                                    <flux:menu.item icon="user-circle" wire:click="showUser({{ $user->id() }})">
                                        {{ __('staff-dashboard/users.profile') }}
                                    </flux:menu.item>
                                    <flux:menu.item icon="pencil-square" wire:click="alert('Edit logic here')">
                                        {{ __('staff-dashboard/users.edit') }}
                                    </flux:menu.item>
                                    <flux:separator />
                                    <flux:menu.item icon="trash" variant="danger" wire:click="deleteUser({{ $user->id() }})"
                                        wire:confirm="{{ __('staff-dashboard/users.delete_confirm') }}">
                                        {{ __('staff-dashboard/users.delete') }}
                                    </flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                        </div>

                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <flux:badge :color="$statusColor" size="sm" class="capitalize">{{ $statusLabel }}
                                </flux:badge>
                                <flux:badge size="sm" :color="$user->isSubscribed() ? 'emerald' : 'zinc'"
                                    :icon="$user->isSubscribed() ? 'check-circle' : 'x-circle'">
                                    {{ $user->isSubscribed() ? __('staff-dashboard/users.subscribed') : __('staff-dashboard/users.unsubscribed') }}
                                </flux:badge>
                            </div>
                            <flux:text variant="subtle" size="sm" class="whitespace-nowrap text-[11px]">
                                {{ $user->lastLoginAt() ? $user->lastLoginAt()->format('m/d H:i') : __('staff-dashboard/users.never') }}
                            </flux:text>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
        <x-pagination :paginator="$this->users" />
    </div>

    {{-- Secondary Sidebar (Filters/Reusable Drawer) --}}
    <x-sidebar-drawer name="user-filters" :title="__('staff-dashboard/users.filter_title')">
        <div class="space-y-6">
            @include('pages.users._filters')
        </div>
    </x-sidebar-drawer>
</div>