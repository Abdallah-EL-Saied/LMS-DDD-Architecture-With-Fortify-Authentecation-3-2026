<!-- Alphabet Filter -->
<div>
    <flux:label class="mb-2 block">{{ __('staff-dashboard/users.initial_letter') }}</flux:label>
    <div class="flex flex-col gap-2">
        <!-- English Letters -->
        <div class="flex items-center gap-1 overflow-x-auto pb-2 scrollbar-thin" dir="ltr">
            <button wire:click="setInitial('')"
                class="shrink-0 flex items-center justify-center px-3 h-8 text-xs font-medium rounded-md transition-colors letter-filter-btn {{ $initialFilter === '' ? '!bg-accent !text-accent-foreground active' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300' }}">
                {{ __('staff-dashboard/users.all') }}
            </button>
            @foreach(range('A', 'Z') as $letter)
                <button wire:click="setInitial('{{ $letter }}')"
                    class="shrink-0 flex items-center justify-center w-8 h-8 text-[11px] font-bold rounded-md transition-colors letter-filter-btn {{ $initialFilter === $letter ? '!bg-accent !text-accent-foreground active' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800' }}">
                    {{ $letter }}
                </button>
            @endforeach
        </div>

        <!-- Arabic Letters -->
        <div class="flex items-center gap-1 overflow-x-auto pb-2 scrollbar-thin" dir="rtl">
            <button wire:click="setInitial('')"
                class="shrink-0 flex items-center justify-center px-3 h-8 text-xs font-medium rounded-md transition-colors letter-filter-btn {{ $initialFilter === '' ? '!bg-accent !text-accent-foreground active' : 'bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-300' }}">
                {{ __('staff-dashboard/users.all') }}
            </button>
            @foreach(['أ', 'ب', 'ت', 'ث', 'ج', 'ح', 'خ', 'د', 'ذ', 'ر', 'ز', 'س', 'ش', 'ص', 'ض', 'ط', 'ظ', 'ع', 'غ', 'ف', 'ق', 'ك', 'ل', 'م', 'ن', 'هـ', 'و', 'ي'] as $letter)
                <button wire:click="setInitial('{{ $letter }}')"
                    class="shrink-0 flex items-center justify-center w-8 h-8 text-[11px] font-bold rounded-md transition-colors letter-filter-btn {{ $initialFilter === $letter ? '!bg-accent !text-accent-foreground active' : 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800' }}">
                    {{ $letter }}
                </button>
            @endforeach
        </div>
    </div>
</div>

<flux:separator />

<!-- Subscription Filter -->
<div>
    <flux:label class="mb-2 block">{{ __('staff-dashboard/users.subscription_status') }}</flux:label>
    <div class="flex gap-2">
        <flux:button size="sm" class="flex-1"
            :variant="$subscriptionFilter === 'yes' ? 'primary' : 'outline'"
            wire:click="$set('subscriptionFilter', 'yes')">
            {{ __('staff-dashboard/users.subscribed') }}
        </flux:button>
        <flux:button size="sm" class="flex-1"
            :variant="$subscriptionFilter === 'no' ? 'primary' : 'outline'"
            wire:click="$set('subscriptionFilter', 'no')">
            {{ __('staff-dashboard/users.not_subscribed') }}
        </flux:button>
    </div>
</div>

<flux:separator />

<!-- Status Filter -->
<div>
    <flux:label class="mb-2 block">{{ __('staff-dashboard/users.account_status') }}</flux:label>
    <div class="grid grid-cols-2 gap-2">
        <flux:button size="sm" :variant="$statusFilter === '' ? 'primary' : 'outline'"
            wire:click="$set('statusFilter', '')">{{ __('staff-dashboard/users.all_statuses') }}
        </flux:button>
        <flux:button size="sm" :variant="$statusFilter === 'active' ? 'primary' : 'outline'"
            wire:click="$set('statusFilter', 'active')">{{ __('staff-dashboard/users.active') }}
        </flux:button>
        <flux:button size="sm" :variant="$statusFilter === 'inactive' ? 'primary' : 'outline'"
            wire:click="$set('statusFilter', 'inactive')">
            {{ __('staff-dashboard/users.inactive') }}
        </flux:button>
        <flux:button size="sm" :variant="$statusFilter === 'banned' ? 'primary' : 'outline'"
            wire:click="$set('statusFilter', 'banned')">
            {{ __('staff-dashboard/users.blocked') }}
        </flux:button>
    </div>
</div>

<flux:separator />

<!-- Last Login Filter -->
<div>
    <flux:label class="mb-2 block">{{ __('staff-dashboard/users.last_login') }}</flux:label>
    <div class="grid grid-cols-2 gap-2">
        <flux:button size="sm" :variant="$lastLoginFilter === '' ? 'primary' : 'outline'"
            wire:click="$set('lastLoginFilter', '')">{{ __('staff-dashboard/users.any_time') }}
        </flux:button>
        <flux:button size="sm" :variant="$lastLoginFilter === '1_month' ? 'primary' : 'outline'"
            wire:click="$set('lastLoginFilter', '1_month')">
            {{ __('staff-dashboard/users.1_month') }}
        </flux:button>
        <flux:button size="sm"
            :variant="$lastLoginFilter === '3_months' ? 'primary' : 'outline'"
            wire:click="$set('lastLoginFilter', '3_months')">
            {{ __('staff-dashboard/users.3_months') }}
        </flux:button>
        <flux:button size="sm"
            :variant="$lastLoginFilter === '6_months' ? 'primary' : 'outline'"
            wire:click="$set('lastLoginFilter', '6_months')">
            {{ __('staff-dashboard/users.6_months') }}
        </flux:button>
        <flux:button size="sm" :variant="$lastLoginFilter === '1_year' ? 'primary' : 'outline'"
            wire:click="$set('lastLoginFilter', '1_year')">
            {{ __('staff-dashboard/users.1_year') }}
        </flux:button>
        <flux:button size="sm"
            :variant="$lastLoginFilter === 'more_than_1_year' ? 'primary' : 'outline'"
            wire:click="$set('lastLoginFilter', 'more_than_1_year')">
            {{ __('staff-dashboard/users.more_than_1_year') }}
        </flux:button>
    </div>
</div>

<flux:separator />

<!-- Created At Filter -->
<div>
    <flux:label class="mb-2 block">{{ __('staff-dashboard/users.created_range') }}</flux:label>
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2">
            <flux:label class="text-xs text-zinc-500 w-8">{{ __('staff-dashboard/users.from') }}
            </flux:label>
            <flux:input type="date" wire:model.live="createdAtFrom" size="sm" class="flex-1" />
        </div>
        <div class="flex items-center gap-2">
            <flux:label class="text-xs text-zinc-500 w-8">{{ __('staff-dashboard/users.to') }}
            </flux:label>
            <flux:input type="date" wire:model.live="createdAtTo" size="sm" class="flex-1" />
        </div>
    </div>
</div>
