<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'days'    => 'required|array|min:1',
            'days.*'  => 'string|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'user_id'  => 'required|integer|exists:users,id',
        ];
    }
}
