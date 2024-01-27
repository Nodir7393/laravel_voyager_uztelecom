<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $id)
 */
class Warehouse extends ALL
{
    use HasFactory;
    protected $table = 'warehouse';
}
