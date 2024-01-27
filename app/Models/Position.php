<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find($position_id)
 */
class Position extends Model
{
    use HasFactory;
    protected $table = 'position';
}
