<?php

namespace App\Http\Requests;

use App\Traits\ApiResponder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserFirstLoginRequest extends FormRequest
{
    use ApiResponder;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !Auth::user()->verified;
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            $this->error('Kamu sudah merubah profile', 403)
        );
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'password' => [
                'required',
                'string',
                'min:8',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/'
            ],
            "username" => "required|unique:users,username," . Auth::user()->id . "|string",
            "phone" => "required|unique:users,phone," . Auth::user()->id . "|regex:/^[0-9]{10,15}$/",
        ];
    }

    protected function passedValidation()
    {
        $this->merge(["verified" => true, "password" => Hash::make($this->password)]);
    }
}
