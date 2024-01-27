<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

/**
 * @method static where(string $string, int $role_id)
 * @property mixed role_id
 * @property mixed role_index
 * @property mixed application_id
 * @property mixed table_name
 */
class SignedDocs extends ALL
{
    use HasFactory;
    protected $with = 'application';
    protected $table = "signed_docs";

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
    public function application()
    {
        return $this->belongsTo(Application::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

}
