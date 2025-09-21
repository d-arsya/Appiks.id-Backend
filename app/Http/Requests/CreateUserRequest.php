<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
            "name" => "required|string",
            "username" => "required|string|unique:users,username",
            "identifier" => "required|digits_between:8,10|unique:users,identifier",
            "phone" => "required|digits_between:8,10|unique:users,phone",
            "role" => "required|string|in:teacher,headteacher,counselor",
        ];
    }

    protected function passedValidation()
    {
        $this->merge(["school_id" => Auth::user()->school_id]);
    }
}
