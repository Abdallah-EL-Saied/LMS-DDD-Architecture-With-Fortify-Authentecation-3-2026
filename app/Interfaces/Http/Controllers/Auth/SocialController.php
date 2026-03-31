<?php

namespace App\Interfaces\Http\Controllers\Auth;

use App\Application\Identity\Actions\HandleSocialLoginAction;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(
        string $provider, 
        HandleSocialLoginAction $loginAction,
        \App\Application\Identity\Actions\LinkSocialAccountAction $linkAction
    ) {
        try {
            $socialUser = Socialite::driver($provider)->user();

            // Case 1: User is already logged in -> Link the account
            if (Auth::check()) {
                $linkAction->execute($socialUser->getId(), Auth::id());
                
                return redirect()->route('external-accounts.edit')->with('status', 'social-account-linked');
            }

            // Case 2: User is NOT logged in -> Try to Login or Register
            $user = $loginAction->execute(
                $socialUser->getId(),
                $socialUser->getEmail(),
                $socialUser->getName() ?: $socialUser->getNickname()
            );

            if ($user->isBanned()) {
                throw new \Exception(__('This account is blocked. Please contact administration.'));
            }

            // Manual login since we are using DDD entities and Fortify doesn't handle this directly
            $eloquentUser = \App\Infrastructure\Persistence\Eloquent\Models\User::find($user->id());

            if ($eloquentUser->two_factor_secret) {
                session(['login.id' => $eloquentUser->id]);
                return redirect()->route('two-factor.login');
            }

            Auth::login($eloquentUser);

            return redirect()->intended(route('dashboard'));
        } catch (\Exception $e) {
            $route = Auth::check() ? 'external-accounts.edit' : 'login';
            
            $bannedMessage = __('This account is blocked. Please contact administration.');
            $message = (config('app.debug') || $e->getMessage() === $bannedMessage) 
                ? $e->getMessage() 
                : __('An error occurred while authenticating. Please try again.');
            
            // Log the detailed error for the developer
            logger()->error('Social Auth Error: ' . $e->getMessage(), [
                'exception' => $e,
                'provider' => $provider
            ]);

            return redirect()->route($route)->withErrors([
                'email' => $message,
            ]);
        }
    }
}
