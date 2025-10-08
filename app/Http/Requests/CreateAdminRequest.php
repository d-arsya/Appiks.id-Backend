<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role == 'super';
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
            'phone' => 'required|digits_between:10,13|unique:users,phone',
            'username' => 'required|unique:users,username',
            'identifier' => 'required|digits:16|unique:users,identifier',
            'school_id' => 'required|integer|exists:schools,id',
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
        return $this->merge([
            'verified' => true,
            'role' => 'admin',
            'password' => Hash::make($this->password),
        ]);
    }
}
