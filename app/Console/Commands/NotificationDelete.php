<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Notification;
use Illuminate\Console\Command;

class NotificationDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete deleted application\'s notification';

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
     * @return string
     */
    public function handle()
    {
        $applications = Application::onlyTrashed()->pluck('id')->toArray();
        Notification::whereIn('application_id',$applications)->delete();
        $notifications = Notification::all();
        foreach($notifications as $notification)
        {
            $application = Application::find($notification->application_id);
            if ($application === null) {
                $notification->delete();
            }
        }
        return 'Deleted Notifications Where Not Exists In Application Table';
    }
}
