<?php

namespace App\Application\Recruitment\Actions;

use App\Domains\Recruitment\Entities\JobApplication;
use App\Domains\Recruitment\RepositoryInterface\IJobApplicationRepository;

class SubmitJobApplicationAction
{
    public function __construct(
        private IJobApplicationRepository $repository
    ) {}

    public function execute(array $data): JobApplication
    {
        $application = new JobApplication(
            id: null,
            firstName: $data['first_name'],
            middleName: $data['middle_name'] ?? null,
            lastName: $data['last_name'],
            age: $data['age'] ?? null,
            address: $data['address'] ?? null,
            email: $data['email'],
            phone: $data['phone'],
            yearsExperience: $data['years_experience'] ?? null,
            cvPath: $data['cv_path'],
            coverLetter: $data['cover_letter'] ?? null,
            specializationIds: $data['specialization_ids'],
            submittedAt: new \DateTimeImmutable()
        );

        return $this->repository->save($application);
    }
}
