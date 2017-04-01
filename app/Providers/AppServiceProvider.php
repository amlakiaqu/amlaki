<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Set the default length of string type in migrations to 191
        Schema::defaultStringLength(191);

        // Write custom validation rule for user phone number
        Validator::extend('phone', function ($attribute, $value, $parameters, $validator) {
            $pattern = config('constants.PHONE_VALIDATION_REGX');
            return preg_match($pattern, $value) == 1;
        }, __('Enter a valid phone number'));
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}