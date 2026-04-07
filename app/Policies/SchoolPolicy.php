<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\School;
use App\Models\User;

class SchoolPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, School $school): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::AdminSchool;
    }

    public function update(User $user, School $school): bool
    {
        return $user->role === UserRole::AdminSchool
            && $user->school_id === $school->id;
    }

    public function delete(User $user, School $school): bool
    {
        return $user->role === UserRole::AdminSchool
            && $user->school_id === $school->id;
    }
}
