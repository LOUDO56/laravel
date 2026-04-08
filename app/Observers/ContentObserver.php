<?php

namespace App\Observers;

use App\Events\VideoUploaded;
use App\Models\Content;

class ContentObserver
{
    public function created(Content $content): void
    {
        event(new VideoUploaded($content, $content->teacher));
    }
}
