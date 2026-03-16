<?php

use Livewire\Component;

new class extends Component {
    public array $courses = [];

    public function mount()
    {
        $this->courses = [
            [
                'icon' => 'fa-solid fa-quran',
                'title' => __('courses.c1_title'),
                'desc' => __('courses.c1_desc'),
                'color' => 'primary',
                'bg' => 'bg-primary/10',
            ],
            [
                'icon' => 'fa-solid fa-book-quran',
                'title' => __('courses.c2_title'),
                'desc' => __('courses.c2_desc'),
                'color' => 'tertiary',
                'bg' => 'bg-tertiary/20',
            ],
            [
                'icon' => 'fa-solid fa-language',
                'title' => __('courses.c3_title'),
                'desc' => __('courses.c3_desc'),
                'color' => 'primary',
                'bg' => 'bg-primary/10',
            ],
            [
                'icon' => 'fa-solid fa-mosque',
                'title' => __('courses.c4_title'),
                'desc' => __('courses.c4_desc'),
                'color' => 'tertiary',
                'bg' => 'bg-tertiary/20',
            ],
            [
                'icon' => 'fa-solid fa-scroll',
                'title' => __('courses.c5_title'),
                'desc' => __('courses.c5_desc'),
                'color' => 'primary',
                'bg' => 'bg-primary/10',
            ],
            [
                'icon' => 'fa-solid fa-child',
                'title' => __('courses.c6_title'),
                'desc' => __('courses.c6_desc'),
                'color' => 'tertiary',
                'bg' => 'bg-tertiary/20',
            ],
        ];
    }
};
?>

<div class="py-24 bg-white" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <x-section-heading :title="__('courses.heading')" :description="__('courses.subheading')" show-line />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($courses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>

        <div class="text-center mt-12">
            <flux:button variant="primary" href="/courses"
                class="bg-primary text-white hover:bg-primary-400 border-none px-10 py-4 text-lg font-bold rounded-full shadow-lg transition-all duration-300 hover:scale-105 active:scale-95 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('courses.view_all') }}
            </flux:button>
        </div>
    </div>
</div>