<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;

class LogActivityEvent
{
    public function handle(object $event): void
    {
        Log::channel('stack')->info('Événement métier : ' . class_basename($event), [
            'event' => get_class($event),
        ]);
    }
}
