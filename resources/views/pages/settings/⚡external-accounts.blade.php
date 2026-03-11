<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('External accounts')] class extends Component {
    /**
     * Get the current user.
     */
    public function getUserProperty()
    {
        return Auth::user();
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('External accounts') }}</flux:heading>

    <x-pages::settings.layout :heading="__('External accounts')" :subheading="__('Manage your connected social accounts')">
        <x-action-message class="mb-4" on="social-account-linked">
            {{ __('Account linked successfully.') }}
        </x-action-message>

        @if (session('status') === 'social-account-linked')
            <flux:subheading size="sm" class="mb-4 text-green-500 font-medium">
                {{ __('Account linked successfully.') }}
            </flux:subheading>
        @endif
        <div class="mt-6 space-y-6">
            <div class="flex items-center justify-between p-4 border rounded-lg border-zinc-200 dark:border-zinc-700">
                <div class="flex items-center gap-3">
                    <svg class="size-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4" />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853" />
                        <path
                            d="M5.84 14.1c-.22-.66-.35-1.36-.35-2.1s.13-1.44.35-2.1V7.06H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.94l3.66-2.84z"
                            fill="#FBBC05" />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.66l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335" />
                    </svg>
                    <div>
                        <flux:heading level="3" size="sm">Google</flux:heading>
                        <flux:subheading size="xs">
                            @if (Auth::user()->google_id)
                                {{ __('Connected') }}
                            @else
                                {{ __('Not connected') }}
                            @endif
                        </flux:subheading>
                    </div>
                </div>

                @if (Auth::user()->google_id)
                    <flux:button variant="danger" size="sm" class="cursor-not-allowed opacity-50" disabled>
                        {{ __('Disconnect') }}
                    </flux:button>
                @else
                    <flux:button :href="route('social.redirect', ['provider' => 'google'])" variant="primary" size="sm">
                        {{ __('Connect') }}
                    </flux:button>
                @endif
            </div>

            <flux:subheading size="xs" class="mt-2 text-zinc-500">
                {{ __('Only Google is currently supported. More providers coming soon.') }}
            </flux:subheading>
        </div>
    </x-pages::settings.layout>
</section>