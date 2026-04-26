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
        $this->app->bind(
            \App\Domains\Specialization\RepositoryInterface\ISpecializationRepository::class,
            \App\Infrastructure\Repositories\EloquentSpecializationRepository::class
        );
        $this->app->bind(
            \App\Domains\Recruitment\RepositoryInterface\IJobApplicationRepository::class,
            \App\Infrastructure\Repositories\EloquentJobApplicationRepository::class
        );
        $this->app->bind(
            \App\Domains\Academy\RepositoryInterface\IAcademyScheduleRepository::class,
            \App\Infrastructure\Repositories\EloquentAcademyScheduleRepository::class
        );
        $this->app->bind(
            \App\Domains\Program\RepositoryInterface\IProgramRepository::class,
            \App\Infrastructure\Repositories\EloquentProgramRepository::class
        );
        $this->app->bind(
            \App\Domains\Program\RepositoryInterface\IBundleRepository::class,
            \App\Infrastructure\Repositories\EloquentBundleRepository::class
        );
    }
}
