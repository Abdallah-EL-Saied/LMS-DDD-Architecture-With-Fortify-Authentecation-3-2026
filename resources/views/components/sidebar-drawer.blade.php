@props(['name', 'title' => null])

<div x-data="{ open: false }" 
     @toggle-drawer-{{ $name }}.window="open = !open"
     class="contents bg-white dark:bg-zinc-900">
    {{-- Desktop: Inline Sidebar --}}
    <div class="hidden lg:flex h-full flex-col bg-white dark:bg-zinc-900 border-s border-zinc-200 dark:border-zinc-800 transition-all duration-300 ease-in-out overflow-hidden sticky top-0"
         :class="open ? 'w-72 opacity-100' : 'w-0 opacity-0 border-none'"
         x-cloak>
        <div class="w-72 flex flex-col h-full">
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex items-center justify-between">
                <flux:heading size="lg" class="px-2">{{ $title }}</flux:heading>
                <flux:button variant="ghost" icon="x-mark" size="sm" @click="open = false" />
            </div>
            
            <div class="p-4 space-y-6 overflow-y-auto h-full scrollbar-thin">
                {{ $slot }}
            </div>
        </div>
    </div>

    {{-- Mobile/Tablet: Bottom Sheet --}}
    <flux:modal :name="$name" position="bottom" variant="flyout" class="lg:hidden w-full h-[80vh] sm:h-auto rounded-t-3xl overflow-hidden p-0 !bg-white dark:!bg-zinc-900 border-t border-zinc-200 dark:border-zinc-800">
        <div class="flex flex-col h-full">

            <div class="px-6 pb-4 pt-2 flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800/50">
                <flux:heading size="lg">{{ $title }}</flux:heading>
                <flux:button variant="ghost" wire:click="resetAllFilters" size="sm"
                    class="text-red-500 hover:text-red-600 font-medium p-0 h-auto">
                    {{ __('staff-dashboard/users.reset') }}
                </flux:button>
            </div>

            <div class="flex-1 overflow-y-auto p-6 scrollbar-thin">
                {{ $slot }}
            </div>
        </div>
    </flux:modal>
</div>
