<?php

namespace Database\Factories;

use App\Infrastructure\Persistence\Eloquent\Models\JobApplication;
use App\Domains\Identity\Enums\RequestStatus;
use App\Infrastructure\Persistence\Eloquent\Models\Specialization;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobApplicationFactory extends Factory
{
    protected $model = JobApplication::class;

    public function definition(): array
    {
        $firstName = fake()->firstName();
        $lastName = fake()->lastName();

        return [
            'first_name' => $firstName,
            'middle_name' => fake()->firstName(),
            'last_name' => $lastName,
            'age' => fake()->numberBetween(22, 60),
            'address' => fake()->address(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'years_experience' => fake()->numberBetween(0, 30),
            'cv_path' => 'cvs/sample.pdf',
            'cover_letter' => fake()->paragraph(),
            'specialization_ids' => $this->getRandomSpecializationIds(),
            'status' => RequestStatus::PENDING,
            'submitted_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    private function getRandomSpecializationIds(): array
    {
        $ids = Specialization::pluck('id')->toArray();
        if (empty($ids)) {
            return [];
        }
        return fake()->randomElements($ids, fake()->numberBetween(1, 3));
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::APPROVED,
            'reviewed_at' => now(),
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => RequestStatus::REJECTED,
            'reviewed_at' => now(),
            'reviewer_notes' => 'Does not meet the full requirements at this time.',
        ]);
    }
}
