<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateRoomRequest extends FormRequest
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
            'level' => 'required|string|in:X,XI,XII',
        ];
    }

    protected function passedValidation()
    {
        $code = substr((string) Str::uuid(), 0, 8);

        return $this->merge([
            'school_id' => Auth::user()->school_id,
            'code' => $code,
        ]);
    }
}
