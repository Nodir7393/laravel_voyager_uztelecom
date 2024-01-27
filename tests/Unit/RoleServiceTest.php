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


class RoleServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_getData()
    {
        $query = Roles::query();
        return Datatables::of($query)
            ->editColumn('branch_id', function ($query) {
                $all = json_decode($query->branch_id);
                $branch = $all ? Branch::find($all)->pluck('name')->toArray(): [];
                return $branch;
            })
            ->addColumn('action', function($row){
                $edit_e = "/admin/roles/{$row->id}/edit";
                $destroy_e = route("voyager.roles.delete",$row->id);
                $app_edit = __('Изменить');
                $app_delete = __('Удалить');;
                $bgcolor = setting('color.edit');
                $color = $bgcolor ? 'white':'black';
                $edit = "<a style='background-color: {$bgcolor};color: {$color}' href='{$edit_e}' class='m-1 col edit btn btn-sm'>$app_edit</a>";
                $bgcolor = setting('color.delete');
                $color = $bgcolor ? 'white':'black';
                $destroy = "<a style='background-color: {$bgcolor};color: {$color}' href='{$destroy_e}' class='m-1 col show btn btn-sm'>$app_delete</a>";
                return "<div class='row'>
                        {$edit}
                        {$destroy}
                        </div>";
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
