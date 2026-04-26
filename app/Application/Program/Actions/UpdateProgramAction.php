<?php

namespace App\Application\Program\Actions;

use App\Application\Program\DTOs\ProgramInput;
use App\Domains\Program\RepositoryInterface\IProgramRepository;
use Illuminate\Support\Str;

class UpdateProgramAction
{
    public function __construct(
        private IProgramRepository $repository
    ) {}

    public function execute(int $id, ProgramInput $input): void
    {
        $data = $input->toArray();

        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']['en'] ?? $data['title']['ar']);
        }

        $this->repository->update($id, $data);
    }
}
