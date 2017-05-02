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
use Carbon\Carbon;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\Post::class, function (Faker\Generator $faker) {
    $randomDateYear = Carbon::createFromTimestamp(rand(Carbon::now()->timestamp, Carbon::create(2000)->timestamp))->format('Y');
    $carsMakers = ['Acura','Alfa Romeo','Aston Martin','Audi','Bentley','BMW','Bugatti','Buick'];
    $images = ['https://static.wixstatic.com/media/85f891_4a8e2e8aa904478b9fae42aad9b37ced~mv2.jpg/v1/fill/w_500,h_500,al_c,q_90/file.jpg',
        'http://www.aucklandrentalcars.co.nz/uploads/cars/tiida-sedan.jpg',
        'http://www.ramsteinusedcars.com/wp-content/uploads/2017/04/7-5-500x400.jpg',
        'http://www.ramsteinusedcars.com/wp-content/uploads/2017/04/5-500x400.jpg',
        'https://s-media-cache-ak0.pinimg.com/736x/06/b2/e1/06b2e187edb394946759160aa09de94f.jpg',
        'http://www.ramsteinusedcars.com/wp-content/uploads/2017/04/3-1-500x400.jpg',
        'http://4.bp.blogspot.com/-eJL_KmJ3K7I/VpYtjtcHLKI/AAAAAAAAAQk/lcOMCRWiXbs/s1600/exotic-audi-r8.png',
        'http://www.dynastyrentals.com/wp-content/gallery/luxury-cars/luxury-porsche-panamera.png',
        'https://c758759.ssl.cf2.rackcdn.com/cropper/223025.jpg',
        'https://imgct2.aeplcdn.com/img/500/cars/Volkswagen/Passat/Volkswagen-Passat-Top-Tech-Car-In-India.jpg'];

    $defaultImageUrl = \Config::get("constants.DEFAULT_IMAGE_URL");

    return [
        'title' => $faker->randomElement($carsMakers).' '.$randomDateYear,
        'image' => $faker->optional($weight = 0.9, $default = $defaultImageUrl)->randomElement($images), // 10% chance of NULL
        'user_id' => $faker->numberBetween(2, 4),
        'verified_by' => 1,
        'category_id' => 1
    ];
});
