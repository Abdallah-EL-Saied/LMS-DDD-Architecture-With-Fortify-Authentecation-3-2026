<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;

new #[Layout('layouts.welcome')] class extends Component {

    #[Computed]
    public function goals()
    {
        return [
            ['icon' => 'fa-solid fa-book-open', 'title' => __('about.goal_1')],
            ['icon' => 'fa-solid fa-graduation-cap', 'title' => __('about.goal_2')],
            ['icon' => 'fa-solid fa-heart', 'title' => __('about.goal_3')],
            ['icon' => 'fa-solid fa-lightbulb', 'title' => __('about.goal_4')],
            ['icon' => 'fa-solid fa-certificate', 'title' => __('about.goal_5')],
        ];
    }

    #[Computed]
    public function faqs()
    {
        return [
            [
                'question' => __('faq.q1'),
                'answer' => __('faq.a1')
            ],
            [
                'question' => __('faq.q2'),
                'answer' => __('faq.a2')
            ],
            [
                'question' => __('faq.q3'),
                'answer' => __('faq.a3')
            ],
            [
                'question' => __('faq.q4'),
                'answer' => __('faq.a4')
            ],
            [
                'question' => __('faq.q5'),
                'answer' => __('faq.a5')
            ],
        ];
    }
};
?>

<!-- About Us Page -->
<div class="bg-surface" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <x-page-header :title="__('global.header.about')" :subtitle="__('about.desc_1')" />

    <!-- About Content Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
            <!-- Text Content -->
            <div class="{{ app()->getLocale() === 'ar' ? 'lg:order-1' : 'lg:order-2' }}">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-primary/5 text-primary text-xs font-bold uppercase tracking-wider mb-6">
                    <span class="size-2 rounded-full bg-primary animate-pulse"></span>
                    {{ __('about.heading') }}
                </div>

                <h2 class="text-4xl font-extrabold text-zinc-900 mb-8 leading-tight">
                    {!! nl2br(__('about.desc_1')) !!}
                </h2>

                <div class="text-lg text-zinc-600 space-y-6 leading-relaxed">
                    <p>{{ __('about.desc_2') }}</p>

                </div>
            </div>

            <!-- Reusable Media Frame -->
            <div class="{{ app()->getLocale() === 'ar' ? 'lg:order-2' : 'lg:order-1' }}">
                <x-media-frame type="video" src="https://www.youtube.com/watch?v=RVwqYjIuNMI"
                    poster="{{ asset('assets/hero.webp') }}" title="Fatima Al-Zahra Center"
                    subtitle="Excellence in Quranic Education" />
            </div>
        </div>

        <!-- Goals Section -->
        <div class="mt-32">
            <x-section-heading :title="__('about.goals_heading')" />

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @foreach ($this->goals as $goal)
                    <div
                        class="relative bg-white p-8 rounded-[2rem] shadow-lg shadow-zinc-200/50 border border-zinc-100 overflow-hidden">
                        <div class="flex absolute top-0 end-0 ">
                            <div class="size-20 bg-primary/5 rounded-es-[4rem] -translate-y-6">
                            </div>
                            <div class="size-10 bg-primary/10 rounded-es-3xl mr-2"></div>
                        </div>

                        <!-- Boxed Icon -->
                        <div class="size-16 rounded-2xl bg-secondary flex items-center justify-center text-primary mb-8 ">
                            <i class="{{ $goal['icon'] }} text-2xl"></i>
                        </div>

                        <h3 class="text-lg font-bold text-zinc-900 mb-4 leading-snug">
                            {{ $goal['title'] }}
                        </h3>

                        <p class="text-sm text-zinc-500 leading-relaxed">
                            {{ __('home.features.f1_desc') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="mt-32 bg-primary/5 rounded-[3rem] p-8 md:p-16 border border-zinc-100">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-16">
                <div class="lg:col-span-1">
                    <h2 class="text-3xl font-bold text-zinc-900 mb-6">
                        {{ __('faq.heading') }}
                    </h2>
                    <p class="text-zinc-600 mb-8 leading-relaxed">
                        {{ __('faq.subheading') }}
                    </p>
                    <div class="p-6 rounded-3xl bg-secondary/10 border border-secondary/20">
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="size-12 rounded-full bg-secondary flex items-center justify-center text-primary">
                                <flux:icon icon="chat-bubble-left-right" variant="solid" class="size-6" />
                            </div>
                            <div class="font-bold text-zinc-900">
                                {{ __('global.header.contact') }}؟
                            </div>
                        </div>
                        <p class="text-xs text-zinc-600 mb-4">
                            {{ __('home.features.f5_desc') }}
                        </p>
                        <a href="/contact" class="text-primary font-bold text-sm hover:underline" wire:navigate>
                            {{ __('programs.learn_more') }} &larr;
                        </a>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <x-faq :items="$this->faqs" />
                </div>
            </div>
        </div>
    </div>
</div>