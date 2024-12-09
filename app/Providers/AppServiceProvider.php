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
        Validator::extend('text_date_format', function ($attribute, $value, $parameters, $validator) {
            $value = preg_replace('/^0/', '', $value);
            $date = \DateTime::createFromFormat('j F Y', $value);
            return $date && $date->format('j F Y') === $value;
        });
    }
}
