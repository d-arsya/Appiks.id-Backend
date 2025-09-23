<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class RescheduleReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->report) && $this->report->status == 'disetujui';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "date" => "required|date_format:Y-m-d",
            /**
             * @var string
             * @example 10:10
             */
            'time' => Rule::date()->format("H:i"),
            "notes" => "required|string",
            "room" => "required|string",
        ];
    }

    protected function passedValidation()
    {
        $this->merge(["status" => "dijadwalkan"]);
    }
}
