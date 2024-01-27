<?php

namespace App\Console\Commands;

use App\Http\Controllers\Site\ApplicationController;
use App\Models\Application;
use App\Services\ApplicationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StatusAndPerformerStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:performer_status_change';

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
     public $service;
    public function __construct(ApplicationService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->service->StatusChangeToPerformerStatus();
    }
}
