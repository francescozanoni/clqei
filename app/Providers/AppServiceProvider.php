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
        
        Validator::extend(
            'not_overlapping_time_range',
            /**
             * This validation rule ensures the provided time range does not overlap
             * any existing time ranges on a table, optionally filtered by the value of another field.
             * E.g.:
             * [
             *   'start_date' => 'required|date|not_overlapping_time_range:end_date,table_name,range_start_field,range_end_field,other_field',
             *   'end_date' => 'required|date|not_overlapping_time_range:start_date,table_name,range_start_field,range_end_field,other_field',
             * ]
             *
             * @param string $attribute current field name
             * @param mixed $value current field value
             * @param array $parameters array of rule parameters, e.g. ["other_field", "value_2"]
             * @param \Illuminate\Validation\Validator $validator
             * @return bool
             */
            function ($attribute, $value, $parameters, $validator) {

                $validator->setFallbackMessages(['Overlapping time range.']);

                $otherFormFieldValue = Arr::get($validator->getData(), $parameters[0]);
                $rangeStartFormValue = $value;                $rangeEndFormValue = $otherFormFieldValue;
                if ($rangeEndFormValue < $rangeStartFormValue) {
                    $rangeStartFormValue = $otherFormFieldValue;                    $rangeEndFormValue = $value;
                }
                
                $tableName = $parameters[1];
                $rangeStartTableField = $parameters[2];                $rangeEndTableField = $parameters[3];
                
                $filterFieldTableName = null;
                $filterFieldTableValue = null;
                if (isset($parameters[4]) === true) {                    $filterFieldTableName = $parameters[4];
                    $filterFieldTableValue = Arr::get($validator->getData(), $parameters[4]);
                }

                // Search on database.
                if (false) {
                    return false;
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


            
        
    