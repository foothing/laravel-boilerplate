<?php

namespace App\Providers;

use App\Auth\Sentinel\Stateful;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Auth\StatefulGuard;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StatefulGuard::class, function($app){
            return new Stateful($app->make('sentinel'));
        });
    }
}
