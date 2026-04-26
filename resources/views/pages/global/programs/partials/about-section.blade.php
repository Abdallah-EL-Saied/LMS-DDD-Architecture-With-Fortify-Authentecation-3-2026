<!-- ==================== Overview Tab ==================== -->
<div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4">
    <flux:card class="p-5 sm:p-8 md:p-12 rounded-[24px] md:rounded-[40px] border-none">
        {{-- About --}}
        <h2
            class="text-2xl md:text-3xl font-black text-primary mb-6 md:mb-8 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
            {{ __('programs.about_title') }}
        </h2>
        <div class="prose prose-zinc lg:prose-xl max-w-none text-zinc-600 leading-relaxed">
            {!! $program->full_description !!}
        </div>

        {{-- What You'll Learn --}}
        @if($program->learnings->isNotEmpty())
            <div class="h-px bg-zinc-100 my-12"></div>
            <h2 class="text-2xl font-black text-primary mb-8">{{ __('programs.what_you_learn') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($program->learnings as $learning)
                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-zinc-50 border border-zinc-100">
                        <div
                            class="size-6 rounded-full border border-secondary flex items-center justify-center flex-shrink-0 mt-0.5">
                            <flux:icon icon="check" variant="outline" size="sm" class="size-4 text-secondary" />
                        </div>
                        <span class="text-zinc-700 font-medium leading-relaxed">{{ $learning->title }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Exclusive Features --}}
        @if($program->features->isNotEmpty())
            <div class="h-px bg-zinc-100 my-12"></div>
            <h2 class="text-2xl font-black text-primary mb-8">
                {{ __('programs.exclusive_features') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($program->features as $feature)
                    <div
                        class="flex items-center gap-5 p-2 rounded-2xl bg-white border border-zinc-100 transition-all group">
                        <div
                            class="size-8 rounded-xl bg-secondary text-white flex items-center justify-center shrink-0 group-hover:bg-primary transition-colors">
                            <i class="{{ $feature->icon }} text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <h3 class="font-black text-zinc-900">{{ $feature->title }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </flux:card>
</div>
