<?php

namespace App\Providers;

use Tymon\JWTAuth\JWTGuard;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::extend('jwt', function (Application $app, string $name, array $config) {
            $jwt = $app->make('tymon.jwt');
            $guard = new JWTGuard(
                $jwt,
                Auth::createUserProvider($config['provider']),
                $app['request']
            );

            $guard->setRequest($app['request']);

            return $guard;
        });
    }
}
