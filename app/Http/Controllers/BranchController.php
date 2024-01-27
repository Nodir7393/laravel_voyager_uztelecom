<?php

namespace App\Http\Controllers;

use App\Enums\PermissionEnum;
use App\Models\Application;
use App\Models\Branch;
use App\Models\StatusExtended;
use App\Enums\ApplicationStatusEnum;
use App\Services\ApplicationService;
use App\Services\BranchService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BranchController extends Controller
{
    /**
     * @var BranchService
     */
    public function __construct(BranchService $service)
    {
        $this->service = $service;
    }
    /**
     * admin paneldan branches ga kirsak "Show Roles" digan knopka chiqadi
     * 1 filialga tegishli bolgan shu knopka bosilganda
     * filial id si cache ga put qilinadi.
     **/
    public function edit($id)
    {
        return view('vendor.voyager.branches.signers-add',compact('id'));
    }
    /**
     * filial id si cache dan olinadi va shunga tegishli bo'lgan
     * Role lar chiqib keladi.
     **/
    public function getData($id)
    {
        return $this->service->getData($id);
    }
    /**
     * Request das kelayotgan branch_id ni users tablitsadagi select_branch_id columniga
     * saxranit qiladi.
     **/
    public function putCache(Request $request)
    {
        $user = auth()->user();
        $user->select_branch_id = $request->branch_id;
        $user->save();
        return redirect()->back();
    }
    /**
     * Zayavkalarni filialiga qarab chiqarish
     *
     * users tablitsadagi select_branch_id columni value sini olib
     * shu branch_id ga tegishli bolgan zayavkalarni ciqarb beradi
     **/
    public function ajax_branch()
    {
        /**@var object $user*/

        $user = auth()->user();
        $id = $user->select_branch_id;
        return $this->service->ajax_branch($id, $user);
    }
    /**
     * vxod qilgan user da select_branch permissionni bo'lsa
     * view ga otib ketadi.
     *
     * agar select_branch permissionni bo'lmasa
     *
     * "Вам недоступно" so'zi chiqadi.
     **/
    public function view()
    {
        if(auth()->user()->hasPermission(PermissionEnum::Select_Branch))
        {
            $branch = Branch::pluck('name','id')->toArray();
            return view('vendor.voyager.branches.view',compact('branch'));
        }else{
            return "<h1 style='text-align: center;color:red;'>".__("Unavailable_to_you")."</h1>";
        }

    }
}
