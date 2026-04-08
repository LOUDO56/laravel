<?php

namespace App\Events;

use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoUploaded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Content $content,
        public readonly User $teacher
    ) {}
}
