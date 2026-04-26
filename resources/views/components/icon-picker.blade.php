@props(['model', 'icons' => []])

<div x-data="{ 
        search: '', 
        allIcons: @js($icons), 
        open: false,
        limit: 150,
        get filteredIcons() {
            if (!this.search) return this.allIcons.slice(0, this.limit);
            return this.allIcons.filter(i => i.toLowerCase().includes(this.search.toLowerCase())).slice(0, this.limit);
        }
    }" class="relative" @click.away="open = false">
    <!-- Preview Box / Trigger -->
    <button type="button" @click="open = !open"
        class="size-14 rounded-2xl bg-zinc-900 text-white flex items-center justify-center shrink-0 shadow-xl border-4 border-zinc-800 hover:scale-105 active:scale-95 transition-all group overflow-hidden relative">
        <i :class="$wire.get('{{ $model }}')" class="text-xl"></i>
        <div
            class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
            <i class="fa-solid fa-pen text-[10px]"></i>
        </div>
    </button>

    <!-- Dropdown Grid -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        class="absolute z-[100] mt-4 w-72 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-[28px] shadow-2xl p-4 overflow-hidden">
        <div class="space-y-4">
            <div class="relative">
                <i
                    class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 text-xs"></i>
                <input x-model="search" type="text" placeholder="Search icons..."
                    class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-800 border-none rounded-xl text-sm focus:ring-2 focus:ring-primary/20"
                    @click.stop>
            </div>

            <div class="grid grid-cols-5 gap-2 max-h-60 overflow-y-auto scrollbar-thin pr-1">
                <template x-for="i in filteredIcons" :key="i">
                    <button type="button"
                        @click="if(typeof $wire !== 'undefined') { $wire.set('{{ $model }}', i); open = false; }"
                        class="size-10 rounded-lg hover:bg-primary hover:text-white flex items-center justify-center transition-colors border border-zinc-100 dark:border-zinc-800"
                        :class="typeof $wire !== 'undefined' && $wire.get('{{ $model }}') === i ? 'bg-primary text-white border-primary' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400'"
                        :title="i">
                        <i :class="i" class="text-sm"></i>
                    </button>
                </template>
            </div>

            <div x-show="allIcons.length > limit"
                class="pt-2 text-center border-t border-zinc-100 dark:border-zinc-800">
                <button type="button" @click.stop="limit += 150"
                    class="text-[10px] font-black uppercase text-zinc-400 hover:text-primary transition-colors">
                    Load More...
                </button>
            </div>
        </div>
    </div>
</div>