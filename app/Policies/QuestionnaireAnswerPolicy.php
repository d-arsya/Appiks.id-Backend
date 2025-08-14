<?php

namespace App\Policies;

use App\Models\QuestionnaireAnswer;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionnaireAnswerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->role == 'teacher';
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QuestionnaireAnswer $questionnaireAnswer): bool
    {
        return $user->role == 'teacher' && $user->room_id == $questionnaireAnswer->student->room_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->role == 'student';
    }
}
