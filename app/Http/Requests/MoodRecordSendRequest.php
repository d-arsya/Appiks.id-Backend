<?php

namespace App\Http\Requests;

use App\Models\MoodRecord;
use App\Traits\ApiResponder;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class MoodRecordSendRequest extends FormRequest
{
    use ApiResponder;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = Auth::user()->role == 'student';
        $mood = MoodRecord::where('user_id', Auth::id())->where('recorded', Carbon::today())->get()->count() == 0;

        return $user && $mood;
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(
            $this->error('Kamu sudah merekam mood hari ini', 403)
        );
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:happy,sad,angry,neutral',
        ];
    }

    protected function passedValidation()
    {
        $this->merge(['user_id' => Auth::id(), 'recorded' => Carbon::today()]);
    }
}
