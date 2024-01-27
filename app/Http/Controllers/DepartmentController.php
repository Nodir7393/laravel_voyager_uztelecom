<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Roles;
use App\Services\DepartmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Yajra\DataTables\DataTables;

class DepartmentController extends Controller
{
    public function __construct(DepartmentService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        return view('vendor.voyager.departments.browse');
    }
    public function getData()
    {
        $query = Department::query();
        return $this->service->getData($query);
    }
}
