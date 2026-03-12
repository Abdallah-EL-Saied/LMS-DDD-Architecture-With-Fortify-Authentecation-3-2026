<?php

use Livewire\Component;

new class extends Component {
    //
};
?>

<div class="py-16 bg-primary relative overflow-hidden text-center text-white"
    dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <!-- Decorative background elements -->
    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/4">
    </div>
    <div
        class="absolute bottom-0 left-0 w-80 h-80 bg-tertiary/10 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3">
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 md:gap-12">

            <div x-data="{ count: 0, target: 1200, started: false }"
                x-intersect.once="started = true; let i = setInterval(() => { if(count < target) { count += Math.ceil(target/50); } else { count = target; clearInterval(i); } }, 40)">
                <div class="text-4xl md:text-5xl lg:text-6xl font-black text-tertiary mb-2 drop-shadow-md flex justify-center items-center gap-1"
                    dir="ltr">
                    +<span x-text="count">0</span>
                </div>
                <div class="text-sm md:text-lg font-medium text-white/90">{{ __('landing.stats.students') }}</div>
            </div>

            <div x-data="{ count: 0, target: 85, started: false }"
                x-intersect.once="started = true; let i = setInterval(() => { if(count < target) { count += Math.ceil(target/30); } else { count = target; clearInterval(i); } }, 50)">
                <div class="text-4xl md:text-5xl lg:text-6xl font-black text-tertiary mb-2 drop-shadow-md flex justify-center items-center gap-1"
                    dir="ltr">
                    +<span x-text="count">0</span>
                </div>
                <div class="text-sm md:text-lg font-medium text-white/90">{{ __('landing.stats.teachers') }}</div>
            </div>

            <div x-data="{ count: 0, target: 450, started: false }"
                x-intersect.once="started = true; let i = setInterval(() => { if(count < target) { count += Math.ceil(target/40); } else { count = target; clearInterval(i); } }, 45)">
                <div class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-2 drop-shadow-md flex justify-center items-center gap-1"
                    dir="ltr">
                    +<span x-text="count">0</span>
                </div>
                <div class="text-sm md:text-lg font-medium text-white/80">{{ __('landing.stats.graduates') }}</div>
            </div>

            <div x-data="{ count: 0, target: 15, started: false }"
                x-intersect.once="started = true; let i = setInterval(() => { if(count < target) { count += 1; } else { count = target; clearInterval(i); } }, 100)">
                <div class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-2 drop-shadow-md flex justify-center items-center gap-1"
                    dir="ltr">
                    +<span x-text="count">0</span>
                </div>
                <div class="text-sm md:text-lg font-medium text-white/80">{{ __('landing.stats.years') }}</div>
            </div>

        </div>
    </div>
</div>