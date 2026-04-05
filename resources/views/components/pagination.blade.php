@props([
    'paginator', 
    'perPage' => 'perPage', 
    'options' => [10, 25, 50, 100]
])

<div {{ $attributes->merge(['class' => '@container flex items-center justify-between gap-4 w-full animate-in fade-in px-4 sm:px-6 py-4 mt-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden']) }}>
    <!-- Left Group: Per Page & Results Info -->
    <div class="flex items-center gap-3 sm:gap-6">
        <!-- Per Page Selector (Flux-like) -->
        <div class="flex items-center gap-2 sm:gap-3">
            <span class="hidden @md:inline text-xs font-medium text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                {{ __('staff-dashboard/users.per_page') }}
            </span>
            
            <div class="relative group h-9 flex items-center">
                <select 
                    wire:model.live="{{ $perPage }}" 
                    class="appearance-none block w-16 h-full pl-2 pr-6 py-0 text-sm font-bold bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-zinc-700 dark:text-zinc-100 cursor-pointer transition-all hover:border-zinc-300 dark:hover:border-zinc-600"
                >
                    @foreach($options as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                <!-- Custom Arrow icon for select -->
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-1.5 text-zinc-400 dark:text-zinc-500">
                    <i class="fas fa-chevron-down text-[8px]"></i>
                </div>
            </div>
        </div>

        <!-- Vertical Divider (Hidden on mobile) -->
        <div class="hidden @md:block h-5 w-px bg-zinc-200 dark:bg-zinc-800 mx-1"></div>

        <!-- Results Info (Hidden on mobile) -->
        <div class="hidden @md:block text-[11px] sm:text-xs text-zinc-500 dark:text-zinc-400 font-medium whitespace-nowrap">
            {{ __('pagination.showing') }} 
            <span class="text-zinc-900 dark:text-white font-bold mx-0.5">{{ $paginator->firstItem() }}</span>
            {{ __('pagination.to') }}
            <span class="text-zinc-900 dark:text-white font-bold mx-0.5">{{ $paginator->lastItem() }}</span>
            {{ __('pagination.of') }}
            <span class="text-zinc-900 dark:text-white font-bold mx-0.5">{{ $paginator->total() }}</span>
        </div>
    </div>

    <!-- Right Group: Pagination Arrows (and Page Numbers for Desktop) -->
    <div class="inline-flex items-center border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-lg overflow-hidden divide-x divide-zinc-200 dark:divide-zinc-700 rtl:divide-x-reverse h-9">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="flex items-center justify-center w-9 h-full bg-zinc-50 dark:bg-zinc-900/30 text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                <i class="fas fa-chevron-left fa-2xs rtl:rotate-180"></i>
            </span>
        @else
            <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="flex items-center justify-center w-9 h-full text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                <i class="fas fa-chevron-left fa-2xs rtl:rotate-180"></i>
            </button>
        @endif

        {{-- Page Numbers (Hidden on mobile) --}}
        <div class="hidden @md:contents">
            @foreach ($paginator->links()->elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="flex items-center justify-center w-9 h-full text-zinc-400 dark:text-zinc-600 bg-zinc-50 dark:bg-zinc-900/10">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex items-center justify-center w-9 h-full bg-primary text-secondary-300 font-bold">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="flex items-center justify-center w-9 h-full text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors font-medium">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="flex items-center justify-center w-9 h-full text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                <i class="fas fa-chevron-right fa-2xs rtl:rotate-180"></i>
            </button>
        @else
            <span class="flex items-center justify-center w-9 h-full bg-zinc-50 dark:bg-zinc-900/30 text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                <i class="fas fa-chevron-right fa-2xs rtl:rotate-180"></i>
            </span>
        @endif
    </div>
</div>
