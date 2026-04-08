<?php

namespace App\Events;

use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubjectReferentialUploaded
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Subject $subject,
        public readonly User $uploader
    ) {}
}
