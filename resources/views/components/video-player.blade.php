@props([
    'videoUrl' => null,
    'youtubeEmbedUrl' => null,
    'thumbnailPath' => null,
    'aspectClass' => 'aspect-video',
    'roundedClass' => 'rounded-[32px]',
    'extraClasses' => 'bg-zinc-900 shadow-sm ring-1 ring-zinc-200',
])

<div class="w-full relative group {{ $roundedClass }} overflow-hidden {{ $extraClasses }} {{ $aspectClass }}" x-data="{ playing: false }">
    <template x-if="!playing">
        <div class="absolute inset-0 cursor-pointer" @click="playing = {{ $videoUrl ? 'true' : 'false' }}">
            @if($thumbnailPath)
                <img src="{{ asset('uploads/' . $thumbnailPath) }}" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
            @else
                <div class="w-full h-full bg-zinc-800"></div>
            @endif
            <div class="absolute inset-0 bg-zinc-900/40 group-hover:bg-zinc-900/50 transition-all flex items-center justify-center">
                @if($videoUrl)
                    <div class="size-20 rounded-full bg-secondary text-primary-500 flex items-center justify-center shadow-2xl scale-95 group-hover:scale-100 transition-all duration-500">
                        <flux:icon icon="play" variant="solid" class="size-8 ml-1" />
                    </div>
                @else
                    <div class="size-16 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center">
                        <flux:icon icon="photo" class="size-6 text-white" />
                    </div>
                @endif
            </div>
            
            {{-- Inherited Slot Overlays (Like Course Intro overlays) --}}
            {{ $slot ?? '' }}

        </div>
    </template>
    
    <template x-if="playing">
        <div class="absolute inset-0 bg-black">
            @if(str_contains($youtubeEmbedUrl ?? '', 'youtube.com/embed') || str_contains($videoUrl ?? '', 'youtube.com') || str_contains($videoUrl ?? '', 'youtu.be'))
                @php
                    $embedUrl = $youtubeEmbedUrl ?? $videoUrl;
                    if(!str_contains($embedUrl, 'embed')) {
                        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $match);
                        $videoId = $match[1] ?? '';
                        $embedUrl = $videoId ? "https://www.youtube.com/embed/{$videoId}" : $videoUrl;
                    }
                @endphp
                <iframe class="w-full h-full border-0" src="{{ $embedUrl }}{{ str_contains($embedUrl, '?') ? '&' : '?' }}autoplay=1" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            @elseif(str_ends_with(strtolower($videoUrl ?? ''), '.mp4') || str_ends_with(strtolower($videoUrl ?? ''), '.webm'))
                <video class="w-full h-full object-cover" autoplay controls>
                    <source src="{{ $videoUrl }}" type="video/mp4">
                </video>
            @else
                <div class="flex items-center justify-center h-full">
                    <flux:button variant="subtle" icon="play" href="{{ $videoUrl }}" target="_blank">{{ app()->getLocale() === 'ar' ? 'مشاهدة الفيديو الخارجي' : 'Watch External Video' }}</flux:button>
                </div>
            @endif
        </div>
    </template>
</div>
