<?php

namespace App\Infrastructure\Repositories;

use App\Domains\Identity\Entities\User;
use App\Domains\Identity\RepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Models\User as UserModel;
use App\Concerns\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class EloquentUserRepository extends BaseRepository implements RepositoryInterface\IUserRepository
{
    public function __construct(UserModel $model)
    {
        parent::__construct($model);
    }

    protected function mapToDomain(Model $model): User
    {
        return User::fromPersistence(
            $model->id,
            $model->name,
            $model->email,
            $model->password,
            $model->email_verified_at ? new \DateTimeImmutable($model->email_verified_at->toDateTimeString()) : null,
            $model->roles->pluck('name')->toArray(),
            $model->google_id
        );
    }

    public function findByEmail(string $email): ?User
    {
        $model = $this->model->where('email', $email)->first();
        return $model ? $this->mapToDomain($model) : null;
    }

    public function findByGoogleId(string $id): ?User
    {
        $model = $this->model->where('google_id', $id)->first();
        return $model ? $this->mapToDomain($model) : null;
    }

    public function save(User $user): User
    {
        $data = [
            'name' => $user->name(),
            'email' => $user->email(),
            'google_id' => $user->googleId(),
        ];

        if ($user->googleId()) {
            $data['email_verified_at'] = now();
        }

        if ($user->password()) {
            $data['password'] = $user->password();
        }

        $model = $this->model->updateOrCreate(
            ['id' => $user->id()],
            $data
        );

        if (!empty($user->roles())) {
            $model->syncRoles($user->roles());
        }

        return $this->mapToDomain($model);
    }
}
