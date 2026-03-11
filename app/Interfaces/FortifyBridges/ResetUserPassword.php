<?php

namespace App\Interfaces\FortifyBridges;

use App\Concerns\Traits\PasswordValidationRules;
use App\Infrastructure\Persistence\Eloquent\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\ResetsUserPasswords;
use App\Application\Identity\Actions\ResetPasswordAction;

class ResetUserPassword implements ResetsUserPasswords
{
    use PasswordValidationRules;

    public function __construct(
        private ResetPasswordAction $resetPasswordAction
    ) {
    }

    /**
     * Validate and reset the user's forgotten password.
     *
     * @param  array<string, string>  $input
     */
    public function reset(User $user, array $input): void
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validate();

        $this->resetPasswordAction->execute($user->id, $input['password']);
    }
}
