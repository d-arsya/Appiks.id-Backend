<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuestionnaireAnswerRequest extends FormRequest
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
            'answers'          => 'required|string',
            'type'             => 'required|in:safe,unsafe,help',
            'user_id'          => 'required|exists:users,id',
            'questionnaire_id' => [
                'nullable',
                'required_unless:type,help',
                'exists:questionnaires,id'
            ],
        ];
    }
}
