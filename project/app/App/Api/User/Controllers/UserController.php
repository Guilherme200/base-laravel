<?php

namespace App\App\Api\User\Controllers;

use App\App\Api\User\Requests\UserRequest;
use App\App\Api\User\Resources\UserResource;
use App\Core\Http\Controllers\Controller;
use App\Domain\User\Actions\CreateUserAction;
use App\Domain\User\DataTransferObjects\UserDto;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function create(UserRequest $request): JsonResponse
    {
        $dto = UserDto::new($request->validated());
        $user = CreateUserAction::new()->execute($dto);
        return response()->json(UserResource::make($user), 200);
    }
}
