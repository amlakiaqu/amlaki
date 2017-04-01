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
        $admin = factory(App\User::class)->create([
		    'name' => 'Admin',
		    'username' => "admin",
		    'email' => 'admin@amlaki.com',
		    'is_admin' => true
		]);


    	// Create 10 new users
    	$users = factory(App\User::class, 10)->create();


    }
}
