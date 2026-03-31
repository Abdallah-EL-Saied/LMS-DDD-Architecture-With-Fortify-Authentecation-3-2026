<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 antialiased font-sans">
    <flux:sidebar sticky collapsible class="bg-white dark:bg-zinc-900 border-r border-zinc-200 dark:border-zinc-800">
        <flux:sidebar.header>
            <x-app-logo :name="'Fatema Al-Zahraa'" :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />

            <flux:spacer />

            <flux:sidebar.collapse />
        </flux:sidebar.header>

        <!-- <flux:sidebar.search placeholder="{{ __('global.header.search_placeholder') }}" /> -->

        <flux:sidebar.nav>
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                {{ __('global.sidebar.dashboard') }}
            </flux:sidebar.item>

            {{-- Admin Sidebar --}}
            @hasrole('admin')
                <flux:sidebar.group expandable icon="users" heading="{{ __('global.sidebar.users') }}">
                    <flux:sidebar.item icon="user" :href="route('users.index', ['roleFilter' => 'student'])" :current="request('roleFilter') === 'student'" wire:navigate>{{ __('global.sidebar.students') }}</flux:sidebar.item>
                    <flux:sidebar.item icon="user-group" :href="route('users.index', ['roleFilter' => 'teacher'])" :current="request('roleFilter') === 'teacher'" wire:navigate>{{ __('global.sidebar.teachers') }}</flux:sidebar.item>
                </flux:sidebar.group>
                <flux:sidebar.item icon="circle-stack" href="#" wire:navigate>{{ __('global.sidebar.circles') }}</flux:sidebar.item>
                <flux:sidebar.item icon="calendar" href="#" wire:navigate>{{ __('global.sidebar.schedule') }}</flux:sidebar.item>
                <flux:sidebar.item icon="document-text" href="#" wire:navigate>{{ __('global.sidebar.exams') }}</flux:sidebar.item>
                <flux:sidebar.item icon="check-badge" href="#" wire:navigate>{{ __('global.sidebar.attendance') }}</flux:sidebar.item>
                <flux:sidebar.item icon="banknotes" href="#" wire:navigate>{{ __('global.sidebar.finance') }}</flux:sidebar.item>
                <flux:sidebar.item icon="presentation-chart-bar" href="#" wire:navigate>{{ __('global.sidebar.reports') }}</flux:sidebar.item>
            @endhasrole

            {{-- Teacher Sidebar --}}
            @hasrole('teacher')
                <flux:sidebar.item icon="users" href="#" wire:navigate>{{ __('global.sidebar.my_students') }}</flux:sidebar.item>
                <flux:sidebar.item icon="circle-stack" href="#" wire:navigate>{{ __('global.sidebar.my_circles') }}</flux:sidebar.item>
                <flux:sidebar.item icon="calendar" href="#" wire:navigate>{{ __('global.sidebar.my_schedule') }}</flux:sidebar.item>
                <flux:sidebar.item icon="clipboard-document-list" href="#" wire:navigate>{{ __('global.sidebar.assignments') }}</flux:sidebar.item>
                <flux:sidebar.item icon="academic-cap" href="#" wire:navigate>{{ __('global.sidebar.grades') }}</flux:sidebar.item>
                <flux:sidebar.item icon="folder" href="#" wire:navigate>{{ __('global.sidebar.resources') }}</flux:sidebar.item>
                @endhasrole
                
                {{-- Student Sidebar --}}
                @hasrole('student')
                <flux:sidebar.item icon="circle-stack" href="#" wire:navigate>{{ __('global.sidebar.my_circles') }}</flux:sidebar.item>
                <flux:sidebar.item icon="calendar" href="#" wire:navigate>{{ __('global.sidebar.my_schedule') }}</flux:sidebar.item>
                <flux:sidebar.item icon="clipboard-document-list" href="#" wire:navigate>{{ __('global.sidebar.assignments') }}</flux:sidebar.item>
                <flux:sidebar.item icon="academic-cap" href="#" wire:navigate>{{ __('global.sidebar.my_grades') }}</flux:sidebar.item>
                <flux:sidebar.item icon="chart-bar" href="#" wire:navigate>{{ __('global.sidebar.progress') }}</flux:sidebar.item>
                <flux:sidebar.item icon="trophy" href="#" wire:navigate>{{ __('global.sidebar.certificates') }}</flux:sidebar.item>
                @endhasrole

                <flux:sidebar.item icon="envelope" href="#" wire:navigate>{{ __('global.sidebar.messages') }}</flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:sidebar.spacer />

        <flux:dropdown position="top" align="start" class="max-lg:hidden">
            <flux:sidebar.profile :avatar="auth()->user()->avatar ?? 'https://fluxui.dev/img/demo/user.png'"
                :name="auth()->user()->name" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer">
                        {{ __('global.sidebar.logout') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <flux:header sticky class="block! bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800">
        <flux:navbar class="w-full">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:navbar.item icon="bell" badge="3" href="#" label="{{ __('global.sidebar.messages') }}" />

            <flux:dropdown position="top" align="start">
                <flux:profile :avatar="auth()->user()->avatar ?? 'https://fluxui.dev/img/demo/user.png'" />

                <flux:menu>
                    <flux:menu.radio.group>
                        <flux:menu.radio checked>{{ auth()->user()->name }}</flux:menu.radio>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                            class="w-full cursor-pointer">
                            {{ __('global.sidebar.logout') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:navbar>
    </flux:header>

    <flux:main class="relative bg-zinc-50 dark:bg-zinc-950" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        {{ $slot }}
    </flux:main>

    @fluxScripts
</body>

</html>