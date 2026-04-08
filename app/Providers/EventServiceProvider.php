<?php

namespace App\Providers;

use App\Events\ProgressUpdated;
use App\Events\SubjectReferentialUploaded;
use App\Events\UserEnrolledInClassroom;
use App\Events\VideoUploaded;
use App\Listeners\LogActivityEvent;
use App\Listeners\UpdateSubjectCompletionStats;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VideoUploaded::class => [
            LogActivityEvent::class,
        ],
        ProgressUpdated::class => [
            UpdateSubjectCompletionStats::class,
            LogActivityEvent::class,
        ],
        SubjectReferentialUploaded::class => [
            LogActivityEvent::class,
        ],
        UserEnrolledInClassroom::class => [
            LogActivityEvent::class,
        ],
    ];
}
