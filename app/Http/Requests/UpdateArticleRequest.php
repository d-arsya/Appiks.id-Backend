<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return  Gate::allows('create', Article::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "tags.*"      => "integer|exists:tags,id",
            "title"       => "required|string|max:255",
            "description" => "required|string",
            "thumbnail"   => "nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5200",
            "content"     => "required|json",
        ];
    }

    protected function passedValidation()
    {
        $baseSlug = Str::slug($this->title);

        // Ambil 4 karakter random dari UUID
        $uniquePart = substr((string) Str::uuid(), 0, 4);

        $this->merge([
            "slug"      => $baseSlug . '-' . $uniquePart,
            "tags"      => (array) $this->input('tags', []),
        ]);
    }
}
