<?php

namespace App\Events;

use App\Models\Branch;
use Illuminate\Support\Facades\Cache;

class ClearBranchCache
{
    public function handle(BranchEvent $event)
    {
        Cache::forget('branches' . $event->getBranch()->id);
    }
}
