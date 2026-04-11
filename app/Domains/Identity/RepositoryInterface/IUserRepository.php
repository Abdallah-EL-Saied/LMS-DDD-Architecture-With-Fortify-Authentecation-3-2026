<?php

namespace App\Domains\Identity\RepositoryInterface;

use App\Concerns\Repositories\BaseRepositoryInterface;
use App\Domains\Identity\Entities\User;

interface IUserRepository extends BaseRepositoryInterface
{
    public function findByEmail(string $email): ?User;
    public function findByGoogleId(string $id): ?User;
    public function save(User $user): User;
    public function chunkById(int $count, callable $callback, array $filters = []);
    public function syncSpecializations(int $userId, array $specializationIds): void;
}
