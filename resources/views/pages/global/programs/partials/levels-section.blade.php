<!-- ==================== Curriculum Tab (Levels) ==================== -->
<div x-show="activeTab === 'curriculum'" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4">
    @if($program->levels->isNotEmpty())
        <div class="space-y-8">
            <h2
                class="text-3xl font-black text-zinc-900 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('programs.levels_title') }}
            </h2>
            <div class="grid grid-cols-1 gap-6">
                @foreach($program->levels as $level)
                    <div
                        class="bg-white p-8 rounded-[32px] shadow-xl shadow-zinc-200/50 border border-zinc-50 flex flex-col md:flex-row gap-8 items-start group hover:border-primary/20 transition-all">
                        <div
                            class="size-16 rounded-2xl bg-primary-500 text-white flex items-center justify-center font-black text-2xl shrink-0 group-hover:bg-secondary-300 group-hover:text-primary-500 transition-colors">
                            {{ $loop->iteration }}
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold text-zinc-900">{{ $level->name }}</h3>
                                <span
                                    class="text-xs font-black text-zinc-400 uppercase tracking-widest">{{ count($level->points) }}
                                    {{ __('programs.lessons') }}</span>
                            </div>
                            <div class="flex flex-col gap-y-3 gap-x-6">
                                @foreach($level->points as $point)
                                    <div class="flex items-center gap-2">
                                        <div class="size-2 rounded-full bg-primary/40"></div>
                                        <span class="text-zinc-600 font-medium">{{ $point }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <flux:card class="p-12 rounded-[40px] text-center italic text-zinc-400">
            {{ __('programs.curriculum_coming_soon') }}
        </flux:card>
    @endif
</div>
