<?php

namespace App\Policies;

use App\Models\{Question, User};

class QuestionPolicy
{
    public function viewAny(User $user): bool
    {
        //
    }

    public function view(User $user, Question $question): bool
    {
        //
    }

    public function create(User $user): bool
    {
        //
    }

    public function update(User $user, Question $question): bool
    {
        return $user->is($question->user);
    }

    public function delete(User $user, Question $question): bool
    {
        //
    }

    public function restore(User $user, Question $question): bool
    {
        return $user->is($question->user);
    }

    public function forceDelete(User $user, Question $question): bool
    {
        return $user->is($question->user);
    }

    public function archive(User $user, Question $question): bool
    {
        return $user->is($question->user);
    }

    public function publish(User $user, Question $question): bool
    {
        return $user->is($question->user);
    }
}
