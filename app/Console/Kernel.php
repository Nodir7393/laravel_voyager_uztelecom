<?php

namespace App\Console;

use App\Console\Commands\NotificationDelete;
use App\Console\Commands\PerformerStatus;
use App\Console\Commands\Sardor;
use App\Console\Commands\Signers;
use App\Console\Commands\StatusChange;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
      NotificationDelete::class,
      Sardor::class,
      StatusChange::class,
      PerformerStatus::class,
      Signers::class,
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
         $schedule->command('overdue:time')->everyThirtyMinutes();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }


}
