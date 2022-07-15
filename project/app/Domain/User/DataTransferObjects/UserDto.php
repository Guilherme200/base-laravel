<?php

namespace App\Domain\User\DataTransferObjects;

use App\Core\Traits\Newable;
use Spatie\DataTransferObject\DataTransferObject;

class UserDto extends DataTransferObject
{
    use Newable;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var string */
    public $password;
}
