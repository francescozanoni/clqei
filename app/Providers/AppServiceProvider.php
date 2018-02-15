<?php

namespace App\Providers;

use App\Services\AcademicYearService;
use App\Services\CompilationService;
use App\Services\DataTablesPluginService;
use App\Services\UserService;
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
        $countries = require(base_path('vendor/umpirsky/country-list/data/' . config('app.locale') . '/country.php'));

        $this->app->when('App\Services\CountryService')
            ->needs('$countries')
            ->give($countries);

        $this->app->bind('App\Services\CompilationService', function () {
            return new CompilationService();
        });

        $this->app->bind('App\Services\AcademicYearService', function () {
            return new AcademicYearService();
        });

        $this->app->bind('App\Services\DataTablesPluginService', function () {
            return new DataTablesPluginService(base_path('node_modules/datatables.net-plugins'));
        });

        $this->app->bind('App\Services\UserService', function () {
            return new UserService();
        });

    }
}
