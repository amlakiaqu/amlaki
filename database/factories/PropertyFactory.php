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
$factory->define(App\Property::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->text(191),
        'code' => strtoupper($faker->unique()->word(10)),
        'value_type' => $faker->randomElement($array = array ('STRING','NUMBER','FLOAT','COLOR','RANGE','SELECT','MULTI_SELECT','MEDIA','RADIO','MULTI_SELECT_CHECKBOX')),
        'extra_settings' => null
    ];
});
