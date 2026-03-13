<?php

use Livewire\Component;

new class extends Component {
    public array $courses = [];

    public function mount()
    {
        $this->courses = [
            [
                'icon' => 'fa-solid fa-quran',
                'title' => __('landing.courses.c1_title'),
                'desc' => __('landing.courses.c1_desc'),
                'color' => 'primary',
                'bg' => 'bg-primary/10',
            ],
            [
                'icon' => 'fa-solid fa-book-quran',
                'title' => __('landing.courses.c2_title'),
                'desc' => __('landing.courses.c2_desc'),
                'color' => 'tertiary',
                'bg' => 'bg-tertiary/20',
            ],
            [
                'icon' => 'fa-solid fa-language',
                'title' => __('landing.courses.c3_title'),
                'desc' => __('landing.courses.c3_desc'),
                'color' => 'primary',
                'bg' => 'bg-primary/10',
            ],
            [
                'icon' => 'fa-solid fa-mosque',
                'title' => __('landing.courses.c4_title'),
                'desc' => __('landing.courses.c4_desc'),
                'color' => 'tertiary',
                'bg' => 'bg-tertiary/20',
            ],
            [
                'icon' => 'fa-solid fa-scroll',
                'title' => __('landing.courses.c5_title'),
                'desc' => __('landing.courses.c5_desc'),
                'color' => 'primary',
                'bg' => 'bg-primary/10',
            ],
            [
                'icon' => 'fa-solid fa-child',
                'title' => __('landing.courses.c6_title'),
                'desc' => __('landing.courses.c6_desc'),
                'color' => 'tertiary',
                'bg' => 'bg-tertiary/20',
            ],
        ];
    }
};
?>

<div class="py-24 bg-white" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <x-section-heading :title="__('landing.courses.heading')" :description="__('landing.courses.subheading')"
            show-line />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($courses as $course)
                <div
                    class="group bg-surface rounded-2xl p-8 border border-zinc-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-400 cursor-pointer relative overflow-hidden">
                    {{-- Decorative corner --}}
                    <div
                        class="absolute top-0 {{ app()->getLocale() === 'ar' ? 'left-0' : 'right-0' }} w-24 h-24 bg-primary/5 rounded-bl-full group-hover:bg-tertiary/10 transition-colors duration-400">
                    </div>

                    <div
                        class="w-16 h-16 {{ $course['bg'] }} rounded-2xl flex items-center justify-center mb-6 text-{{ $course['color'] }} group-hover:scale-110 transition-transform duration-300">
                        <i class="{{ $course['icon'] }} text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 mb-3 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                        {{ $course['title'] }}
                    </h3>
                    <p class="text-zinc-600 leading-relaxed text-sm">{{ $course['desc'] }}</p>

                    <div
                        class="mt-6 flex items-center gap-2 text-primary font-semibold text-sm group-hover:text-tertiary-500 transition-colors">
                        <span>{{ __('landing.courses.learn_more') }}</span>
                        <i
                            class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs group-hover:translate-x-1 transition-transform"></i>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-center mt-12">
            <flux:button variant="primary" href="/courses"
                class="bg-primary text-white hover:bg-primary-400 border-none px-10 py-4 text-lg font-bold rounded-full shadow-lg transition-all duration-300 hover:scale-105 active:scale-95 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('landing.courses.view_all') }}
            </flux:button>
        </div>
    </div>
</div>