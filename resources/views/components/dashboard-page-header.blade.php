@props(['breadcrumbs' => [], 'title' => ''])

<div class="flex flex-col gap-6">
    @if(count($breadcrumbs) > 0)
        <flux:breadcrumbs>
            @foreach($breadcrumbs as $label => $url)
                @if(is_int($label))
                    <flux:breadcrumbs.item>{{ $url }}</flux:breadcrumbs.item>
                @else
                    <flux:breadcrumbs.item href="{{ $url }}" wire:navigate>{{ $label }}</flux:breadcrumbs.item>
                @endif
            @endforeach
        </flux:breadcrumbs>
    @endif
    
    <div class="flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            @if($title)
                <flux:heading size="xl">{{ $title }}</flux:heading>
            @endif
            
            {{ $slot }}
        </div>
        
        @if(isset($actions))
            <div class="flex items-center gap-2">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>
