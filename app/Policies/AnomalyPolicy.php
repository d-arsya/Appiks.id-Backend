<?php

namespace App\Policies;

use App\Models\Anomaly;
use App\Models\User;

class AnomalyPolicy
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
    public function view(User $user, Anomaly $anomaly): bool
    {
        if ($user->role == 'student' && $anomaly->student->id == $user->id) {
            return true;
        } else if ($user->role == 'super') {
            return false;
        }
        return $user->school_id == $anomaly->student->school_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == 'student';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Anomaly $anomaly): bool
    {
        if ($user->role == 'teacher') {
            return $user->room_id == $anomaly->student->room_id;
        } else if ($user->role == 'conselor') {
            return $user->school_id == $anomaly->student->school_id;
        }
        return false;
    }
}
