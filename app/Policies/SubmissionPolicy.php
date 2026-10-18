<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    public function view(
        User $user,
        Submission $submission
    ): bool {
        return $user->isAdmin()
            || $submission->submitted_by === $user->id
            || $submission->members()
                ->where('user_id', $user->id)
                ->exists();
    }

    public function update(
        User $user,
        Submission $submission
    ): bool {
        return $submission->submitted_by === $user->id
            && in_array(
                $submission->status,
                ['pending', 'revision_requested'],
                true
            );
    }
}