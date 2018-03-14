<?php

use Faker\Generator as Faker;
use App\User;

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

$factory->define(User::class, function (Faker $faker) {
    static $password;

    $data = firstNameLastNameEMail(
        $faker->firstName,
        $faker->lastName
    );
    $data['password'] = $password ?: $password = bcrypt('secret');

    return $data;
});

$factory->state(User::class, User::ROLE_ADMINISTRATOR, [
    'role' => User::ROLE_ADMINISTRATOR,
]);

$factory->state(User::class, User::ROLE_VIEWER, [
    'role' => User::ROLE_VIEWER,
]);

$factory->state(User::class, User::ROLE_STUDENT, [
    'role' => User::ROLE_STUDENT,
]);

$factory->state(User::class, 'male', function (Faker $faker) {
    return firstNameLastNameEMail(
        $faker->firstName('male'),
        $faker->lastName
    );
});
$factory->state(User::class, 'female', function (Faker $faker) {
    return firstNameLastNameEMail(
        $faker->firstName('female'),
        $faker->lastName
    );
});
