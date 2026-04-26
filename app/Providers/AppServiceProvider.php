<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Application\Identity\Listeners\UpdateLastLoginListener;
use App\Infrastructure\Observers\BundleSubscriptionObserver;
use App\Infrastructure\Persistence\Eloquent\Models\BundleSubscription;


use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
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
        $this->configureDefaults();

        View::composer('*', function ($view) {
            if (str_contains($view->getName(), 'sidebar')) {
                app(\App\Http\ViewComposers\SidebarComposer::class)->compose($view);
            }
        });

        Event::listen(
            Login::class,
            UpdateLastLoginListener::class
        );

        // Auto Best Seller calculation on subscription events
        BundleSubscription::observe(BundleSubscriptionObserver::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null,
        );
    }
}
