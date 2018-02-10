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

$factory->define(App\User::class, function (Faker $faker) {
    static $password;
    
    $firstName = $faker->firstName;
    $lastName = $faker->lastName;

    return [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => strtolower($firstName . '.' . $lastName) . '@example.com',
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->state(App\User::class, 'administrator', [
    'role' => 'administrator',
]);

$factory->state(App\User::class, 'viewer', [
    'role' => 'viewer',
]);

$factory->state(App\User::class, 'student', [
    'role' => 'student',
]);

/*
'identification_number' => '12345678',
'gender' => 'male',
'nationality' => 'IT',
*/