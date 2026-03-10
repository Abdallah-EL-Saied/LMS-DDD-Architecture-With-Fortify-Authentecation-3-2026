<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
        <div
            class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
            <div class="absolute inset-0 bg-neutral-900">
                @if (isset($cover))
                    {{ $cover }}
                @else
                    <!-- <div class="absolute inset-0 bg-linear-to-b from-neutral-800 to-neutral-950 opacity-50"></div> -->
                    <!-- <x-placeholder-pattern class="absolute inset-0 size-full stroke-white/10" /> -->
                    <img src="/assets/auth.webp" class="absolute inset-0 object-cover w-full h-full opacity-60">
                    <div class="absolute inset-0 bg-linear-to-t from-neutral-950/80 to-transparent"></div>
                @endif
            </div>
            <a href="{{ route('home') }}"
                class="absolute left-1/2 -translate-x-1/2 top-10 z-20 flex items-center text-lg font-medium"
                wire:navigate>
                <x-app-logo color="bg-tertiary" imgUrl="/FZLogo.png" size="size-30" />
            </a>
            <!-- 
            @php
                [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
            @endphp

            <div class="relative z-20 mt-auto text-white">
                <blockquote class="space-y-2 text-white">
                    <flux:heading class="text-white" size="lg">&ldquo;{{ trim($message) }}&rdquo;</flux:heading>
                    <footer class="text-white">
                        <flux:heading class="text-white">{{ trim($author) }}</flux:heading>
                    </footer>
                </blockquote>
            </div> -->
        </div>
        <div class="w-full lg:p-8">
            <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px] my-10">
                <a href="{{ route('home') }}" class="z-20 flex items-center gap-2 font-medium lg:hidden" wire:navigate>
                    <x-app-logo imgUrl="/FZLogo.png" size="size-30" class="self-center lg:hidden" />
                </a>
                {{ $slot }}
            </div>
        </div>
    </div>
    @fluxScripts
</body>

</html>