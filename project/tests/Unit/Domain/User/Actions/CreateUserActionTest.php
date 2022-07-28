<?php

namespace Tests\Unit\Domain\User\Actions;

use App\Domain\User\Actions\CreateUserAction;
use App\Domain\User\DataTransferObjects\UserDto;
use App\Domain\User\Models\User;
use Tests\TestCase;

class CreateUserActionTest extends TestCase
{
    public function test_should_create_user_with_valid_data(): void
    {
        $this->mock(User::class)
            ->shouldReceive('create')
            ->once()
            ->andReturn(new User());

        $userDto = UserDto::new([
            'name' => 'Test',
            'email' => 'test@email.com',
            'password' => '12345678',
        ]);

        $result = (new CreateUserAction())->execute($userDto);
        $this->assertInstanceOf(User::class, $result);
    }
}
