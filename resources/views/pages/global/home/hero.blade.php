<?php

use Livewire\Component;

new class extends Component {
    public array $heroPhotos = [
        ['id' => 1, 'src' => 'assets/auth.webp', 'shape' => 'rounded-[50%_50%_0%_0%/50%_50%_0%_0%]', 'title' => 'landing.hero.subjects.islamic_sciences', 'alt' => 'landing.hero.subjects.islamic_sciences'],
        ['id' => 2, 'src' => 'assets/hero.webp', 'shape' => 'rounded-[0%_0%_100%_100%/0%_0%_100%_100%]', 'title' => 'landing.hero.subjects.quran', 'alt' => 'landing.hero.subjects.quran'],
        ['id' => 3, 'src' => 'assets/hero.webp', 'shape' => 'rounded-[50%_50%_0%_0%/50%_50%_0%_0%]', 'title' => 'landing.hero.subjects.arabic', 'alt' => 'landing.hero.subjects.arabic'],
        ['id' => 4, 'src' => 'assets/hero.webp', 'shape' => 'rounded-[0%_0%_100%_100%/0%_0%_100%_100%]', 'title' => 'landing.hero.subjects.hadith', 'alt' => 'landing.hero.subjects.hadith'],
        ['id' => 5, 'src' => 'assets/hero.webp', 'shape' => 'rounded-[50%_50%_0%_0%/50%_50%_0%_0%]', 'title' => 'landing.hero.subjects.history', 'alt' => 'landing.hero.subjects.history'],
        ['id' => 6, 'src' => 'assets/hero.webp', 'shape' => 'rounded-[0%_0%_100%_100%/0%_0%_100%_100%]', 'title' => 'landing.hero.subjects.nouranya', 'alt' => 'landing.hero.subjects.nouranya'],
        ['id' => 7, 'src' => 'assets/hero.webp', 'shape' => 'rounded-[50%_50%_0%_0%/50%_50%_0%_0%]', 'title' => 'landing.hero.subjects.ijazah', 'alt' => 'landing.hero.subjects.ijazah'],
        ['id' => 8, 'src' => 'assets/hero.webp', 'shape' => 'rounded-[0%_0%_100%_100%/0%_0%_100%_100%]', 'title' => 'landing.hero.subjects.character', 'alt' => 'landing.hero.subjects.character'],
        ['id' => 9, 'src' => 'assets/hero.webp', 'shape' => 'rounded-[50%_50%_0%_0%/50%_50%_0%_0%]', 'title' => 'landing.hero.subjects.fiqh', 'alt' => 'landing.hero.subjects.fiqh'],
        ['id' => 10, 'src' => 'assets/hero.webp', 'shape' => 'rounded-[0%_0%_100%_100%/0%_0%_100%_100%]', 'title' => 'landing.hero.subjects.nour_bayan', 'alt' => 'landing.hero.subjects.nour_bayan'],
    ];
};
?>

