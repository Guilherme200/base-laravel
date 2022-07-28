<?php

namespace App\Domain\User\Actions;

use App\Core\Traits\Newable;
use App\Domain\User\DataTransferObjects\UserDto;
use App\Domain\User\Models\User;

class CreateUserAction
{
    use Newable;

    public function execute(UserDto $dto): User
    {
        return app(User::class)
            ->create($dto->toArray());
    }
}
