<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark" x-data="{ 
        mobileMenuOpen: false,
        scrolledDown: false, 
        isAtTop: true,
        lastScrollTop: 0,
        handleScroll() {
            if (this.mobileMenuOpen) return;
            let st = window.pageYOffset || document.documentElement.scrollTop;
            this.isAtTop = st <= 10;
            
            if (st > this.lastScrollTop && st > 100) {
                this.scrolledDown = true;
            } else if (st < this.lastScrollTop) {
                this.scrolledDown = false;
            }
            this.lastScrollTop = st <= 0 ? 0 : st;
        }
    }" :class="{ 'overflow-hidden': mobileMenuOpen || $store.pageLoading }" @scroll.window="handleScroll"
    x-on:livewire:navigating.window="$store.pageLoading = true"
    x-on:livewire:navigated.window="$store.pageLoading = false">

<head>
    @include('partials.head')

    <style>
        /* Custom Active Navbar Item Styles */
        .custom-nav-item {
            font-weight: 500;
            color: rgba(255, 255, 255, 0.85) !important;
            transition: all 0.3s ease;
        }

        .custom-nav-item:hover {
            color: var(--color-secondary) !important;
            background-color: rgba(255, 255, 255, 0.08) !important;
        }

        .custom-nav-item[data-current] {
            color: var(--color-secondary) !important;
            background-color: rgba(244, 192, 37, 0.1) !important;
            font-weight: 700;
        }

        /* Search Icon Consistency */
        .header-icon {
            color: rgba(255, 255, 255, 0.8);
            transition: color 0.3s ease;
        }

        .header-icon:hover {
            color: var(--color-secondary);
        }

        /* Dropdown & Profile Menu Unified Branded Styling */
        [data-flux-navmenu],
        [data-flux-menu] {
            background-color: var(--color-primary) !important;
            /* Branded dark teal */
            border: 1px solid rgba(244, 192, 37, 0.2) !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.2) !important;
        }

        [data-flux-navmenu-item],
        [data-flux-menu-item],
        [data-flux-menu] flux-heading,
        [data-flux-menu] flux-text {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        [data-flux-navmenu-item]:hover,
        [data-flux-menu-item]:hover {
            background-color: rgba(244, 192, 37, 0.1) !important;
            color: var(--color-secondary) !important;
        }
    </style>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('pageLoading', false)
        })
    </script>
</head>

