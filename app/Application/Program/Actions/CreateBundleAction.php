<?php

namespace App\Application\Program\Actions;

use App\Application\Program\DTOs\BundleInput;
use App\Domains\Program\RepositoryInterface\IBundleRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class CreateBundleAction
{
    public function __construct(
        private IBundleRepository $repository
    ) {}

    public function execute(BundleInput $input): void
    {
        $data = $input->toArray();

        // Business Validation
        $this->validate($data);

        $this->repository->create($data);
    }

    protected function validate(array $data): void
    {
        $validator = Validator::make($data, [
            'name.ar' => 'required|string',
            'name.en' => 'required|string',
            'duration_minutes' => 'required|integer|min:1',
            'sessions_count' => 'required|integer|min:1',
            'monthly_price_egp' => 'required|numeric|min:0',
            'monthly_price_usd' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
