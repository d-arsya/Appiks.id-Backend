<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserFirstLoginRequest;
use App\Http\Resources\UserResource;
use App\Traits\ApiResponder;
use Dedoc\Scramble\Attributes\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use ApiResponder;

    /**
     * Update user profile on first login
     */
    #[Group('User')]
    public function profile(UserFirstLoginRequest $request)
    {
        Auth::user()->update($request->all());
        return $this->success(new UserResource(Auth::user()), 'Success update user profile');
    }
}
