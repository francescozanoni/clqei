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

    $data = firstNameLastNameEMail(
        $faker->firstName,
        $faker->lastName
    );
    $data['password'] = $password ?: $password = bcrypt('secret');

    return $data;
});

$factory->state(App\User::class, \App\User::ROLE_ADMINISTRATOR, [
    'role' => \App\User::ROLE_ADMINISTRATOR,
]);

$factory->state(App\User::class, \App\User::ROLE_VIEWER, [
    'role' => \App\User::ROLE_VIEWER,
]);

$factory->state(App\User::class, \App\User::ROLE_STUDENT, [
    'role' => \App\User::ROLE_STUDENT,
]);

$factory->state(App\User::class, 'male', function (Faker $faker) {
    return firstNameLastNameEMail(
        $faker->firstName('male'),
        $faker->lastName
    );
});
$factory->state(App\User::class, 'female', function (Faker $faker) {
    return firstNameLastNameEMail(
        $faker->firstName('female'),
        $faker->lastName
    );
});

/*
$student = factory(App\Models\Student::class)
    ->make();
$user = factory(App\User::class)
    ->states('student', $student->gender)
    ->create()
    ->student()
    ->save($student);
 */
