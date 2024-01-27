<?php

namespace App\Models;

use Illuminate\Database\Query\Builder;

/**
 * @property mixed $name
 * @property mixed $color
 * @method static StatusExtended find($id, $columns = ['*'])
 * @method static StatusExtended pluck($column, $key = null)
 */
class StatusExtended extends ALL
{
    protected $table = 'status_extended';
}
