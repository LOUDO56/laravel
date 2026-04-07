<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Content;
use App\Models\User;

class ContentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Content $content): bool
    {
        if ($user->role === UserRole::AdminSchool) {
            return $user->school_id === $content->subject->school_id;
        }

        // Teacher or student must be in a classroom linked to the subject
        return $content->subject->classrooms()
            ->whereHas('users', fn ($q) => $q->where('users.id', $user->id))
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::Teacher;
    }

    public function update(User $user, Content $content): bool
    {
        return $user->role === UserRole::Teacher
            && $content->teacher_id === $user->id;
    }

    public function delete(User $user, Content $content): bool
    {
        if ($user->role === UserRole::Teacher) {
            return $content->teacher_id === $user->id;
        }

        if ($user->role === UserRole::AdminSchool) {
            return $user->school_id === $content->subject->school_id;
        }

        return false;
    }
}
