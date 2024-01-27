<?php

namespace App\Jobs;

use App\Http\Requests\ApplicationRequest;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class UpdateApplicationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $applicationRequest;
    public function __construct(ApplicationRequest $applicationRequest)
    {
        $this->applicationRequest = $applicationRequest;
    }

    /**
     * Execute the job.
     *
     * @return Application
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $application= new Application();
            $application->name = $this->applicationRequest->name;
            $application->save();
        } catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }
        DB::commit();
        return $application;
    }
}
