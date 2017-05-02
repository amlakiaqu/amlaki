<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carsMakers = ['Acura','Alfa Romeo','Aston Martin','Audi','Bentley','BMW','Bugatti','Buick'];
        $colors = \Config::get('constants.COLORS_LIST');
        $faker = \Faker\Factory::create();
        factory(App\Post::class, 16)->create()->each(function($post) use ($carsMakers, $faker, $colors){
            $post->properties()->attach(1, ["value" => $faker->numberBetween(100000, 1000000)]); // Price
            $post->properties()->attach(2, ["value" => $post->title]); // Car Model
            $post->properties()->attach(3, ["value" => explode(' ', $post->title)[1]]); // Model Year
            $post->properties()->attach(4, ["value" => $faker->randomElement($colors)]); // Car Color
            $post->properties()->attach(5, ["value" => $faker->randomElement([3,6]) . ' + 1' ]); // Number OF Passengers
            $post->properties()->attach(6, ["value" => $faker->numberBetween(800, 2000)]); // Engine Power
        });

    }
}
