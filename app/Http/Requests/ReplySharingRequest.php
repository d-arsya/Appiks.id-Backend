<?php

namespace App\Http\Requests;

use App\Models\Sharing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ReplySharingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->sharing) && !$this->sharing->reply;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "text" => "string"
        ];
    }

    protected function passedValidation()
    {
        $this->replace(array_merge(
            $this->except('text'), // hapus key text
            [
                'reply'      => $this->text,
                'replied_at' => now()->toDateString(),
                'replied_by' => auth()->user()->name,
            ]
        ));
    }
}
