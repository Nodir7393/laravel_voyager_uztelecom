<?php

namespace App\Models;

use App\Events\BranchDeleted;
use App\Events\BranchSaved;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Role;

/**
 * @method static find($id, $columns = ['*'])
 */
class Branch extends ALL
{
    use HasFactory;
    protected $table = 'branches';

    protected $dispatchesEvents = [
        'saved' => BranchSaved::class,
        'deleted' => BranchDeleted::class,
    ];
}
