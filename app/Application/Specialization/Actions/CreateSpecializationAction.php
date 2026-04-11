<?php

namespace App\Application\Specialization\Actions;

use App\Domains\Specialization\Entities\Specialization;
use App\Domains\Specialization\RepositoryInterface\ISpecializationRepository;

class CreateSpecializationAction
{
    public function __construct(
        private ISpecializationRepository $repository
    ) {}

    public function execute(array $name, ?array $description = null, bool $isActive = true): Specialization
    {
        $specialization = new Specialization(
            id: null,
            name: $name,
            description: $description,
            isActive: $isActive
        );

        return $this->repository->save($specialization);
    }
}
