<?php

namespace App\Domains\Identity\RepositoryInterface;

use App\Domains\Identity\Entities\User;

interface IUserRepository
{
    public function all(array $columns = ['*'], array $relations = [], int $perPage = 15);
    public function findById($id, array $columns = ['*'], array $relations = []);
    public function findByEmail(string $email): ?User;
    public function findByGoogleId(string $id): ?User;
    public function save(User $user): User;
    public function delete(int $id);
}
