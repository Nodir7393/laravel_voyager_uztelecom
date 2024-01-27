<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\StatusExtended;
use App\Enums\ApplicationStatusEnum;
use Illuminate\Console\Command;

class PerformerStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'performer:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $application_s = Application::where('performer_status','!=',null)->get();
        foreach ($application_s as $app)
        {
            $update = StatusExtended::where('name',$app->performer_status)->first();
            if($app->status === $update)
            {
                    $app->status = ApplicationStatusEnum::Extended;
            }
            $app->performer_status = $update->id;
            $app->status_extended_id = $update->id;
            $app->save();
        }
        return 1;
    }
}
