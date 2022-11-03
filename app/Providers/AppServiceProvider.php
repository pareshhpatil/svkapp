<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;


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
        Validator::extend('recaptcha', 'App\\Validators\\ReCaptcha@validate');
        Validator::extend('emailexist', 'App\\Rules\\Emailexist@passes');
        if (env('APP_ENV', 'LOCAL') !== 'LOCAL') {
            \URL::forceScheme('https');
        }
        //
    }
}
