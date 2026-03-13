<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

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
            background-color: #1a4441 !important;
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

        /* Sidebar Branded Styling */
        [data-flux-sidebar] {
            background-color: #1a4441 !important;
            /* Branded dark teal */
            border-right: 1px solid rgba(244, 192, 37, 0.1) !important;
            z-index: 1000 !important;
            top: 0 !important;
        }

        [data-flux-sidebar] [data-flux-sidebar-item] {
            color: rgba(255, 255, 255, 0.8) !important;
        }

        [data-flux-sidebar] [data-flux-sidebar-item][data-current] {
            color: var(--color-secondary) !important;
            background-color: rgba(244, 192, 37, 0.1) !important;
        }

        [data-flux-sidebar] [data-flux-sidebar-item]:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: white !important;
        }

        [data-flux-sidebar-group] flux-sidebar-heading {
            color: rgba(255, 255, 255, 0.5) !important;
        }

        /* Profile Toggle Consistency */
        [data-flux-sidebar-profile] {
            color: rgba(255, 255, 255, 0.9) !important;
        }

        [data-flux-sidebar-profile] flux-icon {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        [data-flux-sidebar-profile]:hover {
            background-color: rgba(255, 255, 255, 0.08) !important;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col w-full antialiased font-sans">
    <div class="w-full h-8 bg-secondary backdrop-blur-md border-b border-white/5 flex items-center">
        <flux class="flex items-center justify-between w-full h-full text-[10px] md:text-xs px-8">
            <!-- Left: Contact Info -->
            <div class="flex items-center gap-4 text-primary">
                <div class="flex items-center gap-1.5 cursor-pointer">
                    <flux:icon icon="phone" class="size-3" />
                    <span dir="ltr">+20 100 000 0000</span>
                </div>
                <div class="flex items-center gap-1.5 cursor-pointer hidden sm:flex">
                    <flux:icon icon="envelope" class="size-3" />
                    <span>info@fz-academy.com</span>
                </div>
            </div>

            <!-- Right: Socials -->
            <div class="flex items-center gap-6 h-full">
                <!-- Social Icons -->
                <div class="flex items-center gap-4">
                    <a href="#" class="text-primary hover:scale-110 transition-transform" title="Facebook">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="text-primary hover:scale-110 transition-transform" title="Instagram">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="text-primary hover:scale-110 transition-transform" title="YouTube">
                        <i class="fa-brands fa-youtube text-sm"></i>
                    </a>
                    <a href="#" class="text-primary hover:scale-110 transition-transform" title="TikTok">
                        <i class="fa-brands fa-tiktok text-sm"></i>
                    </a>
                </div>
            </div>
            </flux:container>
    </div>

    <flux:header container class=" py-3 w-full flex items-center bg-primary transition-all duration-300">

        <flux:sidebar.toggle class="lg:hidden text-white hover:bg-white/10" icon="bars-2" />

        <x-app-logo imgUrl="/FZLogo.png" class="hidden lg:flex me-14" size="size-12" color="bg-secondary" />

        <flux:navbar class="-mb-px max-lg:hidden me-4 gap-2">
            <flux:navbar.item class="custom-nav-item" href="/" :current="request()->is('/')" wire:navigate>
                {{ __('global.header.home') }}
            </flux:navbar.item>
            <flux:navbar.item class="custom-nav-item" href="courses" :current="request()->is('courses')" wire:navigate>
                {{ __('global.header.courses') }}
            </flux:navbar.item>
            <flux:navbar.item class="custom-nav-item" href="about" :current="request()->is('about')" wire:navigate>
                {{ __('global.header.about') }}
            </flux:navbar.item>
            <flux:navbar.item class="custom-nav-item" href="contact" :current="request()->is('contact')" wire:navigate>
                {{ __('global.header.contact') }}
            </flux:navbar.item>

            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item class="custom-nav-item" icon:trailing="chevron-down">
                    {{ __('global.header.favorites') }}
                </flux:navbar.item>

                <flux:navmenu>
                    <flux:navmenu.item href="#">{{ __('global.header.fav_hifz') }}</flux:navmenu.item>
                    <flux:navmenu.item href="#">{{ __('global.header.fav_ijazah') }}</flux:navmenu.item>
                    <flux:navmenu.item href="#">{{ __('global.header.fav_personal') }}</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>

            <!-- <flux:separator vertical variant="subtle" class="my-2" /> -->

            <flux:navbar.item icon="magnifying-glass" href="#" label="{{ __('global.header.search') }}"
                class="header-icon" />
        </flux:navbar>

        <flux:spacer />

        <flux:navbar class="gap-3">
            <flux:dropdown class="hidden lg:block">
                <flux:button variant="subtle" size="sm" class="!text-secondary hover:!bg-white/10 border-none px-2"
                    icon="language">
                </flux:button>
                <flux:navmenu>
                    <flux:navmenu.item :href="route('lang.switch', 'ar')" x-data
                        @click="localStorage.setItem('locale', 'ar')"><span class="cairo-font">العربية</span>
                    </flux:navmenu.item>
                    <flux:navmenu.item :href="route('lang.switch', 'en')" x-data
                        @click="localStorage.setItem('locale', 'en')">English</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>

            <flux:navbar.item :href="route('login')"
                class="!text-white/90 hover:!text-white transition-colors font-medium">{{ __('global.header.login') }}
            </flux:navbar.item>
            <flux:button :href="route('register')" size="sm"
                class="!bg-secondary !text-primary hover:!bg-secondary/90 border-none font-bold px-5 rounded-lg active:scale-95 transition-transform shadow-lg {{ app()->getLocale() === 'ar' ? 'cairo-font' : '' }}">
                {{ __('global.header.register') }}
            </flux:button>
        </flux:navbar>

    </flux:header>

    <flux:sidebar sticky collapsible="mobile" class="fixed !top-0 !h-screen !z-[999] lg:hidden">
        <flux:sidebar.header>
            <x-app-logo imgUrl="/FZLogo.png" size="size-12" color="bg-secondary" />

            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="/" :current="request()->is('/')" wire:navigate>
                {{ __('global.header.home') }}
            </flux:sidebar.item>
            <flux:sidebar.item icon="book-open" href="courses" wire:navigate>{{ __('global.header.courses') }}
            </flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="test" wire:navigate>{{ __('global.header.about') }}
            </flux:sidebar.item>
            <flux:sidebar.item icon="envelope" href="contact" wire:navigate>{{ __('global.header.contact') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="language"
                href="{{ route('lang.switch', app()->getLocale() === 'ar' ? 'en' : 'ar') }}"
                class="text-secondary! font-bold">
                {{ app()->getLocale() === 'ar' ? 'English' : 'العربية' }}
            </flux:sidebar.item>

            <flux:sidebar.group expandable heading="{{ __('global.header.favorites') }}" class="grid">
                <flux:sidebar.item href="#">{{ __('global.header.fav_hifz') }}</flux:sidebar.item>
                <flux:sidebar.item href="#">{{ __('global.header.fav_ijazah') }}</flux:sidebar.item>
                <flux:sidebar.item href="#">{{ __('global.header.fav_personal') }}</flux:sidebar.item>
            </flux:sidebar.group>

        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <div class="mt-6 pt-6 border-t border-white/10 flex flex-col gap-4 px-2">
            <div class="flex items-center gap-3 text-white/70">
                <flux:icon icon="phone" class="size-4 text-secondary" />
                <span class="text-sm" dir="ltr">+20 100 000 0000</span>
            </div>
            <div class="flex items-center gap-3 text-white/70">
                <flux:icon icon="envelope" class="size-4 text-secondary" />
                <span class="text-sm">info@fz-academy.com</span>
            </div>
        </div>
    </flux:sidebar>

    <main class="flex-1 flex flex-col w-full h-full">
        {{ $slot }}
    </main>

    @fluxScripts
</body>

</html>