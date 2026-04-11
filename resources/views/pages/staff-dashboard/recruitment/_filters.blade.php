<!-- Specialization Filter -->
<div class="space-y-2">
    <flux:label>{{ __('staff-dashboard/recruitment.filters.specialization') }}</flux:label>
    <flux:select wire:model.live="specFilter" :placeholder="__('staff-dashboard/recruitment.filters.all_specializations')">
        <flux:select.option value="">{{ __('staff-dashboard/recruitment.filters.all') }}</flux:select.option>
        @foreach($this->specializations as $spec)
            <flux:select.option value="{{ $spec->id() }}">{{ $spec->name()[app()->getLocale()] ?? $spec->name()['en'] }}</flux:select.option>
        @endforeach
    </flux:select>
</div>

<flux:separator />

<!-- Status Filter -->
<div class="space-y-2">
    <flux:label>{{ __('staff-dashboard/recruitment.filters.status') }}</flux:label>
    <div class="grid grid-cols-2 gap-2">
        <flux:button size="sm" :variant="$statusFilter === 'all' ? 'primary' : 'outline'"
            wire:click="$set('statusFilter', 'all')">{{ __('staff-dashboard/recruitment.filters.all') }}
        </flux:button>
        <flux:button size="sm" :variant="$statusFilter === 'pending' ? 'primary' : 'outline'"
            wire:click="$set('statusFilter', 'pending')">{{ __('staff-dashboard/recruitment.filters.pending') }}
        </flux:button>
        <flux:button size="sm" :variant="$statusFilter === 'approved' ? 'primary' : 'outline'"
            wire:click="$set('statusFilter', 'approved')">{{ __('staff-dashboard/recruitment.filters.approved') }}
        </flux:button>
        <flux:button size="sm" :variant="$statusFilter === 'rejected' ? 'primary' : 'outline'"
            wire:click="$set('statusFilter', 'rejected')">{{ __('staff-dashboard/recruitment.filters.rejected') }}
        </flux:button>
    </div>
</div>

<flux:separator />

<!-- Date Range Filter -->
<div class="space-y-2">
    <flux:label>{{ __('staff-dashboard/recruitment.filters.date_range') }}</flux:label>
    <div class="flex flex-col gap-2">
        <div class="flex items-center gap-2">
            <flux:label class="text-xs text-zinc-500 w-8">{{ __('staff-dashboard/recruitment.filters.from') }}</flux:label>
            <flux:input type="date" wire:model.live="dateFrom" size="sm" class="flex-1" />
        </div>
        <div class="flex items-center gap-2">
            <flux:label class="text-xs text-zinc-500 w-8">{{ __('staff-dashboard/recruitment.filters.to') }}</flux:label>
            <flux:input type="date" wire:model.live="dateTo" size="sm" class="flex-1" />
        </div>
    </div>
</div>

<div class="pt-4">
    <flux:button variant="ghost" size="sm" class="w-full text-red-500 hover:text-red-600"
        wire:click="resetFilters">{{ __('staff-dashboard/recruitment.filters.reset') }}</flux:button>
</div>
