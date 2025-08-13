<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateMeetRequest extends FormRequest
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
            'day'       => 'required|string|in:senin,selasa,rabu,kamis,jumat,sabtu,minggu',
            'ended'     => 'nullable|boolean',
            'anomaly_id' => 'required|exists:anomalies,id',
            'teacher'    => 'required|exists:users,id',
            'student'    => 'required|exists:users,id',
        ];
    }
}
