<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'username' => 'required|unique:users,username',
            'phone' => 'required|digits_between:10,15|unique:users,username',
            'identifier' => 'required|digits_between:8,10|unique:users,identifier',
            'role' => 'required|string|in:teacher,headteacher,counselor',
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
            ],
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            'verified' => true,
            'school_id' => Auth::user()->school_id,
            'password' => Hash::make($this->password),
        ]);
    }
}
