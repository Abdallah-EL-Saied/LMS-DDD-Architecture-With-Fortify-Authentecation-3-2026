<?php

namespace App\Interfaces\FortifyBridges;

use App\Concerns\Traits\PasswordValidationRules;
use App\Concerns\Traits\ProfileValidationRules;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use App\Application\Identity\DTOs\RegisterUserInput;
use App\Application\Identity\Actions\RegisterUserAction;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function __construct(
        private RegisterUserAction $registerUserAction
    ) {
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
        ])->validate();

        $userEntity = $this->registerUserAction->execute(
            new RegisterUserInput(
                $input['name'],
                $input['email'],
                $input['password']
            )
        );

        // Convert back to Eloquent for Fortify's internal requirements
        return User::find($userEntity->id());
    }
}
