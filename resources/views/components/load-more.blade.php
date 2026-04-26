@props(['paginator', 'target' => 'loadMore'])

@if($paginator->hasMorePages())
    <div {{ $attributes->merge(['class' => 'mt-20 flex justify-center']) }}>
        <flux:button 
            wire:click="{{ $target }}" 
            variant="outline" 
            size="base" 
            class="w-52 cursor-pointer px-16 h-14 rounded-2xl font-black uppercase tracking-widest !bg-primary !text-white dark:!bg-secondary dark:!text-primary shadow-xl hover:bg-zinc-900 dark:hover:bg-primary dark:hover:text-white transition-all duration-300 border-none"
        >
            {{ __('programs.load_more') }}
        </flux:button>
    </div>
@endif
