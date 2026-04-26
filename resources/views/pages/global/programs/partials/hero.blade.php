<div class="relative bg-primary-500 py-16 overflow-hidden">
    <div class="absolute inset-0 bg-linear-to-b from-primary from-0% via-primary/70 to-primary/60"></div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


        {{-- Navigation --}}
        <div class="mb-8 animate-fade-in">
            <a href="{{ route('programs') }}"
                class="inline-flex items-center gap-2 text-primary-100 hover:text-secondary-300 transition-colors text-sm font-bold uppercase tracking-widest">
                <flux:icon icon="arrow-left" class="size-4" />
                {{ __('programs.back_to_all') }}
            </a>
        </div>

        <div class="flex flex-col-reverse lg:flex-row gap-8 lg:gap-10 items-center">
            <!-- Hero Left: Content -->
            <div class="w-full space-y-6 lg:space-y-8 animate-fade-in-up">
                <div class="">
                    <span
                        class="inline-block px-4 py-1.5 rounded-lg bg-secondary-300 text-primary-500 text-[10px] font-black uppercase tracking-[0.2em] shadow-lg mb-4 lg:mb-6">
                        {{ $program->badge ?: 'Beginner to Advanced' }}
                    </span>

                    <h1
                        class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white leading-[1.2] lg:leading-[1.1] {{ app()->getLocale() === 'ar' ? 'cairo-font' : 'roboto-font tracking-tight' }}">
                        {{ $program->title }}
                    </h1>
                </div>

                <p class="text-lg text-primary-50/80 max-w-xl leading-relaxed font-medium">
                    {{ $program->short_description }}
                </p>

                {{-- Brand Stats Row --}}
                <div class="flex flex-wrap items-center gap-x-10 gap-y-6 pt-6 border-t border-primary-400/30">
                    <div class="flex items-center gap-3 text-white">
                        <flux:icon icon="users" variant="outline" class="size-5 text-secondary-300" />
                        <div class="flex items-baseline gap-1.5">
                            <span class="font-black">{{ $program->real_enrollments_count }}</span>
                            <span
                                class="text-[10px] text-primary-100/60 font-black uppercase tracking-widest">{{ __('programs.enrolled') }}</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-white">
                        <flux:icon icon="star" variant="solid" class="size-5 text-secondary-300" />
                        <div class="flex items-baseline gap-1.5">
                            <span class="font-black">4.9</span>
                            <span class="text-[10px] text-primary-100/60 font-black uppercase tracking-widest">(320
                                {{ __('programs.reviews') }})</span>
                        </div>
                    </div>
                </div>

                {{-- Social Proof --}}
                <div class="flex items-center gap-5 pt-2">
                    <div class="flex items-center">
                        @foreach([13, 14, 15, 16] as $idx => $img)
                            <img src="https://i.pravatar.cc/100?img={{ $img }}"
                                class="size-11 rounded-full border-2 border-primary-500 shadow-xl object-cover bg-zinc-800 relative z-{{ $idx * 10 }} {{ $idx > 0 ? '-ms-5' : '' }}">
                        @endforeach
                        <div
                            class="size-11 rounded-full border-2 border-primary-500 bg-secondary-300 text-primary-500 flex items-center justify-center font-black text-xs shadow-xl relative z-40 -ms-5">
                            100+
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <span
                            class="text-white font-black text-lg leading-tight">{{ __('programs.join_community') }}</span>
                        <span
                            class="text-primary-100/50 text-[10px] font-black uppercase tracking-[0.1em]">{{ __('programs.started_this_week') }}</span>
                    </div>
                </div>
            </div>

            <div class="w-full md:w-1/2 shrink-0">
                <x-video-player :videoUrl="$program->video_url" :youtubeEmbedUrl="$program->youtube_embed_url"
                    :thumbnailPath="$program->thumbnail_path" aspectClass="aspect-video"
                    roundedClass="rounded-[32px] border-2 border-secondary bg-primary-400/20">
                </x-video-player>
            </div>
        </div>
    </div>
</div>