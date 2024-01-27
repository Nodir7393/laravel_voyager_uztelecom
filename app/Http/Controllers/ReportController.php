<?php

namespace App\Http\Controllers;

use App\Enums\ApplicationMagicNumber;
use App\Http\Requests\ReportDateRequest;
use App\Models\ReportDate;
use App\Models\StatusExtended;
use App\Services\ApplicationService;
use App\Services\ReportDataService;
use App\Services\ReportExportService;
use App\Services\ReportService;
use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Branch;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class ReportController extends Controller
{
    const Quarterly_Planned_Report = 22;
    const Report_Statuses_Quantity = 10;

    private ReportService $service;
    private ReportDataService $data;
    private ReportExportService $exportService;

    public function __construct(ReportService $service, ReportExportService $exportService, ReportDataService $data)
    {
        $this->middleware('auth');
        $this->service = $service;
        $this->data = $data;
        $this->exportService = $exportService;
    }
    /**
     * Request da Reportlar date keladi.
     * Shuni Cache ga put qiladi.
    **/
    public function request(ReportDateRequest $request)
    {
        $data = $request->validated();
        foreach($data as $key=>$value)
        {
            $report = ReportDate::UpdateOrCreate(['report_key' => $key]);
            $report->report_value = $value;
            $report->save();
        }
        return redirect()->back();
    }
    /**
     * Nechinchi Reportligiga qarab blade korsatiladi.
     **/
    public function index($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $report = ReportDate::all();
        return match($id)
        {
            "1" => $this->data->report1_data($report),
            "2" => $this->data->report2_data($report),
            "22" => $this->data->report22_data($report),
            "3" => $this->data->report3_data($report),
            "4" => $this->data->report4_data($report),
            "5" => $this->data->report5_data($report),
            "6" => $this->data->report6_data($report),
            "7" => $this->data->report7_data($report),
            "8" => $this->data->report8_data($report),
            "9" => $this->data->report9_data($report),
            "10" => $this->data->report10_data($report),
        };
    }
    /**
     * Nechinchi Report ligiga qarab data chiqadi.
     **/
    public function report($id, Request $request)
    {
        /** @var object $user*/
        $user = auth()->user();
        if($request->startDate !== null && $request->endDate !== null){
            $request->session()->put("report_$id.startDate", $request->startDate);
            $request->session()->put("report_$id.endDate", $request->endDate);
        }
        $this->service->application_query($request);
        $reports = match ($id) {
            '1' => $this->service->report_1($request, $user),
            '2' => $this->service->report_2($request, $user),
            '3' => $this->service->report_3($request, $user),
            '4' => $this->service->report_4($request, $user),
            '5' => $this->service->report_5($request, $user),
            '6' => $this->service->report_6($request, $user),
            '7' => $this->service->report_7($request, $user),
            '8' => $this->service->report_8($request, $user),
            '9' => $this->service->report_9($request, $user),
            '10' => $this->service->report_10($request, $user),
            '22' => $this->service->report_2_2($request, $user),
        };

        return $reports;

    }

    public function report_export($model, Request $request){
        $user = auth()->user();
        if (class_exists($model)) {
            $data =  $this->exportService->export($model, $request, $user);
        }
        else{
            Log::error('Model not exists {}');

        }

        return $data;
    }
}
