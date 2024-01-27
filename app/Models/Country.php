<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static get()
 */
class Country extends Model
{
    use HasFactory;
    protected $primaryKey = 'country_alpha3_code';

    public $incrementing = false;
    protected $table = 'location_info';
}
