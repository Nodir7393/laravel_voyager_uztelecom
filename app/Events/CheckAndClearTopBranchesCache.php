<?php

namespace App\Events;

use App\Models\Branch;
use Illuminate\Support\Facades\Cache;

class CheckAndClearTopBranchesCache
{
    public function handle(BranchEvent $event)
    {
        $updatedBranch = $event->getBranch();
        $branches = Cache::get('branches', []);
        foreach($branches as $branch) {
            if($updatedBranch->id == $branch->id) {
                Cache::forget('branches');
                return;
            }
        }
    }
}
