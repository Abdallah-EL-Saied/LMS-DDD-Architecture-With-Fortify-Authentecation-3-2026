<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')

    <style>
        /* Custom Active Navbar Item Styles */
        .custom-nav-item {
            font-weight: 600;
            color: rgba(255, 255, 255, 0.8) !important;
            transition: all 0.2s ease-in-out;
        }

        .custom-nav-item:hover {
            color: white !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }

        .custom-nav-item[data-current]::after {
            display: none !important;
        }

        .custom-nav-item[data-current] {
            color: var(--color-tertiary-300) !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
            border-radius: 0.5rem;
        }

        .dark .custom-nav-item[data-current] {
            color: var(--color-tertiary-300) !important;
            background-color: rgba(197, 160, 89, 0.1) !important;
        }

        .custom-nav-item div {
            font-size: 1rem;
            font-weight: 600;
        }
    </style>
</head>

<body class="min-h-screen flex flex-col w-full antialiased font-sans">
    <flux:header sticky container
        class="top-0 z-50 py-3 w-full flex items-center bg-primary border-b border-primary-400 transition-all duration-300">

        <flux:sidebar.toggle class="lg:hidden text-white hover:bg-white/10" icon="bars-2" />

        <x-app-logo imgUrl="/FZLogo.png" class="hidden lg:flex me-14" size="size-12" color="bg-tertiary-300" />

        <flux:navbar class="-mb-px max-lg:hidden me-4 gap-2">
            <flux:navbar.item class="custom-nav-item" href="/" :current="request()->is('/')" wire:navigate>Home
            </flux:navbar.item>
            <flux:navbar.item class="custom-nav-item" href="courses" :current="request()->is('courses')" wire:navigate>
                Our Courses
            </flux:navbar.item>
            <flux:navbar.item class="custom-nav-item" href="about" :current="request()->is('about')" wire:navigate>About
                Us
            </flux:navbar.item>
            <flux:navbar.item class="custom-nav-item" href="contact" :current="request()->is('contact')" wire:navigate>
                Contact Us
            </flux:navbar.item>

            <flux:dropdown class="max-lg:hidden">
                <flux:navbar.item icon:trailing="chevron-down">Favorites</flux:navbar.item>

                <flux:navmenu>
                    <flux:navmenu.item href="#">Marketing site</flux:navmenu.item>
                    <flux:navmenu.item href="#">Android app</flux:navmenu.item>
                    <flux:navmenu.item href="#">Brand guidelines</flux:navmenu.item>
                </flux:navmenu>
            </flux:dropdown>

            <!-- <flux:separator vertical variant="subtle" class="my-2" /> -->

            <flux:navbar.item icon="magnifying-glass" href="#" label="Search" />
        </flux:navbar>

        <flux:spacer />

        @auth
            <x-desktop-user-menu />
        @else
            <flux:navbar>
                <flux:navbar.item :href="route('login')">Log in</flux:navbar.item>
                <flux:navbar.item :href="route('register')">Register</flux:navbar.item>
            </flux:navbar>
        @endauth

    </flux:header>

    <flux:sidebar sticky collapsible="mobile"
        class="z-70 lg:hidden bg-zinc-50 dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.header>
            <x-app-logo />

            <flux:sidebar.collapse
                class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" href="/" :current="request()->is('/')" wire:navigate>Home</flux:sidebar.item>
            <flux:sidebar.item icon="book-open" href="courses" wire:navigate>Our Courses</flux:sidebar.item>
            <flux:sidebar.item icon="information-circle" href="test" wire:navigate>About Us</flux:sidebar.item>
            <flux:sidebar.item icon="envelope" href="contact" wire:navigate>Contact Us</flux:sidebar.item>

            <flux:sidebar.group expandable heading="Favorites" class="grid">
                <flux:sidebar.item href="#">Marketing site</flux:sidebar.item>
                <flux:sidebar.item href="#">Android app</flux:sidebar.item>
                <flux:sidebar.item href="#">Brand guidelines</flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />
    </flux:sidebar>

    <main class="flex-1 flex flex-col w-full h-full">
        {{ $slot }}
    </main>

    @fluxScripts
</body>

</html>