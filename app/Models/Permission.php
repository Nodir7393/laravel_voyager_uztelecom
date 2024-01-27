<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Permission extends ALL
{
    use HasFactory;

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'permission_role', 'permission_id', 'role_id');
    }
}
