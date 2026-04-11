<?php

namespace Tests\Unit\Domains\Identity;

use App\Domains\Identity\Entities\User;
use PHPUnit\Framework\TestCase;

class UserEntityTest extends TestCase
{
    public function test_can_create_user_entity()
    {
        $user = User::create('Ahmed', null, 'Yahia', 'ahmed@example.com', 'password');

        $this->assertEquals('Ahmed Yahia', $user->name());
        $this->assertEquals('ahmed@example.com', $user->email());
        $this->assertEquals('password', $user->password());
    }

    public function test_can_change_name()
    {
        $user = User::create('Ahmed', null, 'Yahia', 'ahmed@example.com', 'password');
        $user->changeName('Mohamed', null, 'Ali');

        $this->assertEquals('Mohamed Ali', $user->name());
    }

    public function test_cannot_change_name_to_empty()
    {
        $user = User::create('Ahmed', null, 'Yahia', 'ahmed@example.com', 'password');

        $this->expectException(\InvalidArgumentException::class);
        $user->changeName('', null, '');
    }

    public function test_can_change_email()
    {
        $user = User::create('Ahmed', null, 'Yahia', 'ahmed@example.com', 'password');
        $user->changeEmail('yahia@example.com');

        $this->assertEquals('yahia@example.com', $user->email());
    }

    public function test_cannot_change_email_to_invalid_format()
    {
        $user = User::create('Ahmed', null, 'Yahia', 'ahmed@example.com', 'password');

        $this->expectException(\InvalidArgumentException::class);
        $user->changeEmail('invalid-email');
    }
}
