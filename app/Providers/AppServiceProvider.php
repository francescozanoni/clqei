<?php

namespace App\Providers;

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
        $this->app->singleton(App\Services\CountryService::class, function ($app) {
          // @todo add file availability check.
          $countries = require_once(base_path('vendor/umpirsky/country-list/data/' . config('app.locale') . '/country.php'));
          return new App\Services\CountryService($countries);
        });
    }
}
