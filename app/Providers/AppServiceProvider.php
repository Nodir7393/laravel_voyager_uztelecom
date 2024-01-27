<?php

namespace App\Providers;

use App\View\Components\laravelYajra;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use TCG\Voyager\Facades\Voyager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('laravelYajraL', laravelYajra::class);
        Paginator::useBootstrap();
        Voyager::addAction(\App\Actions\ActiveAction::class);
        Voyager::addAction(\App\Actions\AssignAction::class);
        Voyager::addAction(\App\Actions\ShowApplication::class);
        Voyager::addAction(\App\Actions\AddSignerAction::class);
    }
}
