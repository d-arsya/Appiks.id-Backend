<?php

namespace App\Http\Controllers;

use App\Models\Gemini;
use App\Models\User;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class GeminiController extends Controller
{
    use ApiResponder;

    /**
     * Get all tokens
     */
    #[Group('Gemini Token')]
    public function index()
    {
        Gate::allowIf(function (User $user) {
            return $user->role == 'super';
        });
        $tokens = Gemini::all();

        return $this->success($tokens);
    }

    /**
     * Update a token
     *
     * Hanya bisa update token yang expired
     */
    #[Group('Gemini Token')]
    public function update(Request $request, Gemini $gemini)
    {
        Gate::allowIf(function (User $user) use ($gemini) {
            return $user->role == 'super' && $gemini->status == 'expired' && $gemini->token != $request->token;
        });
        $request->validate(['token' => 'required|string']);
        $gemini->update(['token' => $request->token, 'status' => 'active']);

        return $this->success($gemini);
    }
}
