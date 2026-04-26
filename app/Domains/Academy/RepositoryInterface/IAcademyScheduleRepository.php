<?php

namespace App\Domains\Academy\RepositoryInterface;

use App\Domains\Academy\Entities\AcademyScheduleConfig;

interface IAcademyScheduleRepository extends \App\Concerns\Repositories\BaseRepositoryInterface
{
    public function get(): ?AcademyScheduleConfig;
    public function save(AcademyScheduleConfig $config): void;
}
