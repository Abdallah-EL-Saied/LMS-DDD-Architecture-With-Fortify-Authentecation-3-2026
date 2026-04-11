<?php

namespace App\Domains\Recruitment\RepositoryInterface;

use App\Concerns\Repositories\BaseRepositoryInterface;
use App\Domains\Recruitment\Entities\JobApplication;

interface IJobApplicationRepository extends BaseRepositoryInterface
{
    public function save(JobApplication $application): JobApplication;
    public function findPending(): array;
}
