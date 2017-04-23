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
$factory->define(App\Post::class, function (Faker\Generator $faker) {
    $r = $faker->unique()->randomNumber(2);
    return [
        'title' => 'اعلان تجريبي '.$r,
        'image' => 'http://lorempixel.com/500/400/transport/',
        'user_id' => $faker->numberBetween(2,4),
        'verified_by' => 1,
        'category_id' => 1
    ];
});
