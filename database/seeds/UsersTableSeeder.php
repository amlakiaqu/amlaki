<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create new admin user
        factory(App\User::class)->create([
            'name' => 'Admin',
            'username' => "admin",
            'email' => 'admin@amlaki.com',
            'is_admin' => true
        ]);

        factory(App\User::class)->create([
            'name' => 'test',
            'username' => "test",
            'email' => 'test@amlaki.com',
            'is_admin' => false
        ]);

        // Create 5 new users
        factory(App\User::class, 5)->create();
    }
}
