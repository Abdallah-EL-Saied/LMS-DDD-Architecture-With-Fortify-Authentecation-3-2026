<div x-data x-show="$store.pageLoading" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] flex items-center justify-center bg-surface dark:bg-surface-dark" x-cloak>

    <div class="relative flex items-center justify-center">
        <!-- Rotating border with 3 segments (one transparent side) -->
        <div class="absolute size-32 rounded-full border-[3px] border-primary dark:border-secondary border-t-transparent dark:border-t-transparent animate-spin"></div>
        
        <!-- Logo -->
        <x-app-logo-icon class="size-20" color="bg-primary dark:bg-secondary" />
    </div>

</div>

<style>
    [x-cloak] {
        display: none !important;
    }
</style>