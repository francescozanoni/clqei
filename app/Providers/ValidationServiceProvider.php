<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ValidationServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {

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
             *   'start_date' => 'required|date|not_overlapping_time_range:end_date,table,start_field,end_field,filter_field',
             *   'end_date' => 'required|date|not_overlapping_time_range:start_date,table,start_field,end_field,filter_field',
             * ]
             *
             * @param string $attribute current field name
             * @param mixed $value current field value
             * @param array $parameters rule parameters, e.g. ["end_date", "table", "start_field", "end_field", "filter_field"]
             * @param \Illuminate\Validation\Validator $validator
             * @return bool
             */
            function ($attribute, $value, $parameters, $validator) {

                // If the other range field is not available,
                // there's no time range to validate.
                if (Arr::has($validator->getData(), $parameters[0]) === false) {
                    return true;
                }

                // Form date range fields are retrieved and values suitably stored into variables.
                $otherFormFieldValue = Arr::get($validator->getData(), $parameters[0]);
                $rangeStartFormValue = $value;                $rangeEndFormValue = $otherFormFieldValue;
                if ($rangeEndFormValue < $rangeStartFormValue) {
                    $rangeStartFormValue = $otherFormFieldValue;                    $rangeEndFormValue = $value;
                }
                
                $tableName = $parameters[1];
                $rangeStartTableField = $parameters[2];                $rangeEndTableField = $parameters[3];
                
                // Filter field is optional.
                $filterTableFieldName = null;
                $filterTableFieldValue = null;
                if (isset($parameters[4]) === true) {                    $filterTableFieldName = $parameters[4];
                    $filterTableFieldValue = Arr::get($validator->getData(), $parameters[4]);
                }

                // Search on database.
                // http://salman-w.blogspot.it/2012/06/sql-query-overlapping-date-ranges.html
                // SELECT *
                // FROM <table>
                // WHERE start_date <= <my_end_date>
                //     AND end_date >= <my_start_date>
                $query = DB::table($tableName)
                    ->where($rangeStartTableField, '<=', $rangeEndFormValue)
                    ->where($rangeEndTableField, '>=', $rangeStartFormValue);
                if ($filterTableFieldName !== null) {
                    $query = $query->where($filterTableFieldName, $filterTableFieldValue);
                }
                return $query->exists() === false;
                              
            },
            // @todo refactor validation message localization
            [__('Overlapping time range.')]

        );

    }

    /**
     * Register any application services.
     */
    public function register()
    {

    }

}


            
        
    