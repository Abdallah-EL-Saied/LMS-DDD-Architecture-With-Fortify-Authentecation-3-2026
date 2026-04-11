<?php

namespace App\Domains\Specialization\RepositoryInterface;

use App\Concerns\Repositories\BaseRepositoryInterface;
use App\Domains\Specialization\Entities\Specialization;

interface ISpecializationRepository extends BaseRepositoryInterface
{
    public function save(Specialization $specialization): Specialization;
    public function getActive(): array;
    public function getAll(): array;
}
