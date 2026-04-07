<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Subject;
use App\Models\User;

class SubjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Subject $subject): bool
    {
        if ($user->role === UserRole::AdminSchool) {
            return $user->school_id === $subject->school_id;
        }

        // Teacher or student: must be enrolled in a classroom linked to this subject
        return $subject->classrooms()
            ->whereHas('users', fn ($q) => $q->where('users.id', $user->id))
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::AdminSchool;
    }

    public function update(User $user, Subject $subject): bool
    {
        if ($user->role === UserRole::AdminSchool) {
            return $user->school_id === $subject->school_id;
        }

        if ($user->role === UserRole::Teacher) {
            return $subject->classrooms()
                ->whereHas('teachers', fn ($q) => $q->where('users.id', $user->id))
                ->exists();
        }

        return false;
    }

    public function delete(User $user, Subject $subject): bool
    {
        return $user->role === UserRole::AdminSchool
            && $user->school_id === $subject->school_id;
    }
}
