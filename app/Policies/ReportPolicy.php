<?php

namespace App\Policies;

use App\Models\Report;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ReportPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user, Report $report): bool
    {
        return $report->user_id == $user->id || $report->user->counselor_id == $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == 'student' && in_array($user->lastmood(), ['angry', 'sad']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Report $report): bool
    {
        return $user->role == 'counselor' && $user->school_id == $report->user->school_id;
    }
}
