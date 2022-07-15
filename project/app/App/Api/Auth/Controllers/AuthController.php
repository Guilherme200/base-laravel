<?php

namespace App\App\Api\Auth\Controllers;

use App\App\Api\Auth\Requests\AuthRequest;
use App\Core\Http\Controllers\Controller;
use App\Core\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(AuthRequest $request): JsonResponse
    {
        $attr = $request->validated();
        if (!Auth::attempt($attr)) {
            return $this->error('Credentials not match', 401);
        }

        return $this->success([
            'token' => auth()->user()->createToken('API Token')->plainTextToken,
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return $this->error(['message' => 'Tokens Revoked'], 200);
    }
}
