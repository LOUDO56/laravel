<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\ViewingProgress;

class ViewingProgressPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ViewingProgress $progress): bool
    {
        // Student can only see their own progress
        if ($user->role === UserRole::Student) {
            return $progress->user_id === $user->id;
        }

        // Teacher can see progress for their subject's contents
        if ($user->role === UserRole::Teacher) {
            return $progress->content->subject->classrooms()
                ->whereHas('teachers', fn ($q) => $q->where('users.id', $user->id))
                ->exists();
        }

        // Admin can see progress for their school
        if ($user->role === UserRole::AdminSchool) {
            return $progress->content->subject->school_id === $user->school_id;
        }

        return false;
    }
}
