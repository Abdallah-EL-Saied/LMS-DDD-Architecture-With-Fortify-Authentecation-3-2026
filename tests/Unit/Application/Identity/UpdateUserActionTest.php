<?php

namespace Tests\Unit\Application\Identity;

use App\Application\Identity\DTOs\UpdateUserInput;
use App\Application\Identity\Actions\UpdateUserAction;
use App\Domains\Identity\Entities\User;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use Tests\TestCase;

class UpdateUserActionTest extends TestCase
{
    public function test_can_update_user_name()
    {
        $repository = $this->createMock(IUserRepository::class);

        $existingUser = User::fromPersistence(1, 'Old Name', 'old@example.com');

        $repository->expects($this->once())
            ->method('findById')
            ->with(1)
            ->willReturn($existingUser);

        $repository->expects($this->once())
            ->method('save')
            ->with($this->callback(fn($user) => $user->name() === 'New Name'))
            ->willReturnCallback(fn($user) => $user);

        $action = new UpdateUserAction($repository);
        $input = new UpdateUserInput(1, 'New Name', 'old@example.com');

        $result = $action->execute($input);

        $this->assertEquals('New Name', $result->name());
    }

    public function test_throws_exception_if_user_not_found()
    {
        $repository = $this->createMock(IUserRepository::class);

        $repository->method('findById')->willReturn(null);

        $action = new UpdateUserAction($repository);
        $input = new UpdateUserInput(99, 'Name', 'email@example.com');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('User not found');

        $action->execute($input);
    }
}
