<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;
    static $phoneIntroArray = array('059', '056');
    $faker->locale = 'ar_SA';
    return [
        'name' => $faker->firstName('male').' '.$faker->lastName('male'),
        'username' => $faker->userName,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('123123'),
        'remember_token' => null, #str_random(10),
        'is_admin' => false,
        'phone' => $faker->randomElement($phoneIntroArray) . $faker->randomNumber(7),
        'address' => $faker->address
    ];
});
