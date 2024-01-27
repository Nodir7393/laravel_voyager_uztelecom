<?php

namespace Tests\Unit;

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserController;
use App\Models\Application;
use App\Models\Branch;
use App\Models\Department;
use App\Models\Notification;
use App\Models\Roles;
use App\Models\SignedDocs;
use App\Models\User;
use App\Services\ApplicationService;
use App\Services\DepartmentService;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Yajra\DataTables\DataTables;


class UserServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_changeLeader()
    {
        $user = User::find(9);
        $user->leader = $user->leader?0:1;
        $user->save();
    }
}
