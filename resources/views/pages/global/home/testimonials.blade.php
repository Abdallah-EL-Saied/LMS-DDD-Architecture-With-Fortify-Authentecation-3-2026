<?php

use Livewire\Component;

new class extends Component {
    public array $reviews = [];

    public function mount()
    {
        $data = [
            ['name' => 'Ahmed Yahia', 'role' => 'Parent', 'text' => "Excellent experience with Fatema Al-Zahraa Center. My children's recitation and tajweed level improved significantly in a short time. May Allah reward you."],
            ['name' => 'Sarah Mohamed', 'role' => 'Student (Hifz)', 'text' => 'The teachers are very dedicated and the schedules are flexible and suit university hours. Thanks to Allah and then the center, I was able to memorize 15 Juz.'],
            ['name' => 'Omar Khaled', 'role' => 'Student (Ijazah)', 'text' => 'An accurate scientific methodology and great attention to correcting articulation points and characteristics. I recommend joining the academy to anyone serious about seeking knowledge.'],
            ['name' => 'Mariam Abdullah', 'role' => 'Parent', 'text' => 'I was looking for a reliable place to teach my daughters the Noorania method, and I found here what I was looking for in terms of trust and knowledge.'],
        ];

        // Generate 20 items by repeating and slightly modifying the data
        for ($i = 0; $i < 20; $i++) {
            $item = $data[$i % count($data)];
            $item['rating'] = rand(4, 5);
            $item['name'] .= ' ' . ($i + 1);
            $this->reviews[] = $item;
        }
    }
};
?>

