<?php

namespace App\Events;

use App\Models\Branch;

abstract class BranchEvent
{
    /** @var Branch */
    private $branch;

    public function __construct(Branch $branch) {
        $this->branch = $branch;
    }

    public function getBranch(): Branch {
        return $this->branch;
    }
}
