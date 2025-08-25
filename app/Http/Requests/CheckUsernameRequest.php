<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckUsernameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // boleh diakses tanpa auth kalau mau
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|unique:users,username',
        ];
    }

    public function messages(): array
    {
        return [
            'username.unique' => 'Username is used',
        ];
    }
}
