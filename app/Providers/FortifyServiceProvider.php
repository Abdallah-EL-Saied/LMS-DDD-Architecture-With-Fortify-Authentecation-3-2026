<?php

namespace App\Providers;

use App\Interfaces\FortifyBridges\CreateNewUser;
use App\Interfaces\FortifyBridges\ResetUserPassword;
use App\Domains\Identity\Enums\UserStatus;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use Illuminate\Validation\ValidationException;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::createUsersUsing(CreateNewUser::class);
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(function () {
            $intended = \Illuminate\Support\Facades\Session::get('url.intended');
            
            // Smart Form Exclusions: if intended URL was a "control/edit/create" page, send to parent index
            if ($intended && str_contains($intended, '/control')) {
                // Trim "/control" and anything after it from the intended url
                $cleanedIntended = preg_replace('/\/control(\/.*)?$/', '', $intended);
                \Illuminate\Support\Facades\Session::put('url.intended', $cleanedIntended);
            }

            return view('pages::auth.login');
        });
        Fortify::verifyEmailView(fn() => view('pages::auth.verify-email'));
        Fortify::twoFactorChallengeView(fn() => view('pages::auth.two-factor-challenge'));
        Fortify::confirmPasswordView(fn() => view('pages::auth.confirm-password'));
        Fortify::registerView(fn() => view('pages::auth.register'));
        Fortify::resetPasswordView(fn() => view('pages::auth.reset-password'));
        Fortify::requestPasswordResetLinkView(fn() => view('pages::auth.forgot-password'));
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        Fortify::authenticateUsing(function (Request $request) {
            $user = \App\Infrastructure\Persistence\Eloquent\Models\User::where('email', $request->email)->first();

            if ($user && \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
                // Check if user is banned
                if ($user->status === UserStatus::BANNED) {
                    throw ValidationException::withMessages([
                        Fortify::username() => [__('This account is blocked. Please contact administration.')],
                    ]);
                }

                return $user;
            }

            return null;
        });
    }
}
