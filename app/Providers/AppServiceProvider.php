<?php

namespace App\Providers;

use App\Models\Compilation;
use App\Models\Location;
use App\Models\Ward;
use App\Observers\EloquentModelObserver;
use App\Services\DataTablesPluginService;
use App\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // https://laravel.com/docs/5.5/eloquent#events
        User::observe(EloquentModelObserver::class);
        Compilation::observe(EloquentModelObserver::class);
        Ward::observe(EloquentModelObserver::class);
        Location::observe(EloquentModelObserver::class);
        
        Validator::extend(
            'empty_if',
            /**
             * This validation rule is the opposite of required_if:
             * if another field has a specific value, the current field must be empty.
             * E.g.:
             * [
             *   'other_field' => 'in:value_1,value_2',
             *   'current_field' => 'empty_if:other_field,value_2',
             * ]
             *
             * @param string $attribute current field name
             * @param mixed $value current field value
             * @param array $parameters array of rule parameters, e.g. ["other_field", "value_2"]
             * @param \Illuminate\Validation\Validator $validator
             * @return bool
             */
            function ($attribute, $value, $parameters, $validator) {

                $validator->setFallbackMessages([
                    'The ' . str_replace('_', ' ', $attribute) . ' field must be empty ' .
                    'when ' . str_replace('_', ' ', $parameters[0]) . ' is ' . $parameters[1] . '.'
                ]);

                // Fix for situations where the other field is a boolean:
                // the value to search must be cast from string to boolean,
                // in order to make comparison work correctly,
                // e.g.:
                // [
                //   'other_field' => 'boolean',
                //   'current_field' => 'empty_if:other_field,false',
                // ]
                $otherFieldValue = Arr::get($validator->getData(), $parameters[0]);
                $otherFieldValueToCompare = $parameters[1];
                if ($otherFieldValueToCompare === 'true') {
                    $otherFieldValueToCompare = true;
                }
                if ($otherFieldValueToCompare === 'false') {
                    $otherFieldValueToCompare = false;
                }

                // The current field content is checked only if the other field has the specified value.
                if ($otherFieldValue === $otherFieldValueToCompare) {
                    if (is_array($value) === true) {
                        return $value === [];
                    }
                    return ((string)$value === '');
                }

                return true;
            }

        );

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


            
        
    