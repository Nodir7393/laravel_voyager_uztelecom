<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static find(mixed $role_company)
 */
class Roles extends ALL
{
    use HasFactory;
    protected $table = 'roles';
    public function users()
    {
        return $this->hasMany(User::class,'role_id');
    }
}
