<?php

namespace App\Models;

use App\Enums\ApplicationStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Wildside\Userstamps\Userstamps;

class ALL extends Model
{
    use Userstamps, SoftDeletes;
}
