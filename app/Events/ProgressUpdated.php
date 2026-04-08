<?php

namespace App\Events;

use App\Models\ViewingProgress;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProgressUpdated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly ViewingProgress $progress,
        public readonly int $addedSeconds
    ) {}
}
