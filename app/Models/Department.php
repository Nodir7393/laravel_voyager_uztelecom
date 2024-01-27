<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends ALL
{
    use HasFactory;

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id')->withDefault(['name' => '']);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