<div class="py-24 bg-surface" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" x-data="{
    scrollProgress: 0,
    autoplayInterval: null,
    
    updateProgress() {
        const el = this.$refs.scrollContainer;
        if (!el) return;
        const max = el.scrollWidth - el.clientWidth;
        if (max <= 0) return;
        this.scrollProgress = (Math.abs(el.scrollLeft) / max) * 100;
    },

    move(dir) {
        const el = this.$refs.scrollContainer;
        if (!el) return;

        const max = el.scrollWidth - el.clientWidth;
        const current = Math.abs(el.scrollLeft);
        const amount = 400;
        const isRtl = this.$el.dir === 'rtl';

        if (dir === 'next') {
            if (current >= max - 15) {
                el.scrollTo({ left: 0, behavior: 'smooth' });
            } else {
                el.scrollBy({ left: isRtl ? -amount : amount, behavior: 'smooth' });
            }
        } else {
            if (current <= 15) {
                el.scrollTo({ left: isRtl ? -max : max, behavior: 'smooth' });
            } else {
                el.scrollBy({ left: isRtl ? amount : -amount, behavior: 'smooth' });
            }
        }
    },

    startAutoplay() {
        this.stopAutoplay();
        this.autoplayInterval = setInterval(() => this.move('next'), 4000);
    },
    
    stopAutoplay() {
        if (this.autoplayInterval) {
            clearInterval(this.autoplayInterval);
            this.autoplayInterval = null;
        }
    }
}" x-init="setTimeout(() => { updateProgress(); startAutoplay(); }, 400)" @mouseenter="stopAutoplay()"
    @mouseleave="startAutoplay()">
    <div class="max-w-7xl mx-auto px-4 lg:px-8">

        <x-section-heading :title="__('home.testimonials.heading')" :description="__('home.testimonials.subheading')"
            show-line />

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start mt-12 md:mt-16">
            <!-- Left Side: Stats & Navigation (Lg: col-span-4, Mobile: stacked) -->
            <div class="lg:col-span-4 space-y-6 md:space-y-8">
                <!-- Overall Rating Badge (Visible on all screens) -->
                <div
                    class="inline-flex items-center gap-3 bg-white border border-zinc-100 px-4 py-2.5 rounded-2xl shadow-sm">
                    <div class="flex items-center gap-1.5">
                        <span class="font-bold text-zinc-900 text-xl">4.9</span>
                        <div class="flex text-secondary gap-0.5">
                            @for($i = 0; $i < 5; $i++)
                            <flux:icon icon="star" variant="solid" class="size-3.5" /> @endfor
                        </div>
                    </div>
                    <div class="h-6 w-px bg-zinc-200"></div>
                    <span
                        class="text-xs text-zinc-500 font-medium italic">{{ __(':count reviews', ['count' => 1250]) }}</span>
                </div>

                <div class="space-y-4 md:space-y-6">
                    <div class="text-zinc-200 hidden md:block">
                        <svg class="size-12 fill-current opacity-40" viewBox="0 0 24 24">
                            <path
                                d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H15.017C14.4647 8 14.017 8.44772 14.017 9V12C14.017 12.5523 13.5693 13 13.017 13H12.017V21H14.017ZM6.017 21L6.017 18C6.017 16.8954 6.91243 16 8.017 16H11.017C11.5693 16 12.017 15.5523 12.017 15V9C12.017 8.44772 11.5693 8 11.017 8H7.017C6.46472 8 6.017 8.44772 6.017 9V12C6.017 12.5523 5.56925 13 5.017 13H4.017V21H6.017Z" />
                        </svg>
                    </div>
                    <h3
                        class="text-xl md:text-3xl font-bold text-zinc-900 leading-snug {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                        {{ __('home.testimonials.side_text') }}
                    </h3>

                    <!-- Dynamic Navigation Bar -->
                    <div class="flex items-center gap-4 pt-2 md:pt-6">
                        <!-- PREVIOUS (Right on AR) -->
                        <button @click="move('prev')"
                            class="p-2.5 rounded-full border border-zinc-200 hover:bg-zinc-50 transition-all text-zinc-600 shadow-sm active:scale-90">
                            <flux:icon icon="arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}"
                                class="size-4 md:size-5" />
                        </button>

                        <!-- Progress Track -->
                        <div class="flex-1 h-1 bg-zinc-100 relative rounded-full overflow-hidden">
                            <div class="absolute inset-y-0 h-full bg-primary rounded-full transition-all duration-300"
                                :style="'width: ' + scrollProgress + '%; {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0'">
                            </div>
                        </div>

                        <!-- NEXT (Left on AR) -->
                        <button @click="move('next')"
                            class="p-2.5 rounded-full border border-zinc-200 hover:bg-zinc-50 transition-all text-zinc-600 shadow-sm active:scale-90">
                            <flux:icon icon="arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }}"
                                class="size-4 md:size-5" />
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Side: Horizontal Scroller -->
            <div class="lg:col-span-8 relative">
                <div x-ref="scrollContainer" @scroll.debounce.10ms="updateProgress()"
                    class="flex gap-4 md:gap-6 overflow-x-auto pb-6 md:pb-8 snap-x snap-mandatory no-scrollbar scroll-smooth">
                    @foreach($reviews as $review)
                        <div
                            class="flex-none w-[280px] sm:w-[350px] bg-white p-6 md:p-8 rounded-2xl md:rounded-3xl shadow-[0_4px_20px_-10px_rgba(0,0,0,0.08)] border border-zinc-50 snap-start flex flex-col">

                            <p class="text-zinc-600 leading-relaxed mb-8 flex-1 text-sm sm:text-base">
                                {{ $review['text'] }}
                            </p>

                            <div class="space-y-4">
                                <!-- Green Stars -->
                                <div class="flex gap-0.5 text-secondary">
                                    @for($i = 0; $i < ($review['rating'] ?? 5); $i++)
                                        <flux:icon icon="star" variant="solid" class="size-4" />
                                    @endfor
                                </div>

                                <!-- User Info -->
                                <div class="flex items-center gap-4">
                                    <div
                                        class="size-10 rounded-full bg-zinc-100 flex items-center justify-center text-primary font-bold text-xs border border-zinc-200 shadow-sm">
                                        {{ substr($review['name'], 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="font-bold text-zinc-900 truncate text-sm">{{ $review['name'] }}</div>
                                        <div class="text-[10px] text-zinc-400 mt-0.5 uppercase tracking-wider">
                                            {{ $review['role'] }}
                                        </div>
                                    </div>
                                    <div class="text-[10px] text-zinc-300">
                                        {{ rand(1, 4) }} {{ __('weeks ago') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</div>