<?php

namespace App\Console\Commands;

use App\Services\ApplicationService;
use Illuminate\Console\Command;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

class Signers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'application:signers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Application Signers';

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
     * @return void
     */
    public function handle()
    {
        $service = new ApplicationService();
        return $service->restore_signers();
    }
}
