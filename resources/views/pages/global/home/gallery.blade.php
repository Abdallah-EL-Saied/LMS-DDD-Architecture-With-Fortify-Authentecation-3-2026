<?php

use Livewire\Component;

new class extends Component {
    public array $images = [];

    public function mount()
    {
        $this->images = [
            ['src' => 'assets/hero.webp', 'alt' => __('landing.gallery.alt_1')],
            ['src' => 'assets/auth.webp', 'alt' => __('landing.gallery.alt_2')],
            ['src' => 'assets/hero.webp', 'alt' => __('landing.gallery.alt_3')],
            ['src' => 'assets/auth.webp', 'alt' => __('landing.gallery.alt_4')],
            ['src' => 'assets/hero.webp', 'alt' => __('landing.gallery.alt_5')],
            ['src' => 'assets/auth.webp', 'alt' => __('landing.gallery.alt_6')],
            ['src' => 'assets/hero.webp', 'alt' => __('landing.gallery.alt_7')],
            ['src' => 'assets/auth.webp', 'alt' => __('landing.gallery.alt_8')],
        ];
    }
};
?>

<div class="py-24 bg-surface" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <x-section-heading 
            :title="__('landing.gallery.heading')" 
            :description="__('landing.gallery.subheading')" 
            show-line
        />

        <div class="columns-1 sm:columns-2 lg:columns-4 gap-4 space-y-4">
            @foreach ($images as $img)
                <div
                    class="relative group overflow-hidden rounded-2xl shadow-md hover:shadow-2xl transition-all duration-500">
                    <img src="{{ asset($img['src']) }}" alt="{{ $img['alt'] }}"
                        class="w-full h-auto object-cover group-hover:scale-110 transition-transform duration-700">

                    <div
                        class="absolute inset-0 bg-gradient-to-t from-primary/80 via-primary/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 flex items-end p-6">
                        <p
                            class="text-white font-medium text-sm transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                            {{ $img['alt'] }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>