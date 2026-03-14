<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts.welcome')] class extends Component {
    public array $allCourses = [];

    public function mount()
    {
        $this->allCourses = [
            [
                'icon' => 'fa-solid fa-quran',
                'title' => __('landing.courses.c1_title'),
                'desc' => __('landing.courses.c1_desc'),
                'color' => 'primary',
                'bg' => 'bg-primary/10',
                'image' => asset('assets/hero.webp'),
                'badge' => __('landing.courses.c1_title'),
            ],
            [
                'icon' => 'fa-solid fa-book-quran',
                'title' => __('landing.courses.c2_title'),
                'desc' => __('landing.courses.c2_desc'),
                'color' => 'tertiary',
                'bg' => 'bg-tertiary/20',
                'image' => asset('assets/auth.webp'),
            ],
            [
                'icon' => 'fa-solid fa-language',
                'title' => __('landing.courses.c3_title'),
                'desc' => __('landing.courses.c3_desc'),
                'color' => 'primary',
                'bg' => 'bg-primary/10',
                'image' => asset('assets/hero.webp'),
            ],
            [
                'icon' => 'fa-solid fa-mosque',
                'title' => __('landing.courses.c4_title'),
                'desc' => __('landing.courses.c4_desc'),
                'color' => 'tertiary',
                'bg' => 'bg-tertiary/20',
                'image' => asset('assets/auth.webp'),
            ],
            [
                'icon' => 'fa-solid fa-scroll',
                'title' => __('landing.courses.c5_title'),
                'desc' => __('landing.courses.c5_desc'),
                'color' => 'primary',
                'bg' => 'bg-primary/10',
                'image' => asset('assets/hero.webp'),
            ],
            [
                'icon' => 'fa-solid fa-child',
                'title' => __('landing.courses.c6_title'),
                'desc' => __('landing.courses.c6_desc'),
                'color' => 'tertiary',
                'bg' => 'bg-tertiary/20',
                'image' => asset('assets/auth.webp'),
            ],
            // More courses can be added here
        ];
    }
};
?>

<!-- Courses Page -->
<div dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="bg-zinc-50 min-h-screen">
    <x-page-header :title="__('global.header.courses')" :subtitle="__('landing.courses.subheading')" />

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($allCourses as $course)
                <x-course-card :course="$course" />
            @endforeach
        </div>
    </div>
</div>