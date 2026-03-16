@props([
    'items' => [], // Array of ['question' => '', 'answer' => '']
])

<div x-data="{ active: null }" class="space-y-4">
    @foreach($items as $index => $item)
        <div 
            class="bg-white rounded-2xl border border-zinc-100 shadow-sm overflow-hidden transition-all duration-300"
            :class="active === {{ $index }} ? 'shadow-lg border-primary/20 ring-1 ring-primary/5' : 'hover:border-zinc-200'"
        >
            <button 
                @click="active = active === {{ $index }} ? null : {{ $index }}"
                class="w-full px-6 py-5 flex items-center justify-between gap-4 text-start transition-colors"
                :class="active === {{ $index }} ? 'bg-primary/5' : ''"
            >
                <span 
                    class="text-base font-bold text-zinc-900 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}"
                    :class="active === {{ $index }} ? 'text-primary' : ''"
                >
                    {{ $item['question'] }}
                </span>
                
                <div 
                    class="size-8 rounded-full bg-zinc-50 border border-zinc-100 flex items-center justify-center shrink-0 transition-transform duration-300"
                    :class="active === {{ $index }} ? 'rotate-180 bg-primary text-primary border-primary' : 'text-zinc-500'"
                >
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </div>
            </button>

            <div 
                x-show="active === {{ $index }}" 
                x-collapse
                x-cloak
            >
                <div class="px-6 pb-6 text-sm text-zinc-600 leading-relaxed">
                    <div class="w-full h-px bg-zinc-100 mb-5"></div>
                    {!! $item['answer'] !!}
                </div>
            </div>
        </div>
    @endforeach
</div>
