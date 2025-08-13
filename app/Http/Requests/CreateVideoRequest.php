<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $tags = $this->input('tags', []);

        if (!is_array($tags)) {
            $tags = json_decode($tags, true) ?? [];
        }

        $this->merge([
            'tags' => array_map('strtolower', $tags),
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'   => 'required|string|max:255',
            'link' => 'required|string',
            'tags'    => 'required|array|min:1',
            'tags.*'  => 'string', // each tag must be a string
            'type'    => 'required|in:anger_management,self_help,inspiration',
            'school_id' => 'required|exists:schools,id',
        ];
    }
}
