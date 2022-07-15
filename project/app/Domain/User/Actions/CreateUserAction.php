<?php

namespace App\Domain\User\Actions;

use App\Core\Traits\Newable;
use App\Domain\User\DataTransferObjects\UserDto;
use App\Domain\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class CreateUserAction
{
    use Newable;

    public function execute(UserDto $dto): Model
    {
        return User::query()->create($dto->toArray());
    }
}
