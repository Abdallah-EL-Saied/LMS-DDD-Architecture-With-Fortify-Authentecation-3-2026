<!-- Right: Sidebar Pricing Card -->
<div class="space-y-8 rounded-2xl">
    <div class="rounded-2xl bg-white border border-zinc-100 flex flex-col overflow-hidden">
        {{-- Header Section: Deep Teal --}}
        <div class="p-8 bg-[#013D38] text-center relative overflow-hidden">
            {{-- Decorative pattern --}}
            <div class="absolute -top-12 -right-12 size-40 bg-white/5 rounded-2xl blur-2xl"></div>
            
            <span class="text-[11px] font-black text-white/40 uppercase tracking-[0.2em] block mb-3">
                {{ __('programs.program_fee') }}
            </span>
            

            <!-- Price Display -->
            <div class="flex flex-col items-center justify-center text-white">
                <div class="flex flex-wrap items-baseline justify-center gap-1">
                    <span class="text-5xl sm:text-7xl font-black tracking-tighter leading-none">
                        <span x-show="currency === 'usd'" class="text-2xl sm:text-3xl align-top mt-2">$</span>
                        <span x-text="billing === 'annual' ? pricing.annual.total : pricing.monthly.amount"></span>
                    </span>
                    <div class="flex flex-col items-start">
                        <span x-show="currency === 'egp'" class="text-xl font-bold tracking-tight opacity-80">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</span>
                        <span class="text-white/50 font-bold" x-text="billing === 'annual' ? '' : '/{{ __('programs.month') }}'"></span>
                    </div>
                </div>
                
                {{-- Effective Monthly Price (Only for Annual) --}}
                <div x-show="billing === 'annual'" x-cloak class="mt-2 flex items-center gap-1.5 py-1 px-3 bg-white/10 rounded-xl border border-white/10">
                    <span class="text-sm font-black text-secondary">
                        <span x-show="currency === 'usd'">$</span>
                        <span x-text="pricing.annual.effective_monthly"></span>
                        <span x-show="currency === 'egp'" class="text-[10px] font-bold">{{ app()->getLocale() === 'ar' ? 'ج.م' : 'EGP' }}</span>
                        <span class="text-[10px] font-bold opacity-60">/{{ __('programs.month') }}</span>
                    </span>
                </div>
            </div>

            {{-- Money Back Guarantee Pill --}}
            <div class="mt-6">
                <span class="inline-flex items-center px-5 py-2 rounded-2xl bg-secondary text-[#013D38] text-[10px] font-bold uppercase tracking-widest shadow-lg shadow-black/10">
                    {{ __('programs.money_back_guarantee') }}
                </span>
            </div>
        </div>

        <div class="p-8 pb-10 ">
            <!-- Billing Toggle -->
            <div class="flex items-center justify-center gap-4 mb-10">
                <span @click="billing = 'monthly'" 
                    :class="billing === 'monthly' ? 'text-[#013D38] font-black' : 'text-zinc-400'"
                    class="text-[10px] uppercase font-bold tracking-widest cursor-pointer transition-colors">{{ __('pricing.monthly') }}</span>
                
                <button @click="billing = billing === 'monthly' ? 'annual' : 'monthly'"
                    class="relative w-11 h-6 bg-zinc-100 rounded-2xl transition-colors duration-300"
                    :class="billing === 'annual' ? 'bg-[#036A62]' : ''">
                    <div class="absolute top-1 w-4 h-4 bg-white rounded-2xl transition-all duration-300 shadow-md"
                        :class="[
                            '{{ app()->getLocale() }}' === 'ar' ? 'right-1' : 'left-1',
                            billing === 'annual' ? ('{{ app()->getLocale() }}' === 'ar' ? '-translate-x-5' : 'translate-x-5') : ''
                        ]"></div>
                </button>

                <div class="flex items-center gap-2 cursor-pointer" @click="billing = 'annual'">
                    <span :class="billing === 'annual' ? 'text-[#013D38] font-black' : 'text-zinc-400'"
                        class="text-[10px] uppercase font-bold tracking-widest transition-colors">{{ __('pricing.yearly') }}</span>
                    <span class="text-[9px] font-black text-secondary px-2 py-0.5  rounded border border-secondary/20">
                        -<span x-text="pricing.discount_percentage"></span>%
                    </span>
                </div>
            </div>

            {{-- CTA Buttons --}}
            <div class="space-y-4">
                {{-- Start Free Trial: Gold --}}
                <button class="w-full py-4 rounded-2xl bg-secondary text-primary font-bold hover:bg-primary hover:text-secondary transition-all uppercase tracking-wide text-sm">
                    {{ __('programs.start_free_trial') }}
                </button>

                {{-- Enroll Now: Outline --}}
                <button @click="document.getElementById('bundles-section')?.scrollIntoView({behavior: 'smooth'})"
                    class="w-full py-4 rounded-2xl border-2 border-zinc-100 bg-white text-zinc-600 font-bold hover:bg-primary hover:text-secondary transition-all uppercase tracking-wide text-sm">
                    {{ __('programs.enroll_now') }}
                </button>
            </div>

            <div class="h-px bg-zinc-100 my-8"></div>

            {{-- Includes List --}}
            <h4 class="text-xs font-black text-zinc-900 mb-6 uppercase tracking-[0.1em]">{{ __('programs.program_includes') }}</h4>
            <ul class="space-y-5">
                @php
                    $firstBundle = count($program->bundles) > 0 ? $program->bundles[0] : null;
                    $bundleFeatures = $firstBundle ? $firstBundle->features : [];
                @endphp
                
                @forelse($bundleFeatures as $feature)
                    <li class="flex items-center gap-4 text-sm text-zinc-600 font-medium group">
                        <div class="size-8 rounded-2xl flex items-center justify-center text-secondary border border-secondary shrink-0">
                            <flux:icon icon="check" class="size-5" />
                        </div>
                        <span class="line-clamp-2">{{ $feature }}</span>
                    </li>
                @empty
                    <li class="flex items-center gap-4 text-sm text-zinc-400 font-medium italic">
                        {{ __('programs.curriculum_coming_soon') }}
                    </li>
                @endforelse
            </ul>

            {{-- Guarantee Card --}}
            <div class="mt-10 p-5 rounded-2xl bg-emerald-50 border border-emerald-100 flex gap-4 items-center">
                <div class="size-10 rounded-2xl bg-emerald-500 flex items-center justify-center shrink-0 shadow-lg shadow-emerald-200">
                    <flux:icon icon="shield-check" variant="solid" class="size-6 text-white" />
                </div>
                <div class="leading-tight">
                    <p class="text-xs font-black text-emerald-800">100% Satisfaction Guarantee.</p>
                    <p class="text-[10px] text-emerald-600 opacity-90 mt-0.5 font-medium">Not satisfied? Get a full refund within 30 days.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- WhatsApp CTA: Vibrant Green -->
    <a href="https://wa.me/201122920352" target="_blank" 
       class="p-6 rounded-2xl bg-[#25D366] text-white flex items-center gap-5 cursor-pointer hover:bg-[#20bd5a] transition-all shadow-xl shadow-emerald-500/20 group">
        <div class="size-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center group-hover:scale-110 transition-transform">
            <i class="fa-brands fa-whatsapp text-3xl"></i>
        </div>
        <div class="flex flex-col">
            <h4 class="text-lg font-black leading-tight">{{ __('programs.have_questions') }}</h4>
            <p class="text-[13px] text-white/90 font-medium mt-1">{{ __('programs.chat_on_whatsapp') }} &rarr;</p>
        </div>
    </a>
</div>
