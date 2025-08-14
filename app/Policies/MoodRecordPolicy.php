<?php

namespace App\Policies;

use App\Models\MoodRecord;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class MoodRecordPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role != 'super';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MoodRecord $moodRecord): bool
    {
        return $user->id == $moodRecord->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == 'student';
    }
}
