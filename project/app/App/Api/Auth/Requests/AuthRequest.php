<?php

namespace App\App\Api\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|string|email|exists:users,email',
            'password' => 'required|string|min:6'
        ];
    }
}
