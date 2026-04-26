<div x-show="activeTab === 'faq'" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4">
    @if($program->faqs->isNotEmpty())
        <div class="space-y-4" x-data="{ openFaq: null }">
            @foreach($program->faqs as $faq)
                <div class="bg-white rounded-2xl border border-zinc-100 overflow-hidden transition-all"
                     :class="openFaq === {{ $faq->id }} ? 'shadow-lg border-primary/20' : 'hover:border-zinc-200'">
                    {{-- Question --}}
                    <button @click="openFaq = openFaq === {{ $faq->id }} ? null : {{ $faq->id }}"
                        class="w-full flex items-center justify-between gap-4 p-6 text-start">
                        <span class="font-bold text-zinc-900 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                            {{ $faq->question }}
                        </span>
                        <div class="size-8 rounded-full flex items-center justify-center shrink-0 transition-colors"
                             :class="openFaq === {{ $faq->id }} ? 'bg-primary text-white' : 'bg-zinc-100 text-zinc-500'">
                            <flux:icon icon="chevron-down" class="size-4 transition-transform duration-300"
                                ::class="openFaq === {{ $faq->id }} ? 'rotate-180' : ''" />
                        </div>
                    </button>
                    {{-- Answer --}}
                    <div x-show="openFaq === {{ $faq->id }}"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 -translate-y-2"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-collapse>
                        <div class="px-6 pb-6 text-zinc-600 leading-relaxed border-t border-zinc-50 pt-4">
                            {!! nl2br(e($faq->answer)) !!}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <flux:card class="p-12 rounded-[40px] text-center italic text-zinc-400">
            {{ __('programs.no_faqs') }}
        </flux:card>
    @endif
</div>
