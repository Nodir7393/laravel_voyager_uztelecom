<?php

namespace App\Console\Commands;

use App\Enums\ApplicationMagicNumber;
use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\ReportDate;
use App\Models\Resource;
use App\Models\SignedDocs;
use App\Models\StatusExtended;
use App\Observers\ApplicationObserver;
use App\Observers\SignDocsObserver;
use App\Reports\One;
use App\Services\ApplicationService;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\DataTables;

class Tester extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tester:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Overdue Time in Application';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $data= One::data();


        /** @var DataTable $query */
        $query = Datatables::of($query);

        foreach ($data as $item) {
        $query->addColumn($item['name'], $item['data']);
        }

        $query->rawColumns(['status'])
            ->make(true);


        return  $query;
        /*

        return Datatables::of($query)

            ->editColumn('branch_id', function($application)
            {
                return $application->branch_id ? $application->branch->name:"";
            })
            ->editColumn('performer_user_id', function($application)
            {
                return $application->performer->name ?? $application->performer_user_id;
            })
            ->editColumn('department_initiator_id', function($application)
            {
                return $application->department_initiator_id ? $application->department->name:"";
            })
            ->addColumn('phone', function($application)
            {
                return $application->user->phone ? $application->user->phone:"Not Phone Number";
            })
            ->editColumn('user_id', function($application)
            {
                return $application->user->name;
            })
            ->editColumn('type_of_purchase_id', function($application)
            {
                return $application->type_of_purchase_id ? $application->purchase->name:'';
            })
            ->editColumn('subject', function($application)
            {
                return $application->subject ? $application->subjects->name:'';
            })
            ->addColumn('planned_price', function ($query) {
                return !Str::contains($query->planned_price, ' ') ? number_format($query->planned_price, ApplicationMagicNumber::zero, '', ' ') : $query->planned_price;
            })
            ->editColumn('with_nds', function($application)
            {
                return $application->with_nds ?'Да':'Нет';
            })
            ->editColumn('status', function ($query) {

                $status = $query->status;
                $color = setting("color.{$status}");
                if ($query->performer_status !== null) {
                    $a = StatusExtended::find($query->performer_status);
                    $status = $a->name;
                    $color = $a->color;
                }
                return json_encode(['backgroundColor' => $color,'app' => $this->translateStatus($status),'color' => $color ? 'white':'black']);
            })
            ->editColumn('product', function($application)
            {
                if($application->resource_id != null)
                {
                    foreach (json_decode($application->resource_id) as $product)
                    {
                        $all[] = Resource::withTrashed()->find($product)->name;
                    }
                    return $all;
                }

            })
            ->addColumn('tovar_1', function($branch)
            {
                $date = ReportDate::where('report_key','date_3_month')->pluck('report_value')[0];
                $start_date = Carbon::parse("{$date}-01")
                    ->toDateTimeString();

                $end_date = Carbon::parse("{$date}-31")
                    ->toDateTimeString();
                $applications = $this->application_query()->whereBetween('created_at',[$start_date,$end_date])->where('branch_id', $branch->id)->where('subject',ApplicationMagicNumber::one)->where('with_nds','=',null)->pluck('planned_price')->toArray();
                $result = array_sum(preg_replace( '/[^0-9]/', '', $applications));
                return $result ? number_format($result, ApplicationMagicNumber::zero, '', ' ') : '0';
            })
            ->rawColumns(['status'])
            ->make(true);

        */

        var_dump($item);


    }
}
