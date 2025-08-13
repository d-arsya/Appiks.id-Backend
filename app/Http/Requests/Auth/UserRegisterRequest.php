<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserRegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "name" => "required|string",
            "email" => "required|email|string|unique:users,email",
            "phone" => "required|string|unique:users,phone",
            "identifier" => "required|string",
            "role" => "required|string|in:super,admin,teacher,student,conselor,headteacher",
            "room_id" => "required|integer|exists:rooms,id",
            "school_id" => "required|integer|exists:schools,id",
            "password" => "required|string",
        ];
    }

    protected function passedValidation(): void
    {
        $this->merge([
            'password' => Hash::make($this->password),
        ]);
    }
}
