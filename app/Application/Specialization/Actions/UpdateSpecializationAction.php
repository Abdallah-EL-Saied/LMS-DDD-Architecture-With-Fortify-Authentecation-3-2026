<?php

namespace App\Application\Specialization\Actions;

use App\Domains\Specialization\Entities\Specialization;
use App\Domains\Specialization\RepositoryInterface\ISpecializationRepository;

class UpdateSpecializationAction
{
    public function __construct(
        private ISpecializationRepository $repository
    ) {}

    public function execute(int $id, array $name, ?array $description, bool $isActive): Specialization
    {
        $specialization = $this->repository->findById($id);

        if (!$specialization) {
            throw new \Exception("Specialization not found");
        }

        $specialization->changeName($name);
        $specialization->changeDescription($description);
        
        if ($specialization->isActive() !== $isActive) {
            $specialization->toggleStatus();
        }

        return $this->repository->save($specialization);
    }
}
