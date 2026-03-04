<?php

namespace App\Providers;

use App\Domains\Identity\RepositoryInterface\IUserRepository;
use App\Infrastructure\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;

class ReposProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(IUserRepository::class, EloquentUserRepository::class);
    }
}
