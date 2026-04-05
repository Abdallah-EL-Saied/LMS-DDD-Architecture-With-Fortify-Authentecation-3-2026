<?php

use Livewire\Component;

new class extends Component {
    public array $images = [];

    public function mount()
    {
        // Use relative root paths with leading slash
        $this->images = [
            ['src' => '/assets/hero.webp', 'alt' => __('home.gallery.alt_1'), 'span' => 'lg:col-span-2 lg:row-span-1'],
            ['src' => '/assets/auth.webp', 'alt' => __('home.gallery.alt_2'), 'span' => 'lg:col-span-1 lg:row-span-1'],
            ['src' => '/assets/hero.webp', 'alt' => __('home.gallery.alt_3'), 'span' => 'lg:col-span-1 lg:row-span-2'],
            ['src' => '/assets/auth.webp', 'alt' => __('home.gallery.alt_4'), 'span' => 'lg:col-span-1 lg:row-span-1'],
            ['src' => '/assets/hero.webp', 'alt' => __('home.gallery.alt_5'), 'span' => 'lg:col-span-2 lg:row-span-2'],
            ['src' => '/assets/auth.webp', 'alt' => __('home.gallery.alt_6'), 'span' => 'lg:col-span-1 lg:row-span-2'],
            ['src' => '/assets/hero.webp', 'alt' => __('home.gallery.alt_7'), 'span' => 'lg:col-span-1 lg:row-span-1'],
            ['src' => '/assets/auth.webp', 'alt' => __('home.gallery.alt_8'), 'span' => 'lg:col-span-1 lg:row-span-1'],
            ['src' => '/assets/auth.webp', 'alt' => __('home.gallery.alt_8'), 'span' => 'lg:col-span-2 lg:row-span-1'],
        ];
    }
};
?>

<div class="py-24 bg-surface" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" x-data="{ 
        isOpen: false, 
        currentIdx: 0,
        images: {{ json_encode($images) }},
        openLightbox(idx) {
            this.currentIdx = idx;
            this.isOpen = true;
            document.body.classList.add('overflow-hidden');
        },
        closeLightbox() {
            this.isOpen = false;
            document.body.classList.remove('overflow-hidden');
        },
        next() {
            this.currentIdx = (this.currentIdx + 1) % this.images.length;
        },
        prev() {
            this.currentIdx = (this.currentIdx - 1 + this.images.length) % this.images.length;
        }
     }" @keydown.escape.window="closeLightbox()" @keydown.right.window="next()" @keydown.left.window="prev()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <x-section-heading :title="__('home.gallery.heading')" :description="__('home.gallery.subheading')" show-line />

        <!-- Desktop Gallery (Mosaic/Bento) -->
        <div class="hidden lg:grid grid-cols-4 auto-rows-[200px] gap-4">
            @foreach ($images as $idx => $img)
                <div @click="openLightbox({{ $idx }})"
                    class="relative group overflow-hidden rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 cursor-zoom-in {{ $img['span'] ?? '' }}">
                    <img src="{{ $img['src'] }}" alt="{{ $img['alt'] }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-6">
                        <p
                            class="text-white font-medium text-xs transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            {{ $img['alt'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Mobile Gallery (Manual Sliding Switcher) -->
        <!-- Forced LTR for consistent carousel math -->
        <div class="lg:hidden relative overflow-hidden rounded-3xl shadow-2xl bg-white/5 border border-white/10" 
            dir="ltr"
            x-data="{ 
                activeSlide: 0,
                slides: {{ json_encode($images) }},
                nextSlide() { if(this.activeSlide < this.slides.length - 1) this.activeSlide++ },
                prevSlide() { if(this.activeSlide > 0) this.activeSlide-- }
            }">
            
            <!-- Slides Track (Side-to-Side Motion) -->
            <div class="relative aspect-square overflow-hidden">
                <div class="flex h-full transition-transform duration-500 ease-in-out"
                    :style="'width: ' + (slides.length * 100) + '%; transform: translateX(-' + (activeSlide * (100 / slides.length)) + '%)'">
                    
                    <template x-for="(slide, index) in slides" :key="index">
                        <div class="h-full flex-shrink-0 relative" :style="'width: ' + (100 / slides.length) + '%'">
                            <img :src="slide.src" :alt="slide.alt" @click="openLightbox(index)"
                                class="w-full h-full object-cover cursor-zoom-in">
                            
                            <!-- Caption Overlay - Forced RTL back for text if Arabic -->
                            <div class="absolute inset-x-0 bottom-0 bg-linear-to-t from-primary/95 via-primary/40 to-transparent p-6 pt-12"
                                dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
                                <h3 class="text-white font-bold text-xl" x-text="slide.alt"></h3>
                                <p class="text-secondary text-sm mt-1" x-text="(index + 1) + ' / ' + slides.length"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Navigation Controls -->
            <!-- Previous Button (Left) - Hidden at start -->
            <div class="absolute inset-y-0 left-0 flex items-center pl-2" x-show="activeSlide > 0" x-cloak>
                <button @click="prevSlide()" class="size-10 rounded-full bg-primary/40 backdrop-blur-md text-white border border-white/10 flex items-center justify-center hover:bg-primary/60 transition-all shadow-xl">
                    <flux:icon icon="chevron-left" class="size-6" />
                </button>
            </div>

            <!-- Next Button (Right) - Hidden at end -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-2" x-show="activeSlide < slides.length - 1" x-cloak>
                <button @click="nextSlide()" class="size-10 rounded-full bg-primary/40 backdrop-blur-md text-white border border-white/10 flex items-center justify-center hover:bg-primary/60 transition-all shadow-xl">
                    <flux:icon icon="chevron-right" class="size-6" />
                </button>
            </div>

            <!-- Dots Indicators -->
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2">
                <template x-for="(slide, index) in slides" :key="'dot-'+index">
                    <button @click="activeSlide = index"
                        class="size-1.5 rounded-full transition-all duration-300"
                        :class="activeSlide === index ? 'bg-secondary w-6' : 'bg-white/30'"></button>
                </template>
            </div>
        </div>
    </div>

    <!-- Lightbox Modal -->
    <div x-show="isOpen" x-cloak
        class="fixed inset-0 z-[1000] flex items-center justify-center bg-primary/95 backdrop-blur-sm p-4 sm:p-8"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">

        <!-- Close Button -->
        <button @click="closeLightbox()" class="absolute top-6 right-6 text-white hover:text-secondary z-20">
            <flux:icon icon="x-mark" class="size-8" />
        </button>

        <!-- Navigation Buttons -->
        <button @click="prev()"
            class="absolute left-4 sm:left-10 top-1/2 -translate-y-1/2 text-white hover:text-secondary z-20 transition-colors">
            <flux:icon icon="chevron-left" class="size-10 sm:size-12" />
        </button>
        <button @click="next()"
            class="absolute right-4 sm:right-10 top-1/2 -translate-y-1/2 text-white hover:text-secondary z-20 transition-colors">
            <flux:icon icon="chevron-right" class="size-10 sm:size-12" />
        </button>

        <!-- Image Container -->
        <div class="relative max-w-5xl w-full h-full flex flex-col items-center justify-center">
            <img :src="images[currentIdx].src" :alt="images[currentIdx].alt"
                class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl border-2 border-white/10">

            <div class="mt-6 text-center">
                <p class="text-white text-lg font-medium" x-text="images[currentIdx].alt"></p>
                <p class="text-white/50 text-sm mt-1" x-text="(currentIdx + 1) + ' / ' + images.length"></p>
            </div>
        </div>
    </div>
</div>