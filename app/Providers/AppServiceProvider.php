<?php

namespace App\Providers;

use App\Repositories\User\Sentinel\SentinelUserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\Sentinel\Stateful;
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

        $this->app->singleton(UserRepositoryInterface::class, function($app){
            return new SentinelUserRepository($app->make('sentinel')->createModel());
        });
    }
}
