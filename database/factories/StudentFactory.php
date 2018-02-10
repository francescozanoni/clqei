<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\Models\Student::class, function (Faker $faker) {

    return [
        'identification_number' => $faker->regexify('[1-9][0-9]{7}'),
        'gender' => $faker->randomElement(['male', 'female']),
        'nationality' => $faker->randomElement(['IT']),
    ];
});