<?php

namespace App\Listeners;

use App\Events\ProgressUpdated;
use Illuminate\Support\Facades\Log;

class UpdateSubjectCompletionStats
{
    public function handle(ProgressUpdated $event): void
    {
        $progress = $event->progress;
        $content  = $progress->content;

        if ($content->duration_seconds && ! $progress->completed) {
            if ($progress->watched_seconds >= $content->duration_seconds * 0.9) {
                $progress->update(['completed' => true]);
                Log::info("Student {$progress->user_id} completed content {$content->id}");
            }
        }
    }
}
