<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            'identifier' => 'required|digits_between:8,10|unique:users,identifier',
            'school_id' => 'required|integer|exists:schools,id',
        ];
    }

    protected function passedValidation()
    {
        return $this->merge([
            'role' => 'admin',
        ]);
    }
}
