<?php

namespace App\Infrastructure\Repositories;

use App\Concerns\Repositories\BaseRepository;
use App\Domains\Recruitment\Entities\JobApplication as DomainJobApplication;
use App\Domains\Recruitment\RepositoryInterface\IJobApplicationRepository;
use App\Infrastructure\Persistence\Eloquent\Models\JobApplication as JobApplicationModel;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Identity\Enums\RequestStatus;

class EloquentJobApplicationRepository extends BaseRepository implements IJobApplicationRepository
{
    public function __construct(JobApplicationModel $model)
    {
        parent::__construct($model);
    }

    protected function mapToDomain(Model $model): DomainJobApplication
    {
        return new DomainJobApplication(
            id: $model->id,
            firstName: $model->first_name,
            middleName: $model->middle_name,
            lastName: $model->last_name,
            age: $model->age,
            address: $model->address,
            email: $model->email,
            phone: $model->phone,
            yearsExperience: $model->years_experience,
            cvPath: $model->cv_path,
            coverLetter: $model->cover_letter,
            specializationIds: $model->specialization_ids,
            status: $model->status,
            reviewerId: $model->reviewer_id,
            reviewerNotes: $model->reviewer_notes,
            submittedAt: $model->submitted_at ? \Illuminate\Support\Carbon::parse($model->submitted_at)->toDateTimeImmutable() : null,
            reviewedAt: $model->reviewed_at ? \Illuminate\Support\Carbon::parse($model->reviewed_at)->toDateTimeImmutable() : null,
            createdAt: $model->created_at ? \Illuminate\Support\Carbon::parse($model->created_at)->toDateTimeImmutable() : null,
            updatedAt: $model->updated_at ? \Illuminate\Support\Carbon::parse($model->updated_at)->toDateTimeImmutable() : null
        );
    }

    public function save(DomainJobApplication $application): DomainJobApplication
    {
        $model = $this->model->findOrNew($application->id());
        
        $model->fill([
            'first_name' => $application->firstName(),
            'middle_name' => $application->middleName(),
            'last_name' => $application->lastName(),
            'age' => $application->age(),
            'address' => $application->address(),
            'email' => $application->email(),
            'phone' => $application->phone(),
            'years_experience' => $application->yearsExperience(),
            'cv_path' => $application->cvPath(),
            'cover_letter' => $application->coverLetter(),
            'specialization_ids' => $application->specializationIds(),
            'status' => $application->status(),
            'reviewer_id' => $application->reviewerId(),
            'reviewer_notes' => $application->reviewerNotes(),
            'submitted_at' => $application->submittedAt() ? $application->submittedAt()->format('Y-m-d H:i:s') : null,
            'reviewed_at' => $application->reviewedAt() ? $application->reviewedAt()->format('Y-m-d H:i:s') : null,
        ]);
        
        $model->save();
        
        return $this->mapToDomain($model);
    }

    public function findPending(): array
    {
        return $this->model->where('status', RequestStatus::PENDING)->get()
            ->map(fn(JobApplicationModel $model): DomainJobApplication => $this->mapToDomain($model))
            ->toArray();
    }
}
