<?php

namespace App\Models;

use App\Enums\ApplicationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

/**
 * @property $id
 * @property mixed user_id
 * @property mixed user_role_id
 * @property mixed branch_initiator_id
 * @property mixed branch_id
 * @property mixed department_initiator_id
 * @property mixed|string status
 * @property mixed|null signers
 * @method static Application findOrFail($id)
 * @method static Application find($id, $columns = ['*'])
 * @method static Application where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Application whereBetween($column, array $values, $boolean = 'and', $not = false)
 * @method static Application whereDraft($value)
 * @method static Application orWhere($column, $operator = null, $value = null)
 * @method static Application whereIn($column, $values, $boolean = 'and', $not = false)
 * @method static Application whereNotNull($columns, $boolean = 'and')
 * @method static Application get($columns = ['*'])
 */
class Application extends ALL
{
    use HasFactory;
    use SoftDeletes;

    const NOT_DISTRIBUTED = 2;      // SHOW_LEADER
    const DISTRIBUTED = 1;          // SHOW_LEADER
    const PLANNER_AGREE = 3;
    const CANCELED_APP = 4;

    protected $dates = ['deleted_at'];
//    protected $hidden = ['other_files'];
    protected $table = "applications";
    protected $guarded = [];

    public function need_to_sign()
    {
        return $this->hasMany(SignedDocs::class)->where('role_id',auth()->user()->role_id)->whereNull('status');
    }

    public function type_of_purchase()
    {
        return $this->belongsTo(Purchase::class, 'type_of_purchase_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function performer()
    {
        return $this->belongsTo(User::class, 'performer_user_id', 'id')->withTrashed();
    }

    public function performer_role()
    {
        return $this->belongsTo(Roles::class, 'performer_role_id', 'id');
    }

    public function performer_leader()
    {
        return $this->belongsTo(User::class, 'performer_leader_user_id', 'id')->withTrashed();
    }

    public function branch_leader()
    {
        return $this->belongsTo(User::class, 'branch_leader_user_id', 'id')->withTrashed();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_produced_id', 'country_alpha3_code');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }

    public function branch_signers()
    {
        return $this->belongsTo(Branch::class, 'branch_initiator_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_initiator_id', 'id')->withDefault(['name' => '']);
    }

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'type_of_purchase_id', 'id')->withDefault(['name' => '']);
    }

    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject', 'id')->withDefault(['name' => '']);
    }

    public function signedDocs()
    {
        return $this->hasMany(SignedDocs::class);
    }

    /**
     *
     * Function  delete
     * @return  bool
     */
    final public function delete() : bool
    {
        $this->deleted_by = auth()->id();
        return parent::delete();
    }
}
