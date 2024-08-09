<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogUserCreated implements ShouldQueue, ShouldHandleEventsAfterCommit
{
    public function handle(UserCreated $event): void
    {
        logger([
            'user_id' => $event->user->getKey(),
        ]);
    }
}
