<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Classroom;
use App\Models\User;

class ClassroomPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Classroom $classroom): bool
    {
        if ($user->role === UserRole::AdminSchool) {
            return $user->school_id === $classroom->school_id;
        }

        return $user->classrooms()->where('classrooms.id', $classroom->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::AdminSchool;
    }

    public function update(User $user, Classroom $classroom): bool
    {
        return $user->role === UserRole::AdminSchool
            && $user->school_id === $classroom->school_id;
    }

    public function delete(User $user, Classroom $classroom): bool
    {
        return $user->role === UserRole::AdminSchool
            && $user->school_id === $classroom->school_id;
    }
}
