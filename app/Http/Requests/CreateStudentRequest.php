<?php

namespace App\Http\Requests;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CreateStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->role == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'identifier' => 'required|digits:10|unique:users,identifier',
            'mentor_id' => 'required|string|exists:users,identifier',
            'counselor_id' => 'required|string|exists:users,identifier',
            'room_id' => 'required|string|exists:rooms,code',
        ];
    }

    protected function passedValidation(): void
    {
        $mentor = User::where('identifier', $this->mentor_id)->first();
        $counselor = User::where('identifier', $this->counselor_id)->first();
        $room = Room::where('code', $this->room_id)->first();

        $this->merge([
            'username' => $this->identifier,
            'mentor_id' => $mentor?->id,
            'counselor_id' => $counselor?->id,
            'room_id' => $room?->id,
            'school_id' => Auth::user()->school_id,
            'verified' => false,
            'password' => Hash::make(config('app.default_password')),
        ]);
    }
}
