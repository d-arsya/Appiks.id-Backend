<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        if ($user->role == 'student') {
            $user->cloud()->create(['level' => 1]);
        }
    }
}
