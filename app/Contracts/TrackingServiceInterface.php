<?php

namespace App\Contracts;

use App\Models\Content;
use App\Models\User;
use App\Models\ViewingProgress;

interface TrackingServiceInterface
{
    public function issueToken(User $user, Content $content, int $position): array;

    public function validateSegment(User $user, Content $content, array $payload): ViewingProgress;
}
