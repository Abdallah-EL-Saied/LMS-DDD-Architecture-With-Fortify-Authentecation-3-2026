<?php

use Livewire\Component;

new class extends Component {
    public array $reviews = [
        ['name' => 'Ahmed Yahia', 'role' => 'Parent', 'text' => "Excellent experience with Fatema Al-Zahraa Center. My children's recitation and tajweed level improved significantly in a short time. May Allah reward you.", 'rating' => 5],
        ['name' => 'Sarah Mohamed', 'role' => 'Student (Hifz)', 'text' => 'The teachers are very dedicated and the schedules are flexible and suit university hours. Thanks to Allah and then the center, I was able to memorize 15 Juz.', 'rating' => 5],
        ['name' => 'Omar Khaled', 'role' => 'Student (Ijazah)', 'text' => 'An accurate scientific methodology and great attention to correcting articulation points and characteristics. I recommend joining the academy to anyone serious about seeking knowledge.', 'rating' => 5],
        ['name' => 'Mariam Abdullah', 'role' => 'Parent', 'text' => 'I was looking for a reliable place to teach my daughters the Noorania method, and I found here what I was looking for in terms of trust and knowledge.', 'rating' => 4],
    ];
};
?>

<div class="py-24 bg-surface-dark/5" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <x-section-heading :title="__('landing.testimonials.heading')" :description="__('landing.testimonials.subheading')" show-line />

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($reviews as $review)
                <div
                    class="bg-white p-8 rounded-2xl shadow-sm border border-zinc-100 hover:shadow-lg transition-shadow duration-300 flex flex-col items-center text-center">

                    <!-- Stars -->
                    <div class="flex gap-1 mb-6 text-secondary">
                        @for($i = 0; $i < $review['rating']; $i++)
                            <i class="fa-solid fa-star text-lg text-tertiary"></i>
                        @endfor
                        @for($i = $review['rating']; $i < 5; $i++)
                            <i class="fa-solid fa-star text-zinc-300 text-lg"></i>
                        @endfor
                    </div>

                    <p class="text-zinc-600 leading-relaxed mb-8 flex-1 text-sm md:text-base italic">
                        "{{ $review['text'] }}"
                    </p>

                    <div class="mt-auto">
                        <div class="font-bold text-zinc-900">{{ $review['name'] }}</div>
                        <div class="text-sm text-zinc-400 mt-1">{{ $review['role'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>

    </div>
</div>