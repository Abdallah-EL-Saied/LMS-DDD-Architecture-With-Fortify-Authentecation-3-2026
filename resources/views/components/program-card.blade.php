@props(['program'])

@php
    $isModel = $program instanceof \App\Infrastructure\Persistence\Eloquent\Models\Program;
    $isEntity = $program instanceof \App\Domains\Program\Entities\Program;
    $isObject = $isModel || $isEntity;
    
    $title = $isObject ? $program->title : $program['title'];
    $desc = $isObject ? $program->short_description : $program['desc'];
    $image = $isObject ? ($program->thumbnail_path ? asset('uploads/' . $program->thumbnail_path) : asset('assets/hero.webp')) : ($program['image'] ?? asset('assets/hero.webp'));
    $badge = $isObject ? $program->badge : ($program['badge'] ?? null);
    $icon = $isObject ? $program->icon : ($program['icon'] ?? null);
    $slug = $isObject ? $program->slug : ($program['slug'] ?? '#');
    $hasVideo = $isObject ? !empty($program->video_url) : false;
    $enrollmentCount = $isObject ? ($isModel ? $program->real_enrollments_count : 100) : 100;
@endphp

<div onclick="window.location.href='{{ $isObject ? route('programs.show', $slug) : '#' }}'"
    class="group bg-surface rounded-2xl border border-zinc-100 shadow-sm hover:shadow-xl hover:-translate-y-2 transition-all duration-400 cursor-pointer relative overflow-hidden flex flex-col h-full">

    {{-- Program Image & Video Overlay --}}
    <div class="relative w-full h-48 overflow-hidden bg-zinc-100">
        <img src="{{ $image }}"
            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            alt="{{ $title }}">


        {{-- Overlay badges if any --}}
        @if($badge)
            <div
                class="absolute top-4 {{ app()->getLocale() === 'ar' ? 'left-4' : 'right-4' }} bg-secondary text-primary text-[10px] font-black px-3 py-1 rounded-full shadow-lg uppercase tracking-wider">
                {{ $badge }}
            </div>
        @endif

        {{-- Icon --}}
        @if($icon)
            <div
                class="absolute bottom-2 {{ app()->getLocale() === 'ar' ? 'right-6' : 'left-6' }} w-12 h-12 rounded-xl flex items-center justify-center shadow-md bg-surface border-4 border-primary z-20">
                <i class="{{ $icon }} text-xl text-primary"></i>
            </div>
        @endif
    </div>

    <div class="p-6 flex flex-col flex-1">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-xl font-bold text-primary {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ $title }}
            </h3>

            <flux:text size="xs" color="zinc" class="flex items-center gap-1">
                <i class="fa-solid fa-users"></i> {{ $enrollmentCount }}
            </flux:text>
        </div>

        <p class="text-zinc-600 leading-relaxed text-sm flex-1">{{ $desc }}</p>

        <div class="mt-6 pt-4 border-t border-zinc-100 flex items-center justify-between">
            <div
                class="flex items-center gap-2 text-primary font-semibold text-sm group-hover:text-tertiary-500 transition-colors">
                <span>{{ __('programs.learn_more') }}</span>
                <i
                    class="fa-solid fa-arrow-{{ app()->getLocale() === 'ar' ? 'left' : 'right' }} text-xs group-hover:translate-x-1 transition-transform"></i>
            </div>
        </div>
    </div>
</div>