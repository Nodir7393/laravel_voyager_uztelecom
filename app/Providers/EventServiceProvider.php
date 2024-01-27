<?php

namespace App\Providers;

use App\Events\BranchDeleted;
use App\Events\BranchSaved;
use App\Events\CheckAndClearTopBranchesCache;
use App\Events\CheckAndClearTopUsersCache;
use App\Events\ClearBranchCache;
use App\Events\ClearUsersCache;
use App\Events\UserDeleted;
use App\Events\UserSaved;
use App\Models\Application;
use App\Models\SignedDocs;
use App\Observers\ApplicationObserver;
use App\Observers\SignDocsObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],

        BranchSaved::class => [
            ClearBranchCache::class,
        ],
        BranchDeleted::class => [
            CheckAndClearTopBranchesCache::class,
        ],

        UserSaved::class => [
            ClearUsersCache::class,
        ],
        UserDeleted::class => [
            CheckAndClearTopUsersCache::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        SignedDocs::observe(SignDocsObserver::class);
        Application::observe(ApplicationObserver::class);
    }
}
