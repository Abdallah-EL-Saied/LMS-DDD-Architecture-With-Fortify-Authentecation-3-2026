@props([
    'type' => 'image', // 'image' or 'video'
    'src' => '',
    'poster' => '',
    'title' => '',
    'subtitle' => '',
])

<div x-data="{ playing: false }" class="relative group aspect-video rounded-3xl overflow-hidden shadow-2xl shadow-zinc-200/50 border border-zinc-100 bg-zinc-50">
    
    <!-- Poster / Static Image -->
    <div x-show="!playing" class="absolute inset-0 z-10">
        <img src="{{ $type === 'video' ? $poster : $src }}" 
             alt="{{ $title }}" 
             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
        
        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>

        <!-- Info Overlay (Optional) -->
        @if($title || $subtitle)
            <div class="absolute bottom-6 start-6 end-6 z-20">
                <div class="flex items-center gap-4">
                    @if($poster && $type === 'video')
                        <div class="size-10 rounded-full border-2 border-white/50 overflow-hidden shrink-0">
                            <img src="{{ $poster }}" class="w-full h-full object-cover" alt="">
                        </div>
                    @endif
                    <div>
                        <h4 class="text-white font-bold text-base leading-tight">{{ $title }}</h4>
                        <p class="text-white/80 text-xs mt-1">{{ $subtitle }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Play Button Overlay -->
        @if($type === 'video')
            <div class="absolute inset-0 flex items-center justify-center z-30">
                <button @click="playing = true" 
                        class="size-16 md:size-20 rounded-full bg-secondary text-primary flex items-center justify-center shadow-2xl shadow-secondary/50 transform transition-all duration-300 hover:scale-110 active:scale-95 group-hover:bg-secondary-light">
                    <i class="fa-solid fa-play text-2xl md:text-3xl"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Video Container -->
    @if($type === 'video')
        <template x-if="playing">
            <div class="absolute inset-0 bg-black z-40">
                @if(str_contains($src, 'youtube.com') || str_contains($src, 'youtu.be'))
                    <iframe 
                        src="https://www.youtube.com/embed/{{ str_contains($src, 'v=') ? explode('v=', $src)[1] : (str_contains($src, 'youtu.be/') ? explode('youtu.be/', $src)[1] : '') }}?autoplay=1&rel=0" 
                        class="w-full h-full" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                @else
                    <video src="{{ $src }}" controls autoplay class="w-full h-full object-contain"></video>
                @endif
                
                <!-- Close Button -->
                <button @click="playing = false" 
                        class="absolute top-4 end-4 z-50 size-10 rounded-full bg-black/50 text-white flex items-center justify-center backdrop-blur-md hover:bg-black/70 transition-colors">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>
        </template>
    @endif
</div>
