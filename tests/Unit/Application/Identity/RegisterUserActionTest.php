<?php

namespace Tests\Unit\Application\Identity;

use App\Application\Identity\DTOs\RegisterUserInput;
use App\Application\Identity\Actions\RegisterUserAction;
use App\Domains\Identity\Entities\User;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use Tests\TestCase;

class RegisterUserActionTest extends TestCase
{
    public function test_can_register_user_manually()
    {
        // Mock the Repository
        $repository = $this->createMock(IUserRepository::class);

        // Expect save to be called once with any User object, and return it
        $repository->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(User::class))
            ->willReturnCallback(fn($user) => $user);

        $action = new RegisterUserAction($repository);
        $input = new RegisterUserInput('Ahmed', 'ahmed@example.com', 'hashed_password');

        $result = $action->execute($input);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('Ahmed', $result->name());
        $this->assertEquals('ahmed@example.com', $result->email());
    }
}
