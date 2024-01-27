<?php

namespace App\Console\Commands;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use App\Models\SignedDocs;
use App\Observers\ApplicationObserver;
use App\Observers\SignDocsObserver;
use App\Services\ApplicationService;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use DateTime;
use Illuminate\Support\Carbon;

class Sardor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'overdue:time';

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
        $now = strtotime(Carbon::now()->toDateTimeString());
        $app_1 = Application::where('status','in_process')->orWhere('status','new')->get();
        foreach ($app_1 as $application)
        {
            $app_time = strtotime($application->created_at->toDateTimeString());
            $overdue_time = setting('admin.overdue_time');
            $diff = $app_time - $now;
            $dt1 = new DateTime("@0");
            $result = abs($diff);
            $dt2 = new DateTime("@{$result}");
            $day = $dt1->diff($dt2)->format('%a');
            if($day >= $overdue_time)
            {
                $application->status = 'overdue';
                $array = json_decode($application->signers);
                $message = "{$application->id} "."{$application->name} ".setting('admin.application_overdue');
                $service   = new ApplicationService();
                $service->sendNotifications($array, $application,$message);
                $application->save();
            }
        }
    }
}
