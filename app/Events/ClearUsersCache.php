<?php

namespace App\Events;

use App\Models\Branch;
use Illuminate\Support\Facades\Cache;

class ClearUsersCache
{
    public function handle(UserEvent $event)
    {
        Cache::forget('users' . $event->getUser()->id);
    }
}
