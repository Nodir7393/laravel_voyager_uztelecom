<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Role;

/**
 * @method static where(string $string, mixed $role)
 */
class PermissionRole extends ALL
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'permission_role';

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'permission_role', 'permission_id', 'role_id');
    }
}
