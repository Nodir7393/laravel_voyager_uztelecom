<?php

namespace App\Providers;

use App\Models\Branch;
use App\Models\Notification;
use App\Models\StatusExtended;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     *
     *
     * Cache Taglarini faqat boot functionga yozish kerak
     */
    public function boot()
    {
        Cache::remember('branches', 900, function() {
            return Branch::get();
        });
        Cache::remember('users', 900, function() {
            return User::get();
        });
    }
}
