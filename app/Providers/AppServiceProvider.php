<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
        //
        Validator::extend('date_format_id', function ($attribute, $value, $parameters, $validator) {
            $date = \DateTime::createFromFormat('j F Y', $value);
            return $date && $date->format('j F Y') === $value;
        });
    }
}
