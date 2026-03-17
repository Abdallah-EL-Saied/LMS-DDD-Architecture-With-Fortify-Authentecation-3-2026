<?php

use Livewire\Component;

new class extends Component {
    public array $images = [];

    public function mount()
    {
        // Sample images - keeping same count but planning for a structured mosaic
        $this->images = [
            ['src' => 'assets/hero.webp', 'alt' => __('home.gallery.alt_1'), 'span' => 'lg:col-span-2 lg:row-span-1'],
            ['src' => 'assets/auth.webp', 'alt' => __('home.gallery.alt_2'), 'span' => 'lg:col-span-1 lg:row-span-1'],
            ['src' => 'assets/hero.webp', 'alt' => __('home.gallery.alt_3'), 'span' => 'lg:col-span-1 lg:row-span-2'],
            ['src' => 'assets/auth.webp', 'alt' => __('home.gallery.alt_4'), 'span' => 'lg:col-span-1 lg:row-span-1'],
            ['src' => 'assets/hero.webp', 'alt' => __('home.gallery.alt_5'), 'span' => 'lg:col-span-2 lg:row-span-2'],
            ['src' => 'assets/auth.webp', 'alt' => __('home.gallery.alt_6'), 'span' => 'lg:col-span-1 lg:row-span-2'],
            ['src' => 'assets/hero.webp', 'alt' => __('home.gallery.alt_7'), 'span' => 'lg:col-span-1 lg:row-span-1'],
            ['src' => 'assets/auth.webp', 'alt' => __('home.gallery.alt_8'), 'span' => 'lg:col-span-1 lg:row-span-1'],
            ['src' => 'assets/auth.webp', 'alt' => __('home.gallery.alt_8'), 'span' => 'lg:col-span-2 lg:row-span-1'],
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

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 auto-rows-[200px] gap-4">
            @foreach ($images as $idx => $img)
                <div @click="openLightbox({{ $idx }})"
                    class="relative group overflow-hidden rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500 cursor-zoom-in {{ $img['span'] ?? '' }}">
                    <img src="{{ asset($img['src']) }}" alt="{{ $img['alt'] }}"
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
            <img :src="'/' + images[currentIdx].src" :alt="images[currentIdx].alt"
                class="max-w-full max-h-[80vh] object-contain rounded-lg shadow-2xl border-2 border-white/10">

            <div class="mt-6 text-center">
                <p class="text-white text-lg font-medium" x-text="images[currentIdx].alt"></p>
                <p class="text-white/50 text-sm mt-1" x-text="(currentIdx + 1) + ' / ' + images.length"></p>
            </div>
        </div>
    </div>
</div>