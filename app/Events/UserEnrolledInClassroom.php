<?php

namespace App\Events;

use App\Models\Classroom;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEnrolledInClassroom
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Classroom $classroom,
        public readonly string $role
    ) {}
}
