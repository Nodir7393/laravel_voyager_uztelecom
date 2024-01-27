<?php

namespace App\Console\Commands;

use App\Http\Controllers\Site\ApplicationController;
use App\Services\ApplicationService;
use Illuminate\Console\Command;

class StatusChange extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'status:change';

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
    public function __construct(ApplicationController $service)
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
        return $this->service->change_status();
    }
}
