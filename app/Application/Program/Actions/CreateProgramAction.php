<?php

namespace App\Application\Program\Actions;

use App\Application\Program\DTOs\ProgramInput;
use App\Domains\Program\RepositoryInterface\IProgramRepository;
use Illuminate\Support\Str;

class CreateProgramAction
{
    public function __construct(
        private IProgramRepository $repository
    ) {}

    public function execute(ProgramInput $input): void
    {
        $data = $input->toArray();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']['en'] ?? $data['title']['ar']);
        }

        $this->repository->create($data);
    }
}
