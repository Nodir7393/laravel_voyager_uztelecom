<?php

namespace App\Jobs;

use App\Http\Requests\VoteApplicationRequest;
use App\Models\Application;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class VoteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $application;
    private $request;
    public function __construct(Application $application, VoteApplicationRequest $request)
    {
        $this->application = $application;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @return Application
     */
    public function handle()
    {
        DB::beginTransaction();
        try{
            $application = $this->application;

            if($this->request->status == 2)
                $this->application->status = Application::PLANNER_AGREE;
            else{
                $this->application->status = Application::CANCELED_APP;
                $this->application->comment = $this->request->comment;
            }
            $application->save();
        } catch (Exception $exception){
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $this->application;
    }
}
