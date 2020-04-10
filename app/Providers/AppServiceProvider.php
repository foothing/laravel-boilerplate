<?php

namespace App\Providers;

use App\Activation;
use App\Repositories\User\ActivationRepositoryInterface;
use App\Repositories\User\Laravel\ActivationRepository;
use App\Repositories\User\Laravel\UserRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Services\Auth\Laravel\RegisterService;
use App\Services\Auth\RegisterInterface;
use App\User;
use Illuminate\Support\ServiceProvider;
use App\Services\Auth\LoginInterface;
use App\Services\Auth\Laravel\LoginService;
use App\Services\Auth\PasswordResetInterface;
use App\Services\Auth\Laravel\PasswordService;
use App\Repositories\User\ReminderRepositoryInterface;
use App\Repositories\User\Laravel\ReminderRepository;
use Laravel\Dusk\DuskServiceProvider;

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
        // Uncomment for Sentinel.
        //$this->app->singleton(StatefulGuard::class, function($app){
        //    return new Stateful($app->make('sentinel'));
        //});

        $this->app->singleton(UserRepositoryInterface::class, function(){
            return new UserRepository(new User());
        });

        $this->app->singleton(ActivationRepositoryInterface::class, function(){
            return new ActivationRepository(new Activation());
        });

        $this->app->singleton(RegisterInterface::class, function($app){
            return $app->make(RegisterService::class);
        });

        $this->app->singleton(LoginInterface::class, function($app){
            return $app->make(LoginService::class);
        });

        $this->app->singleton(PasswordResetInterface::class, function($app){
            return $app->make(PasswordService::class);
        });

        $this->app->singleton(ReminderRepositoryInterface::class, function($app){
            return $app->make(ReminderRepository::class);
        });

        if ($this->app->environment('local', 'testing')) {
            $this->app->register(DuskServiceProvider::class);
        }
    }
}
