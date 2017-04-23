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
        factory(App\Post::class, 10)->create()->each(function($post){
            $post->properties()->attach(1, ["value" => random_int(1000, 100000)]);
        });

    }
}
