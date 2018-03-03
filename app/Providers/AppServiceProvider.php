<?php

namespace App\Providers;

use App\Models\Compilation;
use App\Models\Location;
use App\Models\Ward;
use App\Observers\ModelObserver;
use App\Services\DataTablesPluginService;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // https://laravel.com/docs/5.5/eloquent#events
        User::observe(ModelObserver::class);
        Compilation::observe(ModelObserver::class);
        Ward::observe(ModelObserver::class);
        Location::observe(ModelObserver::class);
    }

    /**
     * Register any application services.
     */
    public function register()
    {

        // @todo ensure $countries population can stay here or must be moved to boot() method
        $countries = require(base_path('vendor/umpirsky/country-list/data/' . config('app.locale') . '/country.php'));

        $this->app->when('App\Services\CountryService')
            ->needs('$countries')
            ->give($countries);

        $this->app->bind('App\Services\DataTablesPluginService', function () {
            return new DataTablesPluginService(base_path('node_modules/datatables.net-plugins'));
        });

        $simpleBindings = [
            'App\Services\CompilationService',
            'App\Services\AcademicYearService',
            'App\Services\UserService',
            'App\Services\ImportService',
        ];
        foreach ($simpleBindings as $class) {
            $this->app->bind($class, function () use ($class) {
                return new $class();
            });
        }

    }

}
