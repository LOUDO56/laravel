<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role === UserRole::AdminSchool;
    }

    public function view(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }

        return $user->role === UserRole::AdminSchool
            && $user->school_id === $model->school_id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::AdminSchool;
    }

    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return true;
        }

        return $user->role === UserRole::AdminSchool
            && $user->school_id === $model->school_id;
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === UserRole::AdminSchool
            && $user->school_id === $model->school_id
            && $user->id !== $model->id;
    }
}
