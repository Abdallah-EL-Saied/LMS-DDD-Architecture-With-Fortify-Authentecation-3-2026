@props([
    'paginator', 
    'perPage' => 'perPage', 
    'options' => [10, 25, 50, 100]
])

@if ($paginator->total() > min($options))
<div {{ $attributes->merge(['class' => 'flex items-center justify-between gap-4 w-full animate-in fade-in px-4 sm:px-6 py-4 mt-6 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-xl overflow-hidden']) }}>
    <!-- Left: Per Page Selector & Info -->
    <div class="flex items-center gap-3 sm:gap-6 shrink-0">
        <div class="flex items-center gap-2 sm:gap-3">
            <span class="hidden md:inline text-[10px] font-bold text-zinc-400 dark:text-zinc-500 uppercase tracking-widest">
                {{ __('staff-dashboard/users.per_page') }}
            </span>
            
            <div class="relative group h-9 flex items-center">
                <select 
                    wire:model.live="{{ $perPage }}" 
                    class="appearance-none block w-16 h-full pl-3 pr-6 py-0 text-sm font-bold bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg focus:outline-none focus:ring-1 focus:ring-primary focus:border-primary text-zinc-700 dark:text-zinc-100 cursor-pointer transition-all hover:border-zinc-300 dark:hover:border-zinc-600"
                >
                    @foreach($options as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-zinc-400 dark:text-zinc-500">
                    <i class="fas fa-chevron-down text-[8px]"></i>
                </div>
            </div>
        </div>

        <div class="hidden sm:block text-[10px] text-zinc-400 dark:text-zinc-500 font-medium whitespace-nowrap uppercase tracking-wider">
            {{ __('pagination.showing') }} {{ $paginator->firstItem() }} {{ __('pagination.to') }} {{ $paginator->lastItem() }} {{ __('pagination.of') }} {{ $paginator->total() }}
        </div>
    </div>

    <!-- Right: Navigation Arrows + Page Numbers -->
    <div class="inline-flex items-center border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 rounded-lg overflow-hidden divide-x divide-zinc-200 dark:divide-zinc-700 rtl:divide-x-reverse h-9 shrink-0">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="flex items-center justify-center w-10 h-full bg-zinc-50 dark:bg-zinc-900/30 text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                <i class="fas fa-chevron-left fa-2xs rtl:rotate-180"></i>
            </span>
        @else
            <button wire:click="previousPage" wire:loading.attr="disabled" rel="prev" class="flex items-center justify-center w-10 h-full text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                <i class="fas fa-chevron-left fa-2xs rtl:rotate-180"></i>
            </button>
        @endif

        {{-- Mobile Indicator (Visible only on screens <= 425px) --}}
        <span class="flex [@media(min-width:426px)]:hidden items-center justify-center w-10 h-full bg-primary text-secondary-300 font-bold text-sm">
            {{ $paginator->currentPage() }}
        </span>

        {{-- Desktop Page Numbers (Visible only on screens > 425px) --}}
        <div class="hidden [@media(min-width:426px)]:contents">
            @foreach ($paginator->links()->elements as $element)
                @if (is_string($element))
                    <span class="flex items-center justify-center w-9 h-full text-zinc-400 dark:text-zinc-600 bg-zinc-50 dark:bg-zinc-900/10 shrink-0">
                        {{ $element }}
                    </span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="flex items-center justify-center w-9 h-full bg-primary text-secondary-300 font-bold shrink-0">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }})" class="flex items-center justify-center w-9 h-full text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors font-medium shrink-0">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage" wire:loading.attr="disabled" rel="next" class="flex items-center justify-center w-10 h-full text-zinc-600 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-zinc-700 transition-colors">
                <i class="fas fa-chevron-right fa-2xs rtl:rotate-180"></i>
            </button>
        @else
            <span class="flex items-center justify-center w-10 h-full bg-zinc-50 dark:bg-zinc-900/30 text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                <i class="fas fa-chevron-right fa-2xs rtl:rotate-180"></i>
            </span>
        @endif
    </div>
</div>
@endif
