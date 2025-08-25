<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserFirstLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !Auth::user()->verified;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "username" => "required|unique:users,username," . Auth::user()->id . "|string",
            "phone" => "required|unique:users,phone," . Auth::user()->id . "|regex:/^[0-9]{10,15}$/",
            "verified" => "required|boolean"
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge(["verified" => true]);
    }
}
