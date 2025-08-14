<?php

namespace App\Policies;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SchedulePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return !in_array($user->role, ['super', 'headteacher', 'student']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        if ($user->role == 'teacher') {
            return $user->school_id == $schedule->conselor->school_id;
        } else if ($user->role == 'conselor') {
            return $user->id == $schedule->user_id;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        return $user->id == $schedule->user_id;
    }
}
