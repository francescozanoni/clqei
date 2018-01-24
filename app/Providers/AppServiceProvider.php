<?php

namespace App\Providers;

use App\Services\CompilationService;
use Illuminate\Support\ServiceProvider;

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
         // @todo ensure $countries population can stay here or must be moved to boot() method
         $countries = require_once(base_path('vendor/umpirsky/country-list/data/' . config('app.locale') . '/country.php'));

        $this->app->when('App\Services\CountryService')
          ->needs('$countries')
          ->give($countries);

        $this->app->bind('App\Services\CompilationService', function () {
            return new CompilationService();
        });
    }
}
