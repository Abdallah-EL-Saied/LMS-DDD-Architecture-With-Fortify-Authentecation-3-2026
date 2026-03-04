<?php

namespace Tests\Unit\Application\Identity;

use App\Application\Identity\Actions\DeleteUserAction;
use App\Domains\Identity\RepositoryInterface\IUserRepository;
use Tests\TestCase;

class DeleteUserActionTest extends TestCase
{
    public function test_can_delete_user()
    {
        $repository = $this->createMock(IUserRepository::class);

        $repository->expects($this->once())
            ->method('delete')
            ->with(1)
            ->willReturn(true);

        $action = new DeleteUserAction($repository);

        $result = $action->execute(1);

        $this->assertTrue($result);
    }
}
