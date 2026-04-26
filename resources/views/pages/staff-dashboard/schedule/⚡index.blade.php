<?php

use App\Application\Academy\Actions\UpdateAcademyScheduleAction;
use App\Domains\Academy\RepositoryInterface\IAcademyScheduleRepository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Layout('layouts.app')] class extends Component {
    public function rendering($view)
    {
        $view->title(__('staff-dashboard/schedule.title'));
    }
    public array $availableDays = [];
    public bool $isFullDay = false;
    public ?string $startTime = '08:00';
    public ?string $endTime = '22:00';

    public function mount(IAcademyScheduleRepository $repository): void
    {
        $config = $repository->get();

        if ($config) {
            $this->availableDays = array_map('strval', $config->availableDays());
            $this->isFullDay = $config->isFullDay();
            $this->startTime = $config->startTime() ?: '08:00';
            $this->endTime = $config->endTime() ?: '22:00';
        }
    }

    public function save(UpdateAcademyScheduleAction $action): void
    {
        $rules = [
            'availableDays' => 'required|array|min:1',
            'isFullDay' => 'required|boolean',
        ];

        if (!$this->isFullDay) {
            $rules['startTime'] = 'required';
            $rules['endTime'] = 'required';
            
            try {
                $this->startTime = \Carbon\Carbon::parse($this->startTime)->format('H:i');
                $this->endTime = \Carbon\Carbon::parse($this->endTime)->format('H:i');
            } catch (\Exception $e) {
                // Let validation handle the rest, but we try to fix the format first
            }
            
            $rules['startTime'] .= '|date_format:H:i';
            $rules['endTime'] .= '|date_format:H:i|after:startTime';
        }

        $this->validate($rules);

        $action->execute([
            'available_days' => array_map('intval', $this->availableDays),
            'is_full_day' => $this->isFullDay,
            'start_time' => $this->isFullDay ? null : $this->startTime,
            'end_time' => $this->isFullDay ? null : $this->endTime,
        ]);

        $this->dispatch('toast', variant: 'success', heading: __('staff-dashboard/schedule.toast.success'), message: __('staff-dashboard/schedule.toast.updated'));
    }
}; ?>

<div class="flex h-full w-full overflow-hidden">
    <div class="flex-1 overflow-y-auto p-4 md:p-6 scrollbar-thin">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            @php
                $pageTitle = __('staff-dashboard/schedule.title');
                $breadcrumbs = [__('global.sidebar.dashboard') => route('dashboard'), $pageTitle];
            @endphp
            <x-dashboard-page-header :title="$pageTitle" :breadcrumbs="$breadcrumbs" />
        </div>

        <div class="">
            <flux:card class="p-6 md:p-8 space-y-8">
                <div>
                    <flux:heading size="xl" class="mb-1">{{ __('staff-dashboard/schedule.management_title') }}
                    </flux:heading>
                    <flux:subheading>{{ __('staff-dashboard/schedule.subtitle') }}</flux:subheading>
                </div>

                <div class="space-y-4">
                    <flux:label>{{ __('staff-dashboard/schedule.available_days') }}</flux:label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-7 gap-3">
                        @foreach(__('staff-dashboard/schedule.days') as $value => $label)
                            <label wire:key="day-{{ $value }}"
                                class="relative flex flex-col items-center justify-center p-3 rounded-xl border-2 cursor-pointer transition-all hover:bg-zinc-50 dark:hover:bg-zinc-800 {{ in_array($value, $availableDays) ? 'border-primary bg-primary/5' : 'border-zinc-100 dark:border-zinc-800' }}">
                                <input type="checkbox" value="{{ $value }}" wire:model.live="availableDays" class="sr-only">
                                <span
                                    class="text-xs font-bold {{ in_array($value, $availableDays) ? 'text-primary' : 'text-zinc-500' }}">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-6">
                    <div
                        class="flex items-center gap-4 p-4 rounded-xl bg-zinc-50 dark:bg-zinc-900 border border-zinc-100 dark:border-zinc-800">
                        <flux:switch wire:model.live="isFullDay" :label="__('staff-dashboard/schedule.is_full_day')" />
                        <flux:text size="sm" class="text-zinc-500">{{ __('staff-dashboard/schedule.full_day_help') }}
                        </flux:text>
                    </div>

                    @if(!$isFullDay)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <flux:input type="time" :label="__('staff-dashboard/schedule.start_time')"
                                wire:model="startTime" />
                            <flux:input type="time" :label="__('staff-dashboard/schedule.end_time')" wire:model="endTime" />
                        </div>
                    @endif
                </div>

                <div
                    class="pt-6 border-t border-zinc-100 dark:border-zinc-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <flux:text size="sm" class="max-w-md text-zinc-500 italic">
                        {{ __('staff-dashboard/schedule.help_text') }}
                    </flux:text>
                    <flux:button variant="primary" wire:click="save" class="px-8">
                        {{ __('staff-dashboard/schedule.save_changes') }}
                    </flux:button>
                </div>
            </flux:card>
        </div>
    </div>
</div>