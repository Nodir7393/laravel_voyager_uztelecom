<?php

namespace App\Models;

use App\Events\BranchDeleted;
use App\Events\BranchSaved;
use App\Events\UserDeleted;
use App\Events\UserSaved;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Models\Role;
use App\Models\Branch;
use App\Models\Position;
use Wildside\Userstamps\Userstamps;

/**
 * @property $branch_id
 * @method static where(string $string, $role_id)
 */
class User extends \TCG\Voyager\Models\User
{
    use HasApiTokens, HasFactory, Notifiable, Userstamps, SoftDeletes;

    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
        'deleted' => UserDeleted::class,
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function position()
    {
        return $this->belongsTo(Position::class);
    }
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
