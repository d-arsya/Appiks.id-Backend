<?php

namespace App\Http\Requests;

use App\Models\Report;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class CreateReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Report::class);
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
            "topic" => "required|string",
            "room" => "required|string",
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            "user_id" => Auth::id(),
            "counselor_id" => Auth::user()->counselor_id,
            "priority" => Auth::user()->lastmood() == "angry" ? "tinggi" : "rendah"
        ]);
    }
}
