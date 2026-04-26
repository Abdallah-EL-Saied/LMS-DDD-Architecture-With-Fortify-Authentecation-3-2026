<?php

namespace App\Http\ViewComposers;

use Illuminate\View\View;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use App\Domains\Specialization\RepositoryInterface\ISpecializationRepository;
use App\Domains\Recruitment\RepositoryInterface\IJobApplicationRepository;
use App\Domains\Program\RepositoryInterface\IProgramRepository;
use App\Domains\Program\RepositoryInterface\IBundleRepository;

class SidebarComposer
{
    public function __construct(
        protected IUserRepository $userRepo,
        protected ISpecializationRepository $specRepo,
        protected IJobApplicationRepository $jobRepo,
        protected IProgramRepository $programRepo,
        protected IBundleRepository $bundleRepo
    ) {}

    public function compose(View $view)
    {
        $view->with('sidebarStats', [
            'students_count' => $this->userRepo->count(['roles.name' => 'student']),
            'teachers_count' => $this->userRepo->count(['roles.name' => 'teacher']),
            'specializations_count' => $this->specRepo->count(),
            'job_applications_count' => $this->jobRepo->count(),
            'programs_count' => $this->programRepo->count(),
            'bundles_count' => $this->bundleRepo->count(),
        ]);
    }
}
