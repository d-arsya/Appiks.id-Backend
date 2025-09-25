<?php

namespace App\Http\Requests;

use App\Models\School;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateSchoolRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', School::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|digits_between:8,13|max:20|unique:schools,phone',
            'email' => 'required|email|max:255|unique:schools,email',
            'district' => 'required|string|exists:locations,district|max:255',
            'city' => 'required|string|exists:locations,city|max:255',
            'province' => 'required|string|exists:locations,province|max:255',
        ];
    }
}