<!-- Image -->
<div class="relative overflow-hidden bg-cover bg-center"
    style="background-image: url('{{ asset('assets/hero.webp') }}')">

    <!-- Gradient Overlay -->
    <div class="absolute inset-0 bg-linear-to-b from-primary from-0% via-primary/70 via-90% to-primary/60 to-100%">
    </div>

    <!-- Content Container -->
    <div class="relative z-10 px-6 mx-auto flex flex-col items-center justify-between gap-8 py-12 h-full">

        <!-- Content Stack -->
        <div class="flex flex-col gap-6 text-center max-w-7xl items-center"
            dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
            <!-- Mobile Logo (Center) -->
            <div class="lg:hidden transition-transform duration-500 hover:scale-105">
                <x-app-logo color="bg-secondary" size="size-24" imgUrl="/FZLogo.png" />
            </div>

            <p
                class="text-secondary text-sm md:text-xl font-medium tracking-wide {{ app()->getLocale() === 'ar' ? 'cairo-font' : 'uppercase' }}">
                {{ __('landing.hero.subtitle') }}
            </p>

            <h1
                class="text-3xl md:text-6xl lg:text-7xl font-bold text-white leading-tight break-normal {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('landing.hero.title') }}
            </h1>

            <p class="text-white/80 text-sm md:text-lg max-w-2xl leading-relaxed">
                {{ __('landing.hero.description') }}
            </p>

            <!-- Action Buttons -->
            <div class="flex flex-wrap justify-center gap-4 mt-4">
                <flux:button variant="primary"
                    class="bg-secondary text-primary hover:bg-secondary/90 border-none w-56 px-12 py-6 text-xl font-bold shadow-2xl rounded-full transition-all duration-300 hover:scale-105 active:scale-95 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('landing.hero.button') }}
                </flux:button>
            </div>
        </div>

        <flux:spacer />

        <!-- Photos Carousel - True Queue Style Infinite Loop -->
        <div x-data="{ 
                photos: {{ json_encode(array_map(fn($p) => array_merge($p, ['src' => asset($p['src']), 'title' => __($p['title']), 'alt' => __($p['alt'])]), $heroPhotos)) }},
                activeId: {{ $heroPhotos[0]['id'] }},
                transitioning: false,
                autoplayInterval: null,
                get visibleItems() {
                    if (window.innerWidth >= 1280) return 6;
                    if (window.innerWidth >= 1024) return 4;
                    if (window.innerWidth >= 768) return 3;
                    return 2;
                },
                next() {
                    if (this.transitioning) return;
                    
                    this.transitioning = true;
                    this.$refs.track.style.transition = 'transform 700ms ease-in-out';
                    this.$refs.track.style.transform = `translateX(-${100 / this.visibleItems}%)`;

                    setTimeout(() => {
                        this.$refs.track.style.transition = 'none';
                        const firstItem = this.photos.shift();
                        this.photos.push(firstItem);
                        this.$refs.track.style.transform = 'translateX(0)';
                        
                        this.activeId = this.photos[0].id;
                        this.transitioning = false;
                        this.$refs.track.offsetHeight;
                    }, 700);
                },
                goTo(id) {
                    if (this.transitioning || this.activeId === id) return;
                    
                    const index = this.photos.findIndex(p => p.id === id);
                    if (index === -1) return;
                    
                    const items = this.photos.splice(0, index);
                    this.photos.push(...items);
                    
                    this.activeId = id;
                    
                    clearInterval(this.autoplayInterval);
                    this.autoplayInterval = setInterval(() => { this.next() }, 4000);
                }
            }" x-init="autoplayInterval = setInterval(() => { next() }, 4000)"
            class="w-full flex-1 flex flex-col justify-end overflow-hidden max-w-[100vw] mx-auto px-4 pb-4">

            <div x-ref="track" class="flex h-full items-end gap-2 md:gap-4 pt-2">
                <template x-for="photo in photos" :key="photo.id">
                    <div class="w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/6 flex-shrink-0 px-1 md:px-0">
                        <div class="group relative aspect-square overflow-hidden border-2 md:border-4 border-secondary/20 shadow-xl transition-all duration-500 hover:border-secondary hover:-translate-y-2 cursor-pointer isolation-isolate transform-gpu"
                            :class="photo.shape">
                            <img :src="photo.src"
                                class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110 will-change-transform"
                                :alt="photo.alt">

                            <!-- Branded Title Overlay - Centered -->
                            <div class="photo-overlay absolute inset-0 flex items-center justify-center p-2 md:p-4">
                                <span class="text-secondary font-bold text-center text-xs md:text-lg tracking-wider"
                                    x-text="photo.title">
                                </span>
                            </div>

                            <div class="absolute inset-0 bg-secondary/5 group-hover:bg-transparent transition-colors">
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Carousel Indicators - Restored Dots -->
            <div class="flex justify-center gap-2 mt-6 relative z-20">
                <template x-for="photo in {{ json_encode($heroPhotos) }}" :key="'dot-' + photo.id">
                    <button @click="goTo(photo.id)" type="button" aria-label="Go to slide"
                        class="size-1.5 md:size-2 rounded-full transition-all duration-300 cursor-pointer"
                        :class="activeId === photo.id ? 'bg-secondary w-4 md:w-6' : 'bg-white/20 hover:bg-white/40'"></button>
                </template>
            </div>
        </div>
    </div>
</div>