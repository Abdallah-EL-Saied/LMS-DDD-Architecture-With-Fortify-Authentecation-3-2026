<?php

use App\Concerns\Traits\PasswordValidationRules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Password settings')] class extends Component {
    use PasswordValidationRules;

    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $two_factor_code = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $rules = [
                'current_password' => $this->currentPasswordRules(),
                'password' => $this->passwordRules(),
            ];

            if (Auth::user()->two_factor_secret) {
                $rules['two_factor_code'] = ['required', 'string'];
            }

            $validated = $this->validate($rules);

            if (Auth::user()->two_factor_secret) {
                if (!$this->validateTwoFactorCode($validated['two_factor_code'])) {
                    throw ValidationException::withMessages([
                        'two_factor_code' => [__('The provided two-factor authentication code was invalid.')],
                    ]);
                }
            }
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation', 'two_factor_code');

            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation', 'two_factor_code');

        $this->dispatch('password-updated');
    }

    /**
     * Validate the two-factor authentication code.
     */
    protected function validateTwoFactorCode(string $code): bool
    {
        return app(\Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider::class)
            ->verify(decrypt(Auth::user()->two_factor_secret), $code);
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <flux:heading class="sr-only">{{ __('Password settings') }}</flux:heading>

    <x-pages::settings.layout :heading="__('Update password')" :subheading="__('Ensure your account is using a long, random password to stay secure')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:field>
                <div class="flex items-center gap-2 mb-3">
                    <flux:label>{{ __('Current password') }}</flux:label>
                    <flux:tooltip
                        content="{{ __('If you logged in via Google and have not set a password yet, your default password is password.') }}">
                        <flux:icon.information-circle variant="mini" class="text-zinc-400 cursor-help" />
                    </flux:tooltip>
                </div>

                <flux:input wire:model="current_password" type="password" required autocomplete="current-password"
                    viewable />

                <flux:error name="current_password" />
            </flux:field>
            <flux:input wire:model="password" :label="__('New password')" type="password" required
                autocomplete="new-password" viewable />
            <flux:input wire:model="password_confirmation" :label="__('Confirm password')" type="password" required
                autocomplete="new-password" viewable />

            @if (Auth::user()->two_factor_secret)
                <flux:input wire:model="two_factor_code" :label="__('Two-factor code')" type="text" inputmode="numeric"
                    required :placeholder="__('Enter your 2FA code')" />
            @endif

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full" data-test="update-password-button">
                        {{ __('Save') }}
                    </flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-pages::settings.layout>
</section>