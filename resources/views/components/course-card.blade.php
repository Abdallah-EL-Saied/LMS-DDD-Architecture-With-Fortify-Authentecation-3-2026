@props(['course'])

<div
    class="group bg-surface rounded-2xl border border-zinc-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-400 cursor-pointer relative overflow-hidden flex flex-col h-full">
    {{-- Course Image --}}
    <div class="relative w-full h-48 overflow-hidden bg-zinc-100">
        <img src="{{ isset($course['image']) ? $course['image'] : asset('assets/hero.webp') }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            alt="{{ $course['title'] }}">

        {{-- Overlay badges if any --}}
        @if(isset($course['badge']))
            <div
                class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} bg-secondary text-primary text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                {{ $course['badge'] }}
            </div>
        @endif

        {{-- Icon --}}
        @if(isset($course['icon']))
            <div
                class="absolute bottom-2 {{ app()->getLocale() === 'ar' ? 'right-6' : 'left-6' }} w-12 h-12 rounded-xl flex items-center justify-center shadow-md bg-surface border-4 border-primary z-20">
                <i class="{{ $course['icon'] }} text-xl text-primary"></i>
            </div>
        @endif
    </div>

    <div class="p-6 flex flex-col flex-1 {{ isset($course['icon']) ? 'pt-8' : '' }}">
        <h3 class="text-xl font-bold text-zinc-900 mb-3 {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
            {{ $course['title'] }}
        </h3>
        <p class="text-zinc-600 leading-relaxed text-sm flex-1">{{ $course['desc'] }}</p>

        <div class="mt-6 pt-4 border-t border-zinc-100 flex items-center justify-between">
            <div
                class="flex items-center gap-2 text-primary font-semibold text-sm group-hover:text-tertiary-500 transition-colors">
                <span>{{ __('courses.learn_more') }}</span>
                <i
                    class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs group-hover:translate-x-1 transition-transform"></i>
            </div>
        </div>
    </div>
</div>