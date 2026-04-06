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
        $address = null;
        if ($model->country && $model->city) {
            $address = new \App\Domains\Identity\ValueObjects\Address(
                $model->country,
                $model->city,
                $model->street_address ?? ''
            );
        }

        return User::fromPersistence(
            $model->id,
            $model->first_name,
            $model->middle_name,
            $model->last_name ?? '',
            $model->email,
            $model->password,
            $model->date_of_birth ? new \DateTimeImmutable($model->date_of_birth->toDateString()) : null,
            $model->phone_number,
            $model->gender,
            $model->status ?? \App\Domains\Identity\Enums\UserStatus::ACTIVE,
            $address,
            $model->email_verified_at ? new \DateTimeImmutable($model->email_verified_at->toDateTimeString()) : null,
            $model->roles->pluck('name')->toArray(),
            $model->google_id,
            $model->last_login_at ? new \DateTimeImmutable($model->last_login_at->toDateTimeString()) : null
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
            'first_name' => $user->firstName(),
            'middle_name' => $user->middleName(),
            'last_name' => $user->lastName(),
            'email' => $user->email(),
            'date_of_birth' => $user->dateOfBirth()?->format('Y-m-d'),
            'phone_number' => $user->phoneNumber(),
            'gender' => $user->gender(),
            'status' => $user->status(),
            'google_id' => $user->googleId(),
            'last_login_at' => $user->lastLoginAt()?->format('Y-m-d H:i:s'),
        ];

        if ($user->address()) {
            $data['country'] = $user->address()->country();
            $data['city'] = $user->address()->city();
            $data['street_address'] = $user->address()->streetAddress();
        }

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

    public function chunkById(int $count, callable $callback, array $filters = [])
    {
        $query = $this->model->query();

        if (!empty($filters)) {
            if (method_exists($this->model, 'scopeFilter')) {
                $query->filter($filters);
            } else {
                foreach ($filters as $field => $value) {
                    $query->where($field, $value);
                }
            }
        }

        return $query->chunkById($count, function (\Illuminate\Database\Eloquent\Collection $models) use ($callback) {
            $domainEntities = $models->map(fn(UserModel $model) => $this->mapToDomain($model))->all();
            return $callback($domainEntities);
        });
    }
}
