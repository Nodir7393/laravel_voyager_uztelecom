<?php

namespace Tests\Unit;

use App\Models\Application;
use App\Models\Branch;
use Tests\TestCase;
use Yajra\DataTables\DataTables;


class ReportServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_report()
    {
        $query = Branch::query();
        return Datatables::of($query)
            ->addColumn('count', function($branch){
                $applications = Application::where('branch_initiator_id', $branch->id)->get();
                return count($applications);
            })
            ->addColumn('tovar', function($branch){
                $applications = Application::where('branch_initiator_id', $branch->id)->where('subject',1)->get();
                return count($applications);
            })
            ->addColumn('rabota', function($branch){
                $applications = Application::where('branch_initiator_id', $branch->id)->where('subject',2)->get();
                return count($applications);
            })
            ->addColumn('usluga', function($branch){
                $applications = Application::where('branch_initiator_id', $branch->id)->where('subject',3)->get();
                return count($applications);
            })
            ->addColumn('summa', function($branch){
                $applications = Application::where('branch_initiator_id', $branch->id)->where('with_nds', '=',null)->pluck('planned_price')->toArray();
                return array_sum($applications);
            })
            ->addColumn('nds', function($branch){
                $applications = Application::where('branch_initiator_id', $branch->id)->where('with_nds', '!=',null)->pluck('planned_price')->toArray();
                return array_sum($applications);
            })
            ->make(true);
    }
}
