<?php

namespace App\Http\Requests;

use App\Models\Sharing;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CreateSharingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Sharing::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "string|max:100",
            "description" => "string"
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            "user_id" => Auth::id(),
            "replied_by" => Auth::user()->counselor->name,
            "priority" => "rendah"
        ]);
    }
}
