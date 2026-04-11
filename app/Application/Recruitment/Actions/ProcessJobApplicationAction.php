<?php

namespace App\Application\Recruitment\Actions;

use App\Domains\Recruitment\Entities\JobApplication;
use App\Domains\Recruitment\RepositoryInterface\IJobApplicationRepository;
use App\Domains\Identity\Enums\RequestStatus;

class ProcessJobApplicationAction
{
    public function __construct(
        private IJobApplicationRepository $repository,
        private CreateTeacherFromApplicationAction $createTeacherAction
    ) {}

    public function execute(int $applicationId, string $status, int $reviewerId, ?string $notes = null): JobApplication
    {
        $application = $this->repository->findById($applicationId);

        if (!$application) {
            throw new \Exception("Application not found.");
        }

        if ($status === RequestStatus::APPROVED->value) {
            $application->approve($reviewerId, $notes);
            $this->repository->save($application);
            
            // Create the teacher account
            $this->createTeacherAction->execute($application);
        } elseif ($status === RequestStatus::REJECTED->value) {
            $application->reject($reviewerId, $notes);
            $this->repository->save($application);
        } else {
            throw new \Exception("Invalid status provided.");
        }

        return $application;
    }
}