<body class="min-h-screen flex flex-col w-full antialiased font-sans"
    :class="{ 'overflow-hidden': mobileMenuOpen || $store.pageLoading }">
    <!-- Page Loader -->
    <x-page-loader />

    <!-- Header Wrapper -->
    <div class="z-[100] w-full transition-transform duration-300 bg-primary" :class="{
        'sticky top-0': !mobileMenuOpen,
        'fixed top-0 translate-y-0': mobileMenuOpen,
        '-translate-y-full': scrolledDown && !mobileMenuOpen,
        'translate-y-0': !scrolledDown && !mobileMenuOpen,
        'shadow-[0_10px_30px_-15px_rgba(0,0,0,0.3)]': !isAtTop && (!scrolledDown || mobileMenuOpen)
    }">
        <div class="w-full h-8 bg-secondary border-b border-black/5 flex items-center relative z-20">
            <flux class="flex items-center justify-between w-full h-full text-[10px] md:text-xs px-8">
                <!-- Left: Contact Info -->
                <div class="flex items-center gap-4 text-primary">
                    <div class="flex items-center gap-1.5 cursor-pointer">
                        <i class="fa-solid fa-phone text-[10px]"></i>
                        <span dir="ltr">+20 112 292 0352</span>
                    </div>
                    <div class="flex items-center gap-1.5 cursor-pointer hidden sm:flex">
                        <i class="fa-solid fa-envelope text-[10px]"></i>
                        <span>info@fatemacenter.com</span>
                    </div>
                </div>

                <!-- Right: Socials -->
                <div class="flex items-center gap-6 h-full">
                    <div class="flex items-center gap-4">
                        <a href="https://www.facebook.com/profile.php?id=61556978948907" target="_blank"
                            class="text-primary hover:scale-110 transition-transform"><i
                                class="fa-brands fa-facebook-f text-sm"></i></a>
                        <a href="https://www.instagram.com/mrkzftmlzhr?igsh=MTRheWJscXF5OGxwbg==" target="_blank"
                            class="text-primary hover:scale-110 transition-transform"><i
                                class="fa-brands fa-instagram text-sm"></i></a>
                        <a href="https://www.youtube.com/@fatemaalzahraa-m9b" target="_blank"
                            class="text-primary hover:scale-110 transition-transform"><i
                                class="fa-brands fa-youtube text-sm"></i></a>
                        <a href="https://wa.me/201122920352" target="_blank"
                            class="text-primary hover:scale-110 transition-transform"><i
                                class="fa-brands fa-whatsapp text-sm"></i></a>
                    </div>
                </div>
            </flux>
        </div>

        <flux:header container
            class=" py-3 w-full flex items-center bg-primary transition-all duration-300 relative z-20">

            <!-- Mobile Header Content -->
            <div class="flex w-full items-center justify-between lg:hidden">
                <button @click="mobileMenuOpen = !mobileMenuOpen"
                    style="background: transparent; border: none; outline: none;"
                    class="text-white hover:bg-white/10 p-1.5 rounded-md cursor-pointer flex items-center justify-center transition-colors">
                    <i class="fa-solid fa-bars-staggered text-2xl" x-show="!mobileMenuOpen"></i>
                    <i class="fa-solid fa-xmark text-2xl" x-show="mobileMenuOpen" x-cloak></i>
                </button>
                <x-app-logo imgUrl="/FZLogo.png" size="size-10" color="bg-secondary" />
            </div>

            <!-- Desktop Content -->
            <x-app-logo imgUrl="/FZLogo.png" class="hidden lg:flex me-14" size="size-12" color="bg-secondary" />

            <flux:navbar class="-mb-px max-lg:hidden me-4 gap-2">
                <flux:navbar.item class="custom-nav-item" href="/" :current="request()->is('/')" wire:navigate>
                    {{ __('global.header.home') }}
                </flux:navbar.item>
                <flux:navbar.item class="custom-nav-item" href="courses" :current="request()->is('courses')"
                    wire:navigate>
                    {{ __('global.header.courses') }}
                </flux:navbar.item>
                <flux:navbar.item class="custom-nav-item" href="about" :current="request()->is('about')" wire:navigate>
                    {{ __('global.header.about') }}
                </flux:navbar.item>
                <flux:navbar.item class="custom-nav-item" href="pricing" :current="request()->is('pricing')"
                    wire:navigate>
                    {{ __('global.header.pricing') }}
                </flux:navbar.item>
                <flux:navbar.item class="custom-nav-item" href="contact" :current="request()->is('contact')"
                    wire:navigate>
                    {{ __('global.header.contact') }}
                </flux:navbar.item>

                <!-- <flux:dropdown>
                    <flux:navbar.item class="custom-nav-item" icon:trailing="chevron-down">
                        {{ __('global.header.favorites') }}
                    </flux:navbar.item>

                    <flux:navmenu>
                        <flux:navmenu.item href="#">{{ __('global.header.fav_hifz') }}</flux:navmenu.item>
                        <flux:navmenu.item href="#">{{ __('global.header.fav_ijazah') }}</flux:navmenu.item>
                        <flux:navmenu.item href="#">{{ __('global.header.fav_personal') }}</flux:navmenu.item>
                    </flux:navmenu>
                </flux:dropdown> -->

                <flux:navbar.item href="#" label="{{ __('global.header.search') }}" class="header-icon">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="gap-3 hidden lg:flex">
                <flux:dropdown>
                    <flux:button variant="subtle" size="sm" class="!text-secondary hover:!bg-white/10 border-none px-2">
                        <i class="fa-solid fa-globe"></i>
                    </flux:button>
                    <flux:navmenu>
                        <flux:navmenu.item :href="route('lang.switch', 'ar')"><span class="cairo-font">العربية</span>
                        </flux:navmenu.item>
                        <flux:navmenu.item :href="route('lang.switch', 'en')">English</flux:navmenu.item>
                    </flux:navmenu>
                </flux:dropdown>

                <flux:navbar.item :href="route('login')"
                    class="!text-white/90 hover:!text-white transition-colors font-medium">
                    {{ __('global.header.login') }}
                </flux:navbar.item>
                <flux:button :href="route('register')" size="sm"
                    class="!bg-secondary !text-primary hover:!bg-secondary/90 border-none font-bold px-5 rounded-lg active:scale-95 transition-transform shadow-lg {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                    {{ __('global.header.register') }}
                </flux:button>
            </flux:navbar>
        </flux:header>

        <!-- Dropdown Mobile Menu Panel (Alpine JS powered) -->
        <div x-show="mobileMenuOpen" style="display: none;"
            class="absolute left-0 top-full w-full overflow-hidden origin-top bg-primary z-50 lg:hidden"
            x-transition:enter="transition ease-out duration-200" x-transition:enter-start="-translate-y-2 opacity-0"
            x-transition:enter-end="translate-y-0 opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="translate-y-0 opacity-100" x-transition:leave-end="-translate-y-2 opacity-0">

            <!-- Mobile Links -->
            <div class="flex flex-col py-3 px-5 gap-2 max-h-[calc(100vh-80px)] overflow-y-auto bg-primary">
                <a href="/" @click="mobileMenuOpen=false"
                    class="text-white/80 hover:text-secondary hover:bg-white/5 px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm font-medium transition-colors {{ request()->is('/') ? 'text-secondary bg-white/10' : '' }}">
                    <i class="fa-solid fa-home w-4 text-center"></i> {{ __('global.header.home') }}
                </a>
                <a href="courses" @click="mobileMenuOpen=false"
                    class="text-white/80 hover:text-secondary hover:bg-white/5 px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm font-medium transition-colors {{ request()->is('courses') ? 'text-secondary bg-white/10' : '' }}">
                    <i class="fa-solid fa-book-open w-4 text-center"></i> {{ __('global.header.courses') }}
                </a>
                <a href="about" @click="mobileMenuOpen=false"
                    class="text-white/80 hover:text-secondary hover:bg-white/5 px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm font-medium transition-colors {{ request()->is('about') ? 'text-secondary bg-white/10' : '' }}">
                    <i class="fa-solid fa-circle-info w-4 text-center"></i> {{ __('global.header.about') }}
                </a>
                <a href="pricing" @click="mobileMenuOpen=false"
                    class="text-white/80 hover:text-secondary hover:bg-white/5 px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm font-medium transition-colors {{ request()->is('pricing') ? 'text-secondary bg-white/10' : '' }}">
                    <i class="fa-solid fa-credit-card w-4 text-center"></i> {{ __('global.header.pricing') }}
                </a>
                <a href="contact" @click="mobileMenuOpen=false"
                    class="text-white/80 hover:text-secondary hover:bg-white/5 px-4 py-2.5 rounded-lg flex items-center gap-3 text-sm font-medium transition-colors {{ request()->is('contact') ? 'text-secondary bg-white/10' : '' }}">
                    <i class="fa-solid fa-envelope w-4 text-center"></i> {{ __('global.header.contact') }}
                </a>

                <hr class="border-white/5 my-1" />

                <!-- Auth Buttons -->
                <div class="flex flex-col gap-2 px-4">
                    <a href="{{ route('login') }}"
                        class="text-white bg-white/5 text-center rounded-lg hover:bg-white/10 px-4 py-2.5 text-sm font-medium transition-colors border border-white/5">{{ __('global.header.login') }}</a>
                    <a href="{{ route('register') }}"
                        class="bg-secondary text-primary font-bold text-center px-4 py-2.5 rounded-lg hover:bg-secondary/90 transition-colors shadow-sm text-sm {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">{{ __('global.header.register') }}</a>
                </div>

                <div class="mt-4 pt-4 border-t border-white/5 flex items-center justify-between px-6 pb-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-3 text-white/50">
                            <i class="fa-solid fa-phone text-[10px] text-secondary/70"></i>
                            <span class="text-[10px]" dir="ltr">+20 112 292 0352</span>
                        </div>
                        <div class="flex items-center gap-3 text-white/50">
                            <i class="fa-solid fa-envelope text-[10px] text-secondary/70"></i>
                            <span class="text-[10px]">info@fatemacenter.com</span>
                        </div>
                    </div>

                    <a href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
                        class="flex items-center gap-2 text-secondary font-bold hover:bg-white/10 px-4 py-2 rounded-lg border border-white/5 transition-colors text-xs active:scale-95">
                        <i class="fa-solid fa-globe text-sm"></i>
                        {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <main class="flex-1 flex flex-col w-full h-full relative z-0">
        {{ $slot }}
    </main>

    @fluxScripts
</body>

</html>